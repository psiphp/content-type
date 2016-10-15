<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\View;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ViewFactory
{
    private $registry;

    public function __construct(
        TypeRegistry $registry

    ) {
        $this->registry = $registry;
    }

    public function create(string $viewName, $data, array $options): ViewInterface
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault('template', null);
        $resolver->setAllowedTypes('template', ['string', 'null']);

        $type = $this->registry->get($viewName);
        $type->configureOptions($resolver);

        $options = $resolver->resolve($options);

        return $type->createView($this, $data, $options);
    }
}
