<?php

namespace App\Controller;
use App\Services\GameApi;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GamesController extends AbstractController
{   // Création de l'url et du nom que l'on pourra rappeler dans nos templates
    /**
     * @Route("/games",name="games")
     */
    // Création de la methode jeux, on injecte le service qui fait appel à l'api
    public function games(GameApi $gameApi){
        // comme la réponse de l'api est un tableau on récupère les jeux avec getGames
        $games = $gameApi->getGames();
        // et renvoie le tout au template games avec comme variable games qui aura en elle les données
        return $this->render('front/games.html.twig',[
            'games'=>$games
        ]);
    }

    // Création de l'url et du nom que l'on pourra rappeler dans nos templates
    /**
     * @Route("/game/{id}",name="game")
     */
    // Création de la methode jeu, on injecte le service qui fait appel à l'api ainsi que $id
    // qui nous permettra de cibler un jeu grâce à son id
    public function game($id, GameApi $gameApi){
        // comme la réponse de l'api est un tableau on récupère le jeu avec getGame et son id
        $game = $gameApi->getGame($id);
        // et renvoie le tout au template game avec comme variable game qui aura en elle les données
        return $this->render('front/game.html.twig',['game'=>$game]);
    }

    // Création de l'url et du nom que l'on pourra rappeler dans nos templates
    /**
     * @Route("/game-dlc/{id}",name="game-dlc")
     */
    // Création de la methode jeuDlc, on injecte le service qui fait appel à l'api ainsi que $id
    // qui nous permettra de cibler un dlc de jeu grâce à son id
    public function gameDlc($id,GameApi $gameApi){
        // comme la réponse de l'api est un tableau on récupère le dlc avec getGameDlc et son id
        $game = $gameApi->getGameDlc($id);
        // et renvoie le tout au template game-dlc avec comme variable game qui aura en elle les données
        return $this->render('front/game-dlc.html.twig',['game'=>$game['results'][0]]);
    }
}