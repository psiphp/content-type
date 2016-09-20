<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psi\Component\ContentType\Metadata\Driver;

use Metadata\Driver\DriverInterface;
use Psi\Component\ContentType\Metadata\Annotations;
use Psi\Component\ContentType\Metadata\ClassMetadata;
use Psi\Component\ContentType\Metadata\PropertyMetadata;
use Doctrine\Common\Annotations\Reader;

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
                if ($annotation instanceof Annotations\Property) {
                    $propertyMetadata = new PropertyMetadata(
                        $class->getName(),
                        $reflProperty->getName(),
                        $annotation->type,
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
