<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\Storage;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Factory for storage types.
 */
class TypeFactory
{
    private $registry;

    public function __construct(TypeRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Create a configured type.
     */
    public function create(string $type, array $options = []): ConfiguredType
    {
        return $this->createConfiguredType($this->registry->get($type), $options);
    }

    private function createConfiguredType(TypeInterface $type, array $options = []): ConfiguredType
    {
        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);
        $options = $resolver->resolve($options);

        return new ConfiguredType($type, $options);
    }
}
