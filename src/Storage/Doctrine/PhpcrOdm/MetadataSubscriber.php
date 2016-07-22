<?php

namespace Symfony\Cmf\Component\ContentType\Storage\Doctrine\PhpcrOdm;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\PHPCR\Event;
use Symfony\Cmf\Component\ContentType\FieldRegistry;
use Metadata\MetadataFactory;
use Doctrine\Common\Persistence\Event\LoadClassMetadataEventArgs;
use Symfony\Cmf\Component\ContentType\MappingResolver;

class MetadataSubscriber implements EventSubscriber
{
    private $metadataFactory;
    private $fieldRegistry;
    private $mappingResolver;

    public function __construct(
        MetadataFactory $metadataFactory,
        FieldRegistry $fieldRegistry,
        MappingResolver $mappingResolver
    )
    {
        $this->metadataFactory = $metadataFactory;
        $this->fieldRegistry = $fieldRegistry;
        $this->mappingResolver = $mappingResolver;
    }

    public function getSubscribedEvents()
    {
        return [
            Event::loadClassMetadata
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        $metadata = $args->getClassMetadata();
        $fieldMapper = new FieldMapper();

        if (null === $metadata = $this->metadataFactory->getMetadataForClass($metadata->getName())) {
            return;
        }

        $odmMetadata = $args->getClassMetadata();

        foreach ($metadata->getPropertyMetadata() as $property) {
            $field = $this->fieldRegistry->get($property->getType());
            $mapping = $this->mappingResolver->resolveMapping($field);
            $fieldMapper($property->getName(), $mapping, $odmMetadata);
        }
    }
}
