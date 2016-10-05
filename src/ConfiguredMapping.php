<?php

namespace Psi\Component\ContentType;

class ConfiguredMapping
{
    private $mapping;
    private $options = [];

    public function __construct(MappingInterface $mapping, array $options)
    {
        $this->mapping = $mapping;
        $this->options = $options;
    }

    public function getMapping() 
    {
        return $this->mapping;
    }

    public function getOption($name) 
    {
        if (!isset($this->options[$name])) {
            throw new \InvalidArgumentException(sprintf(
                'Unknown option "%s". Known options: "%s"',
                $name, implode('", "', array_keys($this->options))
            ));
        }

        return $this->options[$name];
    }
}
