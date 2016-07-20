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

class ContentTypeDriver implements MappingDriver
{
    private $registry;
    private $mappings;
    private $initialized = false;
    private $mappingRegistry;

    public function __construct(FieldRegistry $registry, MappingRegistry $mappingRegistry)
    {
        $this->registry = $registry;
        $this->mappingRegistry = $mappingRegistry;
    }

    private function init()
    {
        if ($this->initialized) {
            return;
        }

        foreach ($this->registry->all() as $fieldName => $field) {
            $mappingBuilder = new MappingBuilder($this->mappingRegistry);
            $mapping = $field->getMapping($mappingBuilder);


            // do not map scalar fields.
            if ($mapping instanceof MappingInterface) {
                continue;
            }

            if ($mapping instanceof MappingBuilderCompound) {
                $compound = $mapping->getCompound();
                $this->mappings[$compound->getClass()] = $compound;
                continue;
            }

            throw new \InvalidArgumentException(sprintf(
                'Invalid mapping for field "%s", must be a MappingInterface or a MappingBuilderCompound, got "%s"',
                $fieldName, is_object($field) ? get_class($field) : gettype($field)
            ));
        }

        $this->initialized = true;
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

        if (!isset($this->mappings[$className])) {
            return;
        }

        $mapping = $this->mappings[$className];

        // assume there is an ID field.
        // TODO: we should implement an interface if we want this to be a
        //       prerequisite.
        $metadata->mapId([
            'fieldName' => 'id',
            'id' => true,
        ]);

        foreach ($mapping as $fieldName => $fieldMapping) {
            if ($fieldMapping instanceof StringMapping) {
                $metadata->mapField([
                    'fieldName' => $fieldName,
                    'type' => 'string',
                ]);
                continue;
            }

            if ($fieldMapping instanceof IntegerMapping) {
                $metadata->mapField([
                    'fieldName' => $fieldName,
                    'type' => 'long',
                ]);
                continue;
            }

            throw new \RuntimeException(sprintf(
                'Do not know how to map field of type "%s"',
                get_class($fieldMapping)
            ));
        }
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
