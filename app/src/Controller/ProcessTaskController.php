<?php

namespace App\Controller;

use App\Message\ProcessTaskMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProcessTaskController extends AbstractController
{
    #[Route('/process/task', name: 'app_process_task')]
    public function index(MessageBusInterface $bus): Response
    {
        $bus->dispatch(new ProcessTaskMessage(1));

        return new Response('Your order has been placed');
    }
}
