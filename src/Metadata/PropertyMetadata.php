<?php

namespace Psi\Component\ContentType\Metadata;

use Metadata\PropertyMetadata as BasePropertyMetadata;

class PropertyMetadata extends BasePropertyMetadata
{
    private $type;
    private $options = [];
    private $role;

    public function __construct(
        $class,
        $name,
        $type,
        $role,
        array $options
    ) {
        parent::__construct($class, $name);

        $this->type = $type;
        $this->options = $options;
        $this->role = $role;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getClass()
    {
        return $this->class;
    }
}
