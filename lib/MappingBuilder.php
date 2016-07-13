<?php

namespace Symfony\Cmf\Component\ContentType;

use Symfony\Cmf\Component\ContentType\CompoundMapping;

/**
 * Builder for mappings.
 *
 * In the case where a field uses only a single property
 * the `single` method should be used.
 *
 * In the case of multiple properties, `map(...)` should be called
 * as many times as required before returning the result
 * of `getCompound`.
 */
class MappingBuilder
{
    private $registry;
    private $mappings;

    public function __construct(MappingRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function single($mappingName)
    {
        return $this->registry->get($mappingName);
    }

    public function map($propertyName, $mappingName)
    {
        $mapping = $this->registry->get($mappingName);

        if (isset($this->mappings[$propertyName])) {
            throw new \InvalidArgumentException(sprintf(
                'Property "%s" is already mapped',
                $propertyName
            ));
        }

        $this->mappings[$propertyName] = $mapping;

        return $this;
    }

    public function getCompound()
    {
        return new CompoundMapping($this->mappings);
    }
}
