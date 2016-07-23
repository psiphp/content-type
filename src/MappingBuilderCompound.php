<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Component\ContentType;

use Symfony\Cmf\Component\ContentType\Mapping\CompoundMapping;

/**
 * Builder for compound mappings.
 */
class MappingBuilderCompound
{
    private $registry;
    private $mappings;
    private $classFqn;

    public function __construct(MappingRegistry $registry, $classFqn)
    {
        $this->classFqn = $classFqn;
        $this->registry = $registry;
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
        return new CompoundMapping($this->classFqn, $this->mappings);
    }
}
