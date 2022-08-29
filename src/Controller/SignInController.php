<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class SignInController extends AbstractController
{       // Création de l'url et du nom que l'on pourra rappeler dans nos templates
    /**
     * @Route("/sign-in",name="sign-in")
     */
        // Création de la methode "inscription", on injecte les instances qui vont nous servir par la suite
    public function signIn(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager,
                           SluggerInterface $slugger){
        // on crée une nouvelle ligne dans l'entité user
        $user = new User();
        // comme le formulaire qui suit ne traite pas ce champ et qu'il est obligatoire on donne le role user
        // pour que symfony puisse traiter la demande
        $user->setRoles(['ROLE_USER']);
        // on utilise le formulaire usertype qui servira pour créer le nouvel user
        $form = $this->createForm(UserType::class, $user);
        // $request gère les données de la requête pour ensuite pouvoir les traiter
        $form->handleRequest($request);
        // si le formulaire soumis est valide
        if ($form->isSubmitted()&&$form->isValid()){
            // on récupère l'image soumise dans le formulaire
            $picture = $form->get('picture')->getData();
            // et avec pathinfo le nom du fichier
            $originalFileName = pathinfo($picture->getClientOriginalName(),PATHINFO_FILENAME);
            // que l'on slug
            $safeFileName = $slugger->slug($originalFileName);
            // pour supprimer les espaces qui pourraient nous gêner en base de données
            $newFileName = $safeFileName.'-'.uniqid().'.'.$picture->guessExtension();
            // l'image est enregistrée dans le dossier prédéfini
            $picture->move(
                $this->getParameter('images_directory'),$newFileName
            );
            // on enregistre l'image comme image d'utilisateur
            $user->setPicture($newFileName);
            // récupère le mot de passe entré dans le formulaire
            $plainPassword=$form->get('password')->getData();
            // le hache grâce à userpasswordhasher
            $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
            // on enregistre le mot de passe haché
            $user->setPassword($hashedPassword);
            // Entity manager nous sert à envoyer le tout en base de données
            $entityManager->persist($user);
            $entityManager->flush();
            // On affiche un message flash et on redirige le nouvel utilisateur sur la page home
            $this->addFlash('success','Account created');
            return $this->redirectToRoute("home");
        }
        // sur la page connexion on peut afficher le formulaire avec la variable form
        return $this->render('connexion/sign-in.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}