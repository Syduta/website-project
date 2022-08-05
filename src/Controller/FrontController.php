<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Subject;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\SubjectType;
use App\Form\UserType;
use App\Repository\ActualityRepository;
use App\Repository\CommentRepository;
use App\Repository\ForumRepository;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    public function news(ActualityRepository $actualityRepository){
        $news = $actualityRepository->findAll();
        return $this->render('front/news.html.twig',['news'=>$news]);
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

    public function forums(ForumRepository $forumRepository){
        $forums = $forumRepository->findAll();
        return $this->render('front/forums.html.twig',['forums'=>$forums]);
    }

    /**
     * @Route("/forum/{id}",name="forum")
     */

    public function forum($id, ForumRepository $forumRepository, EntityManagerInterface $entityManager, Request $request, SubjectRepository $subjectRepository){
        $forum = $forumRepository->find($id);
        $subject = new Subject();
        $subject->setUser($this->getUser());
        $subject->setDate(new \DateTime('NOW'));
        $subject->setForum($forum);
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $entityManager->persist($subject);
            $entityManager->flush();
            $this->addFlash('success','subject added');
        }
        return $this->render('front/forum.html.twig',[
            'forum'=>$forum,
            'form'=>$form->createView(),
            ]);
    }

    /**
     * @Route("/comment/{id}",name="comment")
     */

    public function comment($id, EntityManagerInterface $entityManager, Request $request, SubjectRepository $subjectRepository, CommentRepository $commentRepository)
    {
        $subject = $subjectRepository->find($id);
        $comment = new Comment();
        $comment->setUser($this->getUser());
        $comment->setDate(new \DateTime('NOW'));
        $comment->setSubject($subject);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'comment added');
//            $this->redirectToRoute('forum');
        }
        return $this->render('front/comment.html.twig',[
            'subject'=>$subject,
            'form'=>$form->createView(),
        ]);

    }



    /**
     * @Route("/update-profile",name="update-profile")
     */

    public function updateProfile( Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, UserPasswordHasherInterface $userPasswordHasher){
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
}