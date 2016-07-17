<?php

namespace Symfony\Cmf\Component\ContentType;

use Symfony\Cmf\Component\ContentType\CompoundMapping;
use Symfony\Cmf\Component\ContentType\MappingBuilderCompound;

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

    public function __construct(MappingRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function single($mappingName)
    {
        return $this->registry->get($mappingName);
    }

    public function compound($classFqn)
    {
        return new MappingBuilderCompound($this->registry, $classFqn);
    }
}
