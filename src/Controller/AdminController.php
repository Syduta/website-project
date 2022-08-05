<?php

namespace App\Controller;

use App\Entity\Actuality;
use App\Entity\Forum;
use App\Entity\User;
use App\Form\ActualityType;
use App\Form\ForumType;
use App\Repository\ActualityRepository;
use App\Repository\ForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin/new-actu",name="admin-new-actu")
     */

    public function newActu(EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger){
        $actu = new Actuality();
        $actu->setUser($this->getUser());
        $form = $this->createForm(ActualityType::class, $actu);
        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $picture = $form->get('picture')->getData();
            $originalFileName = pathinfo($picture->getClientOriginalName(),PATHINFO_FILENAME);
            $safeFileName = $slugger->slug($originalFileName);
            $newFileName = $safeFileName.'-'.uniqid().'.'.$picture->guessExtension();
            $picture->move(
                $this->getParameter('images_directory'),$newFileName
            );
            $actu->setPicture($newFileName);
            $entityManager->persist($actu);
            $entityManager->flush();
            $this->addFlash('success','news added');
        }
        return $this->render("/admin/new-actu.html.twig",
        ['form'=>$form->createView()]);
    }

    /**
     * @Route("/admin/delete-actu/{id}",name="admin-delete-actu")
     */

    public function deleteActu($id, ActualityRepository $actualityRepository, EntityManagerInterface $entityManager){
        $actu = $actualityRepository->find($id);
        if(!is_null($actu)){
            $entityManager->remove($actu);
            $entityManager->flush();
            $this->addFlash('success','actuality deleted');
            return$this->redirectToRoute('news');
        }else{
            return$this->redirectToRoute('news');
        }
    }

    /**
     * @Route("/admin/update-actu/{id}",name="admin-update-actu")
     */

    public function updateActu($id, ActualityRepository $actualityRepository, EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger){
        $actu = $actualityRepository->find($id);
        $form = $this->createForm(ActualityType::class,$actu);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $picture = $form->get('picture')->getData();
            $originalFileName = pathinfo($picture->getClientOriginalName(),PATHINFO_FILENAME);
            $safeFileName = $slugger->slug($originalFileName);
            $newFileName = $safeFileName.'-'.uniqid().'.'.$picture->guessExtension();
            $picture->move(
                $this->getParameter('images_directory'),$newFileName
            );
            $actu->setPicture($newFileName);
            $entityManager->persist($actu);
            $entityManager->flush();
            $this->addFlash('success','new updated');
        }
        return $this->render("admin/update-actu.html.twig",['form'=>$form->createView()]);
    }

    /**
     * @Route("/admin/new-forum",name="admin-new-forum")
     */

    public function newForum(EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger){
        $forum = new Forum();
        $forum->setUser($this->getUser());
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $picture = $form->get('picture')->getData();
            $originalFileName = pathinfo($picture->getClientOriginalName(),PATHINFO_FILENAME);
            $safeFileName = $slugger->slug($originalFileName);
            $newFileName = $safeFileName.'-'.uniqid().'.'.$picture->guessExtension();
            $picture->move(
                $this->getParameter('images_directory'),$newFileName
            );
            $forum->setPicture($newFileName);
            $entityManager->persist($forum);
            $entityManager->flush();
            $this->addFlash('success','forum created');
        }
        return $this->render("/admin/new-forum.html.twig",
            ['form'=>$form->createView()]);
    }

    /**
     * @Route("/admin/delete-forum/{id}",name="admin-delete-forum")
     */

    public function deleteForum($id, ForumRepository $forumRepository, EntityManagerInterface $entityManager){
        $forum = $forumRepository->find($id);
        if(!is_null($forum)){
            $entityManager->remove($forum);
            $entityManager->flush();
            $this->addFlash('success','forum deleted');
            return$this->redirectToRoute('forums');
        }else{
            return$this->redirectToRoute('forums');
        }
    }

    /**
     * @Route("/admin/update-forum/{id}",name="admin-update-forum")
     */

    public function updateForum($id, ForumRepository $forumRepository, EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger){
        $forum = $forumRepository->find($id);
        $form = $this->createForm(ForumType::class,$forum);
        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
            $picture = $form->get('picture')->getData();
            $originalFileName = pathinfo($picture->getClientOriginalName(),PATHINFO_FILENAME);
            $safeFileName = $slugger->slug($originalFileName);
            $newFileName = $safeFileName.'-'.uniqid().'.'.$picture->guessExtension();
            $picture->move(
                $this->getParameter('images_directory'),$newFileName
            );
            $forum->setPicture($newFileName);
            $entityManager->persist($forum);
            $entityManager->flush();
            $this->addFlash('success','forum updated');
        }
        return $this->render("admin/update-forum.html.twig",['form'=>$form->createView()]);
    }
}