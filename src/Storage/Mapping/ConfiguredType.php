<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\Storage\Mapping;

/**
 * Contains resolved user configuration for the referenced storage type
 * service.
 */
class ConfiguredType
{
    private $innerType;
    private $options = [];

    public function __construct(TypeInterface $innerType, array $options)
    {
        $this->innerType = $innerType;
        $this->options = $options;
    }

    /**
     * Return the inner (actual) storage type.
     */
    public function getInnerType(): TypeInterface
    {
        return $this->innerType;
    }

    /**
     * Return all the options.
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Return the named option.
     */
    public function getOption($name)
    {
        if (!isset($this->options[$name])) {
            throw new \InvalidArgumentException(sprintf(
                'Unknown option "%s", known options: "%s"',
                $name, implode('", "', array_keys($this->options))
            ));
        }

        return $this->options[$name];
    }
}
