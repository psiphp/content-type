<?php

namespace Psi\Component\ContentType\Storage\Doctrine\PhpcrOdm;

use Doctrine\ODM\PHPCR\Mapping\ClassMetadata;
use Psi\Component\ContentType\Mapping\CollectionMapping;
use Psi\Component\ContentType\Mapping\CompoundMapping;
use Psi\Component\ContentType\Mapping\IntegerMapping;
use Psi\Component\ContentType\Mapping\StringMapping;
use Psi\Component\ContentType\MappingInterface;

/**
 * The FieldMapper maps the metadata for PHPCR-ODM fields for
 * a given $fieldName and content-type Mapping object.
 */
class FieldMapper
{
    /**
     * @var PropertyEncoder
     */
    private $encoder;

    public function __construct(PropertyEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param string $fieldName
     * @param MappingInterface $fieldMapping
     * @param ClassMetadata $metadata
     */
    public function __invoke($fieldName, MappingInterface $fieldMapping, ClassMetadata $metadata)
    {
        if ($fieldMapping instanceof CompoundMapping) {
            $metadata->mapChild([
                'fieldName' => $fieldName,
                'nodeName' => $this->encoder->encode($fieldName),
            ]);

            return;
        }

        if ($fieldMapping instanceof CollectionMapping) {
            $metadata->mapChildren([
                'fieldName' => $fieldName,
                'fetchDepth' => 1,
                'filter' => $this->encoder->encode($fieldName) . '-*',
                'cascade' => ClassMetadata::CASCADE_ALL,
            ]);

            return;
        }

        if ($fieldMapping instanceof StringMapping) {
            $metadata->mapField([
                'fieldName' => $fieldName,
                'type' => 'string',
            ]);

            return;
        }

        if ($fieldMapping instanceof IntegerMapping) {
            $metadata->mapField([
                'fieldName' => $fieldName,
                'type' => 'long',
            ]);

            return;
        }

        throw new \RuntimeException(sprintf(
            'Do not know how to map field of type "%s"',
            get_class($fieldMapping)
        ));
    }
}
