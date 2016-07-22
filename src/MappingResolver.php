<?php

namespace Symfony\Cmf\Component\ContentType;

use Symfony\Cmf\Component\ContentType\MappingBuilder;
use Symfony\Cmf\Component\ContentType\MappingInterface;

class MappingResolver
{
    /**
     * @var MappingRegistry
     */
    private $registry;

    /**
     * @param MappingRegistry $registry
     */
    public function __construct(MappingRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @return MappingInterface
     */
    public function resolveMapping(FieldInterface $field)
    {
        $mappingBuilder = new MappingBuilder($this->registry);
        $mapping = $field->getMapping($mappingBuilder);

        if ($mapping instanceof MappingInterface) {
            return $mapping;
        }

        if ($mapping instanceof MappingBuilderCompound) {
            return $mapping->getCompound();
        }

        throw new \InvalidArgumentException(sprintf(
            'Mapping must be a MappingInterface or a MappingBuilderCompound, got "%s"',
            is_object($field) ? get_class($field) : gettype($field)
        ));
    }
}
