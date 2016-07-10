<?php

namespace Symfony\Cmf\Component\ContentType\Metadata;

use Metadata\PropertyMetadata as BasePropertyMetadata;

class PropertyMetadata extends BasePropertyMetadata
{
    private $type;
    private $options = [];
    private $formOptions = [];

    public function __construct(
        $class,
        $name,
        $type,
        array $options,
        array $formOptions
    ) {
        parent::__construct($class, $name);

        $this->type = $type;
        $this->options = $options;
        $this->formOptions = $formOptions;
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

    public function getFormOptions()
    {
        return $this->formOptions;
    }
}
