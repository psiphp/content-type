<?php

namespace Psi\Component\ContentType\View;

class View implements \ArrayAccess, \IteratorAggregate, \Countable
{
    private $vars = [];
    private $value;
    private $template;

    public function __construct(string $template = null)
    {
        $this->template = $template;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function offsetGet($name)
    {
        if (!isset($this->vars[$name])) {
            throw new \InvalidArgumentException(sprintf(
                'View value "%s" has not been set, available vars: "%s"',
                $name, implode('", "', array_keys($this->vars))
            ));
        }


        return $this->vars[$name];
    }

    public function offsetExists($name)
    {
        return isset($this->vars[$name]);
    }

    public function offsetSet($name, $value)
    {
        $this->vars[$name] = $value;
    }

    public function offsetUnset($name)
    {
        unset($this->vars[$name]);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->vars);
    }

    public function count()
    {
        return count($this->vars);
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getVars()
    {
        return $this->vars;
    }

    public function __toString()
    {
        if (isset($this->value)) {
            return $this->value;
        }

        return '<no primary value>';
    }
}
