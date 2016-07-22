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
use Symfony\Cmf\Component\ContentType\Mapping\CompoundMapping;
use Symfony\Cmf\Component\ContentType\Storage\Doctrine\PhpcrOdm\FieldMapper;
use Symfony\Cmf\Component\ContentType\MappingResolver;

class ContentTypeDriver implements MappingDriver
{
    private $registry;
    private $fieldMappings = [];
    private $initialized = false;
    private $mappingRegistry;
    private $mappingResolver;
    private $mapper;

    public function __construct(
        FieldRegistry $registry,
        MappingRegistry $mappingRegistry,
        MappingResolver $resolver
    )
    {
        $this->registry = $registry;
        $this->mappingRegistry = $mappingRegistry;
        $this->mapper = new FieldMapper();
        $this->mappingResolver = $resolver;
    }

    private function init()
    {
        if ($this->initialized) {
            return;
        }

        foreach ($this->registry->all() as $fieldName => $field) {

            try {
                $mapping = $this->mappingResolver->resolveMapping($field);
            } catch (\Exception $e) {
                throw new \RuntimeException(sprintf(
                    'Could not map field type "%s"',
                    $fieldName
                ), null, $e);
            }

            // scalar fields will be mapped by the metadata subscriber.
            if (!$mapping instanceof CompoundMapping) {
                continue;
            }

            $this->fieldMappings[$mapping->getClass()] = $mapping;
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

        if (!isset($this->fieldMappings[$className])) {
            return;
        }

        $mapping = $this->fieldMappings[$metadata->getName()];

        // assume there is an ID field.
        // TODO: we should implement an interface if we want this to be a
        //       prerequisite.
        $metadata->mapId([
            'fieldName' => 'id',
            'id' => true,
        ]);

        foreach ($mapping as $fieldName => $fieldMapping) {
            $this->mapper->__invoke($fieldName, $fieldMapping, $metadata);
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
