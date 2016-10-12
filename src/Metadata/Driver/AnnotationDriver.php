<?php

namespace Psi\Component\ContentType\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\DriverInterface;
use Psi\Component\ContentType\Metadata\Annotations;
use Psi\Component\ContentType\Metadata\ClassMetadata;
use Psi\Component\ContentType\Metadata\PropertyMetadata;

class AnnotationDriver implements DriverInterface
{
    /**
     * @var Reader
     */
    private $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $metadata = new ClassMetadata($class->getName());
        $propertyMetadata = [];

        foreach ($class->getProperties() as $reflProperty) {
            $annotations = $this->reader->getPropertyAnnotations($reflProperty);

            foreach ($annotations as $annotation) {
                if ($annotation instanceof Annotations\Field) {
                    $propertyMetadata = new PropertyMetadata(
                        $class->getName(),
                        $reflProperty->getName(),
                        $annotation->type,
                        $annotation->role,
                        $annotation->group,
                        $annotation->options
                    );
                    $propertyMetadatas[] = $propertyMetadata;
                }
            }
        }

        if (empty($propertyMetadatas)) {
            return;
        }

        foreach ($propertyMetadatas as $propertyMetadata) {
            $metadata->addPropertyMetadata($propertyMetadata);
        }

        return $metadata;
    }
}
