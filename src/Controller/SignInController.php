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
{
    /**
     * @Route("/sign-in",name="sign-in")
     */

    public function signIn(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SluggerInterface $slugger){
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
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
            $this->addFlash('success','Account created');
            return $this->redirectToRoute("home");
        }
        return $this->render('connexion/sign-in.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}