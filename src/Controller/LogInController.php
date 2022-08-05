<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class LogInController extends AbstractController
{
    /**
     * @Route("/log-in",name="log-in")
     */

    public function logIn(AuthenticationUtils $authenticationUtils){
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $pseudo = $authenticationUtils->getLastUsername();
        $this->addFlash('success','Connected');

        return $this->render('connexion/log-in.html.twig', [
            'pseudo' => $pseudo,
            'error' => $error,
        ]);
    }
}