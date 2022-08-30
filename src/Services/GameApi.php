<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GameApi
{
    private $httpClient;
    //je crée un client avec un constructeur pour pouvoir le rappeler plus facilement par la suite
    // on injecte httpclientinterface pour pouvoir avoir accès au client
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getGamesHome()
    {
        // $response stocke la réponse de notre appel à l'api
        $response = $this->httpClient->request(
            'GET',
            // ici l'affichage de 15 jeux
            'https://api.rawg.io/api/games?page_size=15&key=7146302c5fd744509641167c9814fc5e&dates=2019-09-01,2019-09-30&platforms=18,1,7'
        );
        // et on renvoie la réponse sous le format d'un tableau php
        return $response->toArray();
    }

    public function getGame($id){
        // $response stocke la réponse de notre appel à l'api
        $response = $this->httpClient->request(
            'GET',
            // ici l'obtention de l'id d'un jeu pour pouvoir aller sur la page du jeu en question
            'https://api.rawg.io/api/games/'.$id.'?key=7146302c5fd744509641167c9814fc5e&dates=2019-09-01,2019-09-30&platforms=18,1,7'
        );
        // et on renvoie la réponse sous le format d'un tableau php
        return $response->toArray();
    }

    public function getGameDlc($id){
        // $response stocke la réponse de notre appel à l'api
        $response = $this->httpClient->request(
            'GET',
            // ici l'obtention de l'id d'un dlc d'un jeu pour ensuite pouvoir aller la page de ce dlc
            'https://api.rawg.io/api/games/'.$id.'/additions?key=7146302c5fd744509641167c9814fc5e&dates=2019-09-01,2019-09-30&platforms=18,1,7'
        );
        // et on renvoie la réponse sous le format d'un tableau php
        return $response->toArray();
    }

    public function getGames()
    {
        // $response stocke la réponse de notre appel à l'api
        $response = $this->httpClient->request(
            'GET',
            // ici on récupère tous les jeux de l'api
            'https://api.rawg.io/api/games?page_size=40&key=7146302c5fd744509641167c9814fc5e&dates=2019-09-01,2019-09-30&platforms=18,1,7'
        );
        // et on renvoie la réponse sous le format d'un tableau php
        return $response->toArray();
    }

    public function searchGames($search){
        // $response stocke la réponse de notre appel à l'api
        $response = $this->httpClient->request(
            'GET',
            // ici on permet de rechercher un jeu grâce à son nom
            'https://api.rawg.io/api/games?search='.$search.'&key=7146302c5fd744509641167c9814fc5e&dates=2019-09-01,2019-09-30&platforms=18,1,7'
        );
        // et on renvoie la réponse sous le format d'un tableau php
        return $response->toArray();
    }

}