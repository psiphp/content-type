<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Component\ContentType;

class ContentView implements \ArrayAccess, \IteratorAggregate, \Countable
{
    private $values;
    private $value;

    public function offsetGet($name)
    {
        if (!isset($this->values[$name])) {
            throw new \InvalidArgumentException(sprintf(
                'View value "%s" has not been set, available values: "%s"',
                $name, implode('", "', array_keys($this->values))
            ));
        }

        return $this->values[$name];
    }

    public function offsetExists($name)
    {
        return isset($this->values[$name]);
    }

    public function offsetSet($name, $value)
    {
        $this->values[$name] = $value;
    }

    public function offsetUnset($name)
    {
        unset($this->values[$name]);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->values);
    }

    public function count()
    {
        return count($this->values);
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function __toString()
    {
        if (isset($this->value)) {
            return $this->value;
        }

        return '<no primary value>';
    }
}
