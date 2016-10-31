<?php

namespace Psi\Component\ContentType\Metadata;

use Metadata\PropertyMetadata as BasePropertyMetadata;
use Psi\Component\ContentType\FieldOptions;

class PropertyMetadata extends BasePropertyMetadata
{
    private $type;
    private $options;
    private $role;
    private $group;

    public function __construct(
        $class,
        $name,
        $type,
        $role,
        $group,
        array $options
    ) {
        parent::__construct($class, $name);

        $this->type = $type;
        $this->role = $role;
        $this->group = $group;
        $this->options = FieldOptions::create($options);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getOptions(): FieldOptions
    {
        return $this->options;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getGroup()
    {
        return $this->group;
    }
}
