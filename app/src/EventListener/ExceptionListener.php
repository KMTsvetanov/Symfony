<?php

namespace App\EventListener;

use App\Service\ServiceException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{

    public function onKernelException(ExceptionEvent $event): void
    {
        /** @var ServiceException $exception */
        $exception = $event->getThrowable();

//        dd($exception->getStatusCode());

        $response = new JsonResponse([
            'type' => 'ConstraintViolationList',
            'title' => 'An error occurred',
            'description' => 'This value should be positive',
            'violations' => [
                [
                    'propertyPath' => 'quantity',
                    'message' => 'This value should be positive',
                ],
            ],
        ]);

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }
}