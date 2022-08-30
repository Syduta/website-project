<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Subject;
use App\Entity\User;
use App\Entity\Message;
use App\Form\CommentType;
use App\Form\MessageType;
use App\Form\SubjectType;
use App\Form\UserType;
use App\Repository\ActualityRepository;
use App\Repository\CommentRepository;
use App\Repository\ForumRepository;
use App\Repository\MessageRepository;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class FrontController extends AbstractController
{
    /**
     * @Route("/news",name="news")
     */

    public function news(ActualityRepository $actualityRepository, Request $request, PaginatorInterface $paginator){
        $news = $actualityRepository->findAll();
        $newsPagination = $paginator->paginate(
            $news,
            $request->query->getInt('page',1),
            3
        );
        return $this->render('front/news.html.twig',['news'=>$newsPagination]);
    }

    /**
     * @Route("/new/{id}",name="new")
     */

    public function new($id, ActualityRepository $actualityRepository){
        $new = $actualityRepository->find($id);
        return $this->render('front/new.html.twig',['new'=>$new]);
    }

    /**
     * @Route("/forums",name="forums")
     */

    public function forums(ForumRepository $forumRepository, Request $request, PaginatorInterface $paginator){
        $forums = $forumRepository->findAll();
        $forumsPagination = $paginator->paginate(
            $forums,
            $request->query->getInt('page',1),
            3
        );
        return $this->render('front/forums.html.twig',['forums'=>$forumsPagination]);
    }
    // Création de l'url et du nom que l'on pourra rappeler dans nos templates
    /**
     * @Route("/forum/{id}",name="forum")
     */
    // Création de la fonction forum, on injecte les instances qui vont nous servir par la suite
    public function forum($id, ForumRepository $forumRepository, EntityManagerInterface $entityManager, Request $request){
        // on trouve le forum en question grace à son id
        $forum = $forumRepository->find($id);
        // instance d'un nouveau sujet, en créant une nouvelle ligne dans l'entité sujet
        $subject = new Subject();
        // on donne à l'utilisateur connecté le statut de créateur du sujet
        $subject->setUser($this->getUser());
        // et défini la date de création avec la date du moment automatiquement
        $subject->setDate(new \DateTime('NOW'));
        // on lie le sujet au bon forum
        $subject->setForum($forum);
        // et donne la valeur true à isPublished par défaut
        $subject->setIsPublished(1);
        // on utilise le formulaire subjecttype qui servira pour créer le nouvel sujet
        $form = $this->createForm(SubjectType::class, $subject);
        // $request gère les données de la requête pour ensuite pouvoir les traiter
        $form->handleRequest($request);
        // si le formulaire soumis est valide
        if($form->isSubmitted() && $form->isValid()){
            // Entity manager nous sert à envoyer le tout en base de données
            $entityManager->persist($subject);
            $entityManager->flush();
            // On affiche un message flash et on redirige sur la page forums si un sujet a été créé
            $this->addFlash('success','subject added');
            return $this->redirectToRoute('forums');
        }
        // sur la page forum on peut afficher le formulaire avec la variable form
        return $this->render('front/forum.html.twig',[
            'forum'=>$forum,
            'form'=>$form->createView(),
            ]);
    }

    /**
     * @Route("/comment/{id}",name="comment")
     */

    public function comment($id, EntityManagerInterface $entityManager, Request $request, SubjectRepository $subjectRepository)
    {
        $subject = $subjectRepository->find($id);
        $comment = new Comment();
        $comment->setUser($this->getUser());
        $comment->setDate(new \DateTime('NOW'));
        $comment->setSubject($subject);
        $comment->setIsPublished(1);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'comment added');
            return $this->redirectToRoute('forums');
        }
        return $this->render('front/comment.html.twig',[
            'subject'=>$subject,
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/connexion",name="connexion")
     */

    public function connexion(){
        $user = $this->getUser();
        return $this->render('connexion/connexion.html.twig');
    }

    /**
     * @Route("/update-profile",name="update-profile")
     */

    public function updateProfile( Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger,
                                   UserPasswordHasherInterface $userPasswordHasher){
        $user= $this->getUser();
        $form= $this->createForm(UserType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
            $picture = $form->get('picture')->getData();
            $originalFileName = pathinfo($picture->getClientOriginalName(),PATHINFO_FILENAME);
            $safeFileName = $slugger->slug($originalFileName);
            $newFileName = $safeFileName.'-'.uniqid().'.'.$picture->guessExtension();
            $picture->move(
                $this->getParameter('images_directory'),$newFileName
            );
            $user->setPicture($newFileName);
            $plainPassword=$form->get('password')->getData();
            $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success','profile updated');
        }
        return $this->render("front/update-profile.html.twig",['form'=>$form->createView()]);
    }

    /**
     * @Route("/messages",name="messages")
     */

    public function messages(){

        return $this->render('front/messages.html.twig');
    }
    // Création de l'url et du nom que l'on pourra rappeler dans nos templates
    /**
     * @Route("/send",name="send")
     */
    // Création de la fonction "envoyer message",  on injecte les instances qui vont nous servir par la suite
    public function sendMessage(Request $request, EntityManagerInterface $entityManager){
        // instance d'un nouveau message, on crée une nouvelle ligne dans l'entité message
        $message = new Message();
        // on défini la date de création
        $message->setCreatedAt(new \DateTimeImmutable('NOW'));
        // on utilise le formulaire messagetype pour créer le message
        $form = $this->createForm(MessageType::class,$message);
        // request gère les données avant de les traiter
        $form->handleRequest($request);
        // si le formulaire soumis est valide
        if($form->isSubmitted() && $form->isValid()){
            // l'utilisateur connecté sera l'expéditeur
            $message->setSender($this->getUser());
            // Entity manager nous sert à envoyer le tout en base de données
            $entityManager->persist($message);
            $entityManager->flush();
            // un message flash vient nous confirmé l'envoi du message
            $this->addFlash('success','message sent');
            // on redirige vers la boite de réception
            return $this->redirectToRoute('messages');
        }
        // sur la page send on peut afficher le formulaire avec la variable form
        return $this->render('front/send.html.twig',[
        'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/received",name="received")
     */

    public function receivedMessage(){
        return $this->render('front/received.html.twig');
    }

    /**
     * @Route("/read/{id}",name="read")
     */

    public function readMessage(Message $message,EntityManagerInterface $entityManager){
        $message->setIsRead(1);
        $entityManager->persist($message);
        $entityManager->flush();
        return $this->render('front/read.html.twig', compact("message"));
    }

    /**
     * @Route("/delete-message/{id}",name="delete-message")
     */

    public function deleteMessage(Message $message,EntityManagerInterface $entityManager){
        $entityManager->remove($message);
        $entityManager->flush();
        return $this->redirectToRoute("received");
    }

    /**
     * @Route("/sent",name="sent")
     */

    public function sentMessage()
    {
        return $this->render('front/sent.html.twig');
    }

}