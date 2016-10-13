<?php

namespace Psi\Component\ContentType\View;

class ViewIterator
{
    private $builder;
    private $data;
    private $fieldType;
    private $options;

    public function __construct(
        ViewBuilder $builder,
        \Traversable $data,
        string $fieldType,
        array $options
    ) {
        $this->builder = $builder;
        $this->data = $data;
        $this->fieldType = $fieldType;
        $this->options = $options;
    }

    public function current()
    {
        return $this->createView(current($this->data));
    }

    public function key()
    {
        return key($this->data);
    }

    public function next()
    {
        return next($this->data);
    }

    public function rewind()
    {
        reset($this->data);
    }

    public function valid()
    {
        return current($this->data) ? true : false;
    }

    private function createView($data)
    {
        return $this->builder->createView($this->fieldType, $data, $this->options);
    }
}
