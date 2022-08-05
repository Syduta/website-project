<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GameApi
{

    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getGamesHome()
    {

        $response = $this->httpClient->request(
            'GET',
            'https://api.rawg.io/api/games?page_size=12&key=7146302c5fd744509641167c9814fc5e&dates=2019-09-01,2019-09-30&platforms=18,1,7'
        );

        return $response->toArray();
    }

    public function getGame($id){
        $response = $this->httpClient->request(
            'GET',
            'https://api.rawg.io/api/games/'.$id.'?key=7146302c5fd744509641167c9814fc5e&dates=2019-09-01,2019-09-30&platforms=18,1,7'
        );
        return $response->toArray();
    }

    public function getGameDlc($id){
        $response = $this->httpClient->request(
            'GET',
            'https://api.rawg.io/api/games/'.$id.'/additions?key=7146302c5fd744509641167c9814fc5e&dates=2019-09-01,2019-09-30&platforms=18,1,7'
        );
        return $response->toArray();
    }

    public function getGames()
    {

        $response = $this->httpClient->request(
            'GET',
            'https://api.rawg.io/api/games?page_size=40&key=7146302c5fd744509641167c9814fc5e&dates=2019-09-01,2019-09-30&platforms=18,1,7'
        );

        return $response->toArray();
    }

    public function searchGames($search){
        $response = $this->httpClient->request(
            'GET',
            'https://api.rawg.io/api/games?search='.$search.'&key=7146302c5fd744509641167c9814fc5e&dates=2019-09-01,2019-09-30&platforms=18,1,7'
        );

        return $response->toArray();
    }

}