<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoviesController extends AbstractController
{
    #[Route('/movies/{name}', name: 'app_movies', defaults: ['name' => null] ,methods: ['GET', 'HEAD'])]
    public function index($name): Response
    {
        return $this->render('movies/index.html.twig', [
            'controller_name' => 'MoviesController',
            'movie_name' => $name,
            'movies' => [
                'Movie 1',
                'Movie 2',
            ],
        ]);
    }
}
