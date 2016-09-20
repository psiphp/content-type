<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psi\Component\ContentType;

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
