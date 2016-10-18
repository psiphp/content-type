<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\View;

use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeFactory
{
    private $typeRegistry;

    public function __construct(
        TypeRegistry $typeRegistry
    ) {
        $this->typeRegistry = $typeRegistry;
    }

    public function create(string $typeName, $data, array $options): ViewInterface
    {
        $type = $this->typeRegistry->get($typeName);
        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);
        $options = $resolver->resolve($options);

        return $type->createView($this, $data, $options);
    }
}
