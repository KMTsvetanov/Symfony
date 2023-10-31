<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoviesController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {

    }

    #[Route('/movies/{name}', name: 'app_movies', defaults: ['name' => null], methods: ['GET', 'HEAD'])]
    public function index($name): Response
    {
        // findAll() - SELECT * FROM movies
        // find(5) - SELECT * FROM movies WHERE id = 5
        // findBy([], ['id' => 'DESC']); - SELECT * FROM movies ORDER BY id DESC
        // findOneBy(['id' => 9, 'title' => 'The Dark Knight'], ['id' => 'DESC']); - SELECT * FROM movies WHERE id = 9 AND title = 'The Dark Knight' ORDER BY id DESC
        // count(['id' => 9]); - SELECT COUNT(*) FROM movies WHERE id = 9
        // getClassName(); - "App\Entity\Movie"
        // createQueryBuilder()->...; - SELECT e.* FROM movie e WHERE e.id >= 1 AND e.id <= 10 ORDER BY e.id DESC
        $repository = $this->entityManager->getRepository(Movie::class);

        $query = $repository->createQueryBuilder('e')
            ->where('e.id >= :minId')
            ->andWhere('e.id <= :maxId')
            ->setParameter('minId', 1)
            ->setParameter('maxId', 100)
            ->orderBy('e.id', 'DESC')
            ->getQuery();

        $movies = $query->getResult();

        $titles = [];

        foreach ($movies as $movie) {
            // Access the 'title' property of each Movie object
            $titles[] = $movie->getTitle();
        }

        return $this->render('movies/index.html.twig', [
            'controller_name' => 'MoviesController',
            'movie_name' => $name,
            'movies' => $titles,
        ]);
    }
}