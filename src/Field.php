<?php

declare(strict_types=1);

namespace Psi\Component\ContentType;

use Psi\Component\ContentType\OptionsResolver\FieldOptionsResolver;

class Field
{
    private $resolved;
    private $resolver;
    private $field;
    private $options;

    public function __construct(
        FieldInterface $field,
        FieldOptions $options
    ) {
        $this->field = $field;
        $this->options = $options;
    }

    public function getOptions(): array
    {
        return $this->getResolver()->resolve($this->options->getSharedOptions());
    }

    public function getFormType(): string
    {
        return $this->field->getFormType();
    }

    public function getFormOptions(): array
    {
        return $this->resolve('resolveFormOptions');
    }

    public function getViewType(): string
    {
        return $this->field->getViewType();
    }

    public function getViewOptions(): array
    {
        return $this->resolve('resolveViewOptions');
    }

    public function getStorageOptions(): array
    {
        return $this->resolve('resolveStorageOptions');
    }

    public function getStorageType(): string
    {
        return $this->field->getStorageType();
    }

    public function getInnerField()
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

    private function resolve(string $methodName)
    {
        if (isset($this->resolved[$methodName])) {
            return $this->resolved[$methodName];
        }

        $resolver = $this->getResolver();

        $this->resolved[$methodName] = $resolver->$methodName($this->options);

        return $this->resolved[$methodName];
    }
}
