<?php

namespace App\EventSubscriber;

use App\Event\AfterDtoCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DtoSubscriber implements EventSubscriberInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AfterDtoCreatedEvent::NAME => [
                ['validateDto', 100], // higher priority goes first
                ['doSomethingElse', 1],
            ]
        ];
    }

    public function validateDto(AfterDtoCreatedEvent $event): void
    {
        // Validate the dto
        $dto = $event->getDto();

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            throw new ValidationFailedException('Validation failed', $errors);
        }
    }

    public function doSomethingElse()
    {
//        dd('Do something else');
    }
}