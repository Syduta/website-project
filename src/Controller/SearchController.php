<?php

namespace App\Controller;

use App\Repository\ActualityRepository;
use App\Repository\ForumRepository;
use App\Repository\UserRepository;
use App\Services\GameApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class SearchController extends AbstractController
{
    /**
     * @Route("/find",name="find")
     */

    public function search(Request $request,GameApi $gameApi, ActualityRepository $actualityRepository, ForumRepository $forumRepository){
        $search = $request->query->get('search');
        $news = $actualityRepository->searchByWord($search);
        $forums = $forumRepository->searchByWord($search);
        $gameSearch = $gameApi->searchGames($search);


        if((!empty($gameSearch)) || (!empty($news)) || (!empty($forums))){

        return $this->render("find.html.twig",[
            'forums'=>$forums,
            'news'=>$news,
            'games'=>$gameSearch
            ]);
        }else{
            $this->addFlash('error', 'Your search gave nothing');
        }
    }
}