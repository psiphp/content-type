<?php

namespace Psi\Component\ContentType\Metadata;

use Metadata\PropertyMetadata as BasePropertyMetadata;

class PropertyMetadata extends BasePropertyMetadata
{
    private $type;
    private $options = [];

    public function __construct(
        $class,
        $name,
        $type,
        array $options
    ) {
        parent::__construct($class, $name);

        $this->type = $type;
        $this->options = $options;
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
}
