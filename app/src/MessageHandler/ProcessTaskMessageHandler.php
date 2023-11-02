<?php

namespace App\MessageHandler;

use App\Entity\Movie;
use App\Message\ProcessTaskMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ProcessTaskMessageHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(ProcessTaskMessage $message)
    {
        $movieRepository = $this->entityManager->getRepository(Movie::class);
        $movies = $movieRepository->find($message->getOrderId());

        // do something with your message
        echo 'Sending email to movie with Title:' . $movies->getTitle() . ' in the backend (async) now..';
    }
}
