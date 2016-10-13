<?php

namespace Psi\Bridge\ContentType\Doctrine\PhpcrOdm\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LoadClassMetadataEventArgs;
use Doctrine\ODM\PHPCR\Event;
use Metadata\MetadataFactory;
use Psi\Bridge\ContentType\Doctrine\PhpcrOdm\FieldMapper;
use Psi\Component\ContentType\FieldLoader;

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
            try {
                $field = $this->fieldLoader->load($property->getType(), $property->getOptions());
                $this->mapper->__invoke($property->getName(), $field, $odmMetadata);
            } catch (\Exception $e) {
                throw new \InvalidArgumentException(sprintf(
                    'Could not map field type "%s" on property "%s#%s"',
                    $property->getType(), $property->getClass(), $property->getName()
                ), null, $e);
            }
        }
    }
}
