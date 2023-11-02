<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieFormType;
use App\Message\ProcessTaskMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class MoviesController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {

    }

    // Example
    //#[Route('/movies/{name}', name: 'app_movies', defaults: ['name' => null], methods: ['GET', 'HEAD'])]
    // findAll() - SELECT * FROM movies
    // find(5) - SELECT * FROM movies WHERE id = 5
    // findBy([], ['id' => 'DESC']); - SELECT * FROM movies ORDER BY id DESC
    // findOneBy(['id' => 9, 'title' => 'The Dark Knight'], ['id' => 'DESC']); - SELECT * FROM movies WHERE id = 9 AND title = 'The Dark Knight' ORDER BY id DESC
    // count(['id' => 9]); - SELECT COUNT(*) FROM movies WHERE id = 9
    // getClassName(); - "App\Entity\Movie"
    // createQueryBuilder()->...; - SELECT e.* FROM movie e WHERE e.id >= 1 AND e.id <= 10 ORDER BY e.id DESC
    // $query = $movieRepository->createQueryBuilder('e')
    //     ->where('e.id >= :minId')
    //     ->andWhere('e.id <= :maxId')
    //     ->setParameter('minId', 1)
    //     ->setParameter('maxId', 100)
    //     ->orderBy('e.id', 'DESC')
    //     ->getQuery();
    // $movies = $query->getResult();

    #[Route('/movies', methods: ['GET'])]
    public function index(): Response
    {
        $movieRepository = $this->entityManager->getRepository(Movie::class);
        $movies = $movieRepository->findAll();

        return $this->render('movies/index.html.twig', [
            'controller_name' => 'MoviesController',
            'movies' => $movies,
        ]);
    }

    #[Route('/movies/create')]
    public function create(Request $request): Response
    {
        $movie = new Movie();

        $form = $this->createForm(MovieFormType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $imagePath = $form->get('imagePath')->getData();
            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();
                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/images',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $movie->setImagePath('/images/' . $newFileName);
            }

            $this->entityManager->persist($movie);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_movies_index');
        }

        return $this->render('movies/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/movies/{id}', requirements: ["id" => "\d+"], methods: ['GET'])]
    public function show($id, MessageBusInterface $bus): Response
    {
        $movieRepository = $this->entityManager->getRepository(Movie::class);
        $movie = $movieRepository->find($id);

        if ($movie) {
            $bus->dispatch(new ProcessTaskMessage($id));
        }

        return $this->render('movies/show.html.twig', [
            'movie' => $movie,
        ]);
    }



    #[Route('/movies/edit/{id}', requirements: ["id" => "\d+"])]
    public function edit($id, Request $request): Response
    {
        $movieRepository = $this->entityManager->getRepository(Movie::class);
        $movie = $movieRepository->find($id);

        $form = $this->createForm(MovieFormType::class, $movie, ['imagePathMapped' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $imagePath = $form->get('imagePath')->getData();
            if ($imagePath) {
                if ($movie->getImagePath() !== null) {
                    if (file_exists($this->getParameter('kernel.project_dir') . '/public' . $movie->getImagePath())) {
                        $newFileName = uniqid() . '.' . $imagePath->guessExtension();
                        try {
                            $imagePath->move(
                                $this->getParameter('kernel.project_dir') . '/public/images',
                                $newFileName
                            );

                            $oldFilePath = $this->getParameter('kernel.project_dir') . '/public' . $movie->getImagePath();
                            unlink($oldFilePath);

                        } catch (FileException $e) {
                            return new Response($e->getMessage());
                        }
                        $movie->setImagePath('/images/' . $newFileName);
                    }
                }
            }

            $this->entityManager->flush();

            return $this->redirectToRoute('app_movies_index');

        }
        return $this->render('movies/edit.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/movies/delete/{id}', requirements: ["id" => "\d+"], methods: ['GET', 'DELETE'])]
    public function delete($id): Response
    {
        $movieRepository = $this->entityManager->getRepository(Movie::class);
        $movie = $movieRepository->find($id);
        if (file_exists($this->getParameter('kernel.project_dir') . '/public' . $movie->getImagePath())) {
            $oldFilePath = $this->getParameter('kernel.project_dir') . '/public' . $movie->getImagePath();
            unlink($oldFilePath);
        }
        $this->entityManager->remove($movie);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_movies_index');
    }
}