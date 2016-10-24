<?php

declare(strict_types=1);

namespace Psi\Bridge\ContentType\Doctrine\PhpcrOdm;

/**
 * Responsible for encoding PHPCR property names which are managed by the
 * content-type component.
 */
class PropertyEncoder
{
    private $prefix;
    private $uri;

    public function __construct(string $prefix, string $uri)
    {
        $this->prefix = $prefix;
        $this->uri = $uri;
    }

    /**
     * Encode a field name. If the field reprents a compound type then $key
     * should be passed to represent the key for the value of the compound
     * field.
     */
    public function encode(string $fieldName, string $key = null): string
    {
        $propertyName = sprintf(
            '%s:%s',
            $this->prefix,
            $fieldName
        );

        if (null !== $key) {
            $propertyName .= '-' . $key;
        }


        return $propertyName;
    }

    /**
     * Return the PHPCR namespace prefix (alias).
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Return the namespace URI.
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}
