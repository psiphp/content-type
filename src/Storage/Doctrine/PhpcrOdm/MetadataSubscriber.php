<?php

namespace Symfony\Cmf\Component\ContentType\Storage\Doctrine\PhpcrOdm;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\PHPCR\Event;

class MetadataSubscriber implements EventSubscriber
{
    private $metadataFactory;

    public function __construct(
        MetadataFactory $metadataFactory
    )
    {
        $this->metadataFactory = $metadataFactory;
    }

    public function getSubscribedEvents()
    {
        return [
            Event::loadClassMetadata
        ];
    }

    private function initUserModels()
    {
        foreach ($this->metadataFactory->getAllClassNames() as $className) {
            $this->userMappings[$className] = $this->metadataFactory->getMetadataForClass($className);
        }
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        $metadata = $args->getClassMetadata();

        if (null === $metadata = $this->metadataFactory->getMetadataForClass($metadata->getName())) {
            return;
        }


    }
}
