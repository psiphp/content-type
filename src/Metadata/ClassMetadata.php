<?php

namespace Psi\Component\ContentType\Metadata;

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
