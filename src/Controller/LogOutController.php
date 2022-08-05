<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class LogOutController extends AbstractController
{
    /**
     * @Route("/log-out",name="log-out",methods={"GET"})
     */

    public function logOut(){
        // controller can be blank: it will never be called!
//        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}