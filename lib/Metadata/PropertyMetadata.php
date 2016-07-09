<?php

namespace Symfony\Cmf\Component\ContentType\Metadata;

use Metadata\PropertyMetadata as BasePropertyMetadata;

class PropertyMetadata extends BasePropertyMetadata
{
    private $type;
    private $viewOptions = [];
    private $formOptions = [];

    public function __construct(
        $class,
        $name,
        $type,
        array $viewOptions,
        array $formOptions
    )
    {
        parent::__construct($class, $name);

        $this->type = $type;
        $this->viewOptions = $viewOptions;
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

    public function getViewOptions() 
    {
        return $this->viewOptions;
    }

    public function getFormOptions() 
    {
        return $this->formOptions;
    }
}
