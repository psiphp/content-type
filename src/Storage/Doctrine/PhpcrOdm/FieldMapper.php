<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\Storage\Doctrine\PhpcrOdm;

use Doctrine\ODM\PHPCR\Mapping\ClassMetadata;
use Psi\Component\ContentType\FieldLoader;
use Psi\Component\ContentType\LoadedField;
use Psi\Component\ContentType\Storage\Mapping\Type\CollectionType;
use Psi\Component\ContentType\Storage\Mapping\Type\DateTimeType;
use Psi\Component\ContentType\Storage\Mapping\Type\IntegerType;
use Psi\Component\ContentType\Storage\Mapping\Type\ObjectType;
use Psi\Component\ContentType\Storage\Mapping\Type\ReferenceType;
use Psi\Component\ContentType\Storage\Mapping\Type\StringType;

/**
 * The FieldMapper maps the correct PHPCR-ODM field for the given content-type
 * field.
 */
class FieldMapper
{
    private $encoder;
    private $fieldLoader;

    public function __construct(
        PropertyEncoder $encoder,
        FieldLoader $fieldLoader
    ) {
        $this->encoder = $encoder;
        $this->fieldLoader = $fieldLoader;
    }

    public function __invoke($fieldName, LoadedField $loadedField, ClassMetadata $metadata, array $extraOptions = [])
    {
        $configuredType = $loadedField->getStorageType();
        $type = $configuredType->getInnerType();
        $options = array_merge([
            'multivalue' => false,
        ], $extraOptions);

        if ($type instanceof ObjectType) {
            $options = $loadedField->getStorageType()->getOptions();
            $this->unrestrictChildClass($options['class'], $metadata);
            $metadata->mapChild([
                'fieldName' => $fieldName,
                'nodeName' => $this->encoder->encode($fieldName),
                'nullable' => true,
            ]);

            return;
        }

        if ($type instanceof CollectionType) {
            $this->mapCollectionType($fieldName, $loadedField, $metadata);

            return;
        }

        if ($type instanceof StringType) {
            $metadata->mapField([
                'fieldName' => $fieldName,
                'type' => 'string',
                'nullable' => true,
                'multivalue' => $options['multivalue'],
            ]);

            return;
        }

        if ($type instanceof IntegerType) {
            $metadata->mapField([
                'fieldName' => $fieldName,
                'type' => 'long',
                'nullable' => true,
                'multivalue' => $options['multivalue'],
            ]);

            return;
        }

        if ($type instanceof DateTimeType) {
            $metadata->mapField([
                'fieldName' => $fieldName,
                'type' => 'date',
                'nullable' => true,
                'multivalue' => $options['multivalue'],
            ]);

            return;
        }

        if ($type instanceof ReferenceType) {
            $metadata->mapManyToOne([
                'fieldName' => $fieldName,
                'strategy' => 'hard',

                'nullable' => true,
                'cascade' => ClassMetadata::CASCADE_ALL,
                'multivalue' => $options['multivalue'],
            ]);

            return;
        }

        throw new \RuntimeException(sprintf(
            'Do not know how to map field of type "%s"',
            get_class($type)
        ));
    }

    private function mapCollectionType($fieldName, LoadedField $loadedField, ClassMetadata $metadata)
    {
        $options = $loadedField->getOptions();
        $collectionField = $this->fieldLoader->loadByTypeAndOptions($options['field'], $options['field_options']);
        $storageType = $collectionField->getStorageType();

        if ($storageType->getInnerType() instanceof ObjectType) {
            $options = $storageType->getOptions();
            $this->unrestrictChildClass($options['class'], $metadata);

            $metadata->mapChildren([
                'fieldName' => $fieldName,
                'fetchDepth' => 1,
                'filter' => $this->encoder->encode($fieldName) . '-*',
                'cascade' => ClassMetadata::CASCADE_ALL,
                'nullable' => true,
            ]);

            return;
        }

        $this->__invoke($fieldName, $collectionField, $metadata, [
            'multivalue' => false,
        ]);
    }

    private function unrestrictChildClass(string $class, ClassMetadata $metadata)
    {
        if (!$class) {
            return;
        }

        if (!$childClasses = $metadata->getChildClasses()) {
            return;
        }

        $childClasses[] = $class;
        $metadata->setChildClasses($childClasses);
    }
}
