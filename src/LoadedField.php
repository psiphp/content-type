<?php

declare(strict_types=1);

namespace Psi\Component\ContentType;

use Psi\Component\ContentType\OptionsResolver\FieldOptionsResolver;
use Psi\Component\ContentType\Storage\ConfiguredType;
use Psi\Component\ContentType\Storage\TypeFactory;

/**
 * Loaded field wraps the basic field service and lazily provides access to
 * its storage mapping and resolved options.
 */
class LoadedField
{
    private $resolvedOptions;
    private $resolvedFormOptions;
    private $resolvedViewOptions;
    private $resolvedStorageType;
    private $resolver;
    private $factory;
    private $options;
    private $field;

    public function __construct(TypeFactory $factory, FieldInterface $field, array $options)
    {
        $this->factory = $factory;
        $this->field = $field;
        $this->options = $options;
    }

    /**
     * Return the resolved options for the field's form type.
     */
    public function getFormOptions(): array
    {
        if ($this->resolvedFormOptions) {
            return $this->resolvedFormOptions;
        }

        $resolver = $this->getResolver();
        $this->resolvedFormOptions = $resolver->resolveFormOptions($this->options);

        return $this->resolvedFormOptions;
    }

    /**
     * Return all of the field's resolved options.
     */
    public function getOptions(): array
    {
        if ($this->resolvedOptions) {
            return $this->resolvedOptions;
        }
        $resolver = $this->getResolver();
        $this->resolvedOptions = $resolver->resolve($this->options);

        return $this->resolvedOptions;
    }

    /**
     * Return all the resolved options for the field's view.
     */
    public function getViewOptions(): array
    {
        if ($this->resolvedViewOptions) {
            return $this->resolvedViewOptions;
        }

        $resolver = $this->getResolver();
        $this->resolvedViewOptions = $resolver->resolveViewOptions($this->options);

        return $this->resolvedViewOptions;
    }

    public function getViewType(): string
    {
        return $this->field->getViewType();
    }

    /**
     * Return the configured storage type.
     */
    public function getStorageType(): ConfiguredType
    {
        if ($this->resolvedStorageType) {
            return $this->resolvedStorageType;
        }

        $this->resolvedStorageType = $this->field->getStorageType($this->factory);

        return $this->resolvedStorageType;
    }

    /**
     * Return the actual field class.
     */
    public function getInnerField(): FieldInterface
    {
        return $this->field;
    }

    private function getResolver(): FieldOptionsResolver
    {
        if ($this->resolver) {
            return $this->resolver;
        }

        $this->resolver = new FieldOptionsResolver();
        $this->field->configureOptions($this->resolver);

        return $this->resolver;
    }
}
