<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Component\ContentType\Metadata;

use Metadata\MergeableClassMetadata;

class ClassMetadata extends MergeableClassMetadata
{
    public function __construct(
        $name
    ) {
        parent::__construct($name);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPropertyMetadata()
    {
        return $this->propertyMetadata;
    }
}
