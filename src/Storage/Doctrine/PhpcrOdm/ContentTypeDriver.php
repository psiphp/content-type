<?php

namespace Symfony\Cmf\Component\ContentType\Storage\Doctrine\PhpcrOdm;

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Symfony\Cmf\Component\ContentType\MappingBuilderCompound;
use Symfony\Cmf\Component\ContentType\MappingInterface;
use Symfony\Cmf\Component\ContentType\FieldRegistry;
use Symfony\Cmf\Component\ContentType\Mapping\StringMapping;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Symfony\Cmf\Component\ContentType\MappingRegistry;
use Symfony\Cmf\Component\ContentType\MappingBuilder;
use Symfony\Cmf\Component\ContentType\Mapping\IntegerMapping;
use Metadata\MetadataFactory;
use Symfony\Cmf\Component\ContentType\Mapping\CompoundMapping;

class ContentTypeDriver implements MappingDriver
{
    const CONTENTTYPE_PREFIX = 'cmfct';

    private $registry;
    private $userMappings = [];
    private $fieldMappings = [];
    private $initialized = false;
    private $mappingRegistry;
    private $metadataFactory;

    public function __construct(
        FieldRegistry $registry,
        MappingRegistry $mappingRegistry,
        MetadataFactory $metadataFactory
    )
    {
        $this->registry = $registry;
        $this->mappingRegistry = $mappingRegistry;
        $this->metadataFactory = $metadataFactory;
    }

    private function init()
    {
        if ($this->initialized) {
            return;
        }

        $this->initFieldModels();
        $this->initUserModels();

        $this->initialized = true;
    }

    /**
     * Initialize mappings for the field models.
     */
    private function initFieldModels()
    {
        foreach ($this->registry->all() as $fieldName => $field) {
            $mappingBuilder = new MappingBuilder($this->mappingRegistry);
            $mapping = $field->getMapping($mappingBuilder);


            // do not map scalar fields.
            if ($mapping instanceof MappingInterface) {
                continue;
            }

            if ($mapping instanceof MappingBuilderCompound) {
                $compound = $mapping->getCompound();
                $this->fieldMappings[$compound->getClass()] = $compound;
                continue;
            }

            throw new \InvalidArgumentException(sprintf(
                'Invalid mapping for field "%s", must be a MappingInterface or a MappingBuilderCompound, got "%s"',
                $fieldName, is_object($field) ? get_class($field) : gettype($field)
            ));
        }
    }

    private function initUserModels()
    {
        foreach ($this->metadataFactory->getAllClassNames() as $className) {
            $this->userMappings[$className] = $this->metadataFactory->getMetadataForClass($className);
        }
    }

    /**
     * Loads the metadata for the specified class into the provided container.
     *
     * @param string        $className
     * @param ClassMetadata $metadata
     *
     * @return void
     */
    public function loadMetadataForClass($className, ClassMetadata $metadata)
    {
        $this->init();

        if (isset($this->fieldMappings[$className])) {
            $this->mapFieldModel($metadata);
            return;
        }

        if (isset($this->userMappings[$className])) {
            $this->mapUserModel($metadata);
            return;
        }
    }

    /**
     * Map metadata for a content type model (e.g. Image).
     *
     * @param ClassMetadata $metadata
     */
    private function mapFieldModel(ClassMetadata $metadata)
    {
        $mapping = $this->fieldMappings[$metadata->getName()];

        // assume there is an ID field.
        // TODO: we should implement an interface if we want this to be a
        //       prerequisite.
        $metadata->mapId([
            'fieldName' => 'id',
            'id' => true,
        ]);

        foreach ($mapping as $fieldName => $fieldMapping) {
            $this->applyMapping($fieldName, $fieldMapping, $metadata);
        }
    }

    /**
     * Map metadata for a user defined model which has content-type mappings.
     *
     * @param ClassMetadata $metadata
     */
    private function mapUserModel(ClassMetadata $metadata)
    {
        $ctMetadata = $this->userMappings[$metadata->getName()];

        foreach ($ctMetadata->getProperties() as $propertyMetadata) {
            $field = $this->fieldRegistry->get($propertyMetadata->getType());
            $mapping = $this->mappingRegistry->get($field->getMapping());
            $this->applyMapping($propertyMetadata->getName(), $mapping, $metadata);
        }
    }

    private function applyMapping($fieldName, $fieldMapping, $metadata)
    {
        if ($fieldMapping instanceof CompoundMapping) {
            $metadata->mapChild([
                'fieldName' => $fieldName,
                'nodeName' => sprintf(
                    '%s:%s',
                    self::CONTENTTYPE_PREFIX,
                    $fieldName
                )
            ]);
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

    /**
     * Gets the names of all mapped classes known to this driver.
     *
     * @return array The names of all mapped classes known to this driver.
     */
    public function getAllClassNames()
    {
        $this->init();

        return array_keys($this->mappings);
    }

    /**
     * Returns whether the class with the specified name should have its metadata loaded.
     * This is only the case if it is either mapped as an Entity or a MappedSuperclass.
     *
     * @param string $className
     *
     * @return boolean
     */
    public function isTransient($className)
    {
        $this->init();

        return isset($this->mappings[$className]);
    }
}
