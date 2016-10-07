<?php

namespace Psi\Component\ContentType\Storage\Doctrine\PhpcrOdm\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LoadClassMetadataEventArgs;
use Doctrine\ODM\PHPCR\Event;
use Metadata\MetadataFactory;
use Psi\Component\ContentType\FieldLoader;
use Psi\Component\ContentType\Storage\Doctrine\PhpcrOdm\FieldMapper;

class MetadataSubscriber implements EventSubscriber
{
    private $metadataFactory;
    private $fieldLoader;
    private $mapper;

    public function __construct(
        MetadataFactory $metadataFactory,
        FieldLoader $fieldLoader,
        FieldMapper $mapper
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->fieldLoader = $fieldLoader;
        $this->mapper = $mapper;
    }

    public function getSubscribedEvents()
    {
        return [
            Event::loadClassMetadata,
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        $metadata = $args->getClassMetadata();

        if (null === $metadata = $this->metadataFactory->getMetadataForClass($metadata->getName())) {
            return;
        }

        $odmMetadata = $args->getClassMetadata();

        foreach ($metadata->getPropertyMetadata() as $property) {
            $field = $this->fieldLoader->loadForProperty($property);
            $this->mapper->__invoke($property->getName(), $field, $odmMetadata);
        }
    }
}
