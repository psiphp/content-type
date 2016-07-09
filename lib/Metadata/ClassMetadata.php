<?php

namespace Symfony\Cmf\Component\ContentType\Metadata;

use Symfony\Cmf\Component\ContentType\Metadata\FieldMetadata;
use Metadata\ClassMetadata as BaseClassMetadata;

class ClassMetadata extends BaseClassMetadata
{
    private $driver;

    public function __construct(
        $name,
        $driver
    )
    {
        parent::__construct($name);
        $this->driver = $driver;
    }

    public function getName() 
    {
        return $this->name;
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function getPropertyMetadata() 
    {
        return $this->propertyMetadata;
    }
    
}
