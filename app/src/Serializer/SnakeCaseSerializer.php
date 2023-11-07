<?php

namespace App\Serializer;

use App\Event\AfterDtoCreatedEvent;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SnakeCaseSerializer implements SerializerInterface
{
    private SerializerInterface $serializer;

    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
        $this->serializer = new Serializer(
            // normalizers
            [
                new ObjectNormalizer(
                    classMetadataFactory: new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader())),
                    nameConverter: new CamelCaseToSnakeCaseNameConverter()),
            ],
            // encoders
            [
                new JsonEncoder()
            ]
        );
    }

    public function serialize(mixed $data, string $format, array $context = []): string
    {
        return $this->serializer->serialize($data, $format, $context);
    }

    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        $dto = $this->serializer->deserialize($data, $type, $format, $context);

        $event = new AfterDtoCreatedEvent($dto);

        // Dispatch an after dto created event
        $this->eventDispatcher->dispatch($event, $event::NAME);

        return $dto;
    }
}