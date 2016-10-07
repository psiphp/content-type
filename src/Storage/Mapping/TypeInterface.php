<?php

namespace Psi\Component\ContentType\Storage\Mapping;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface for a configurable storage type.
 */
interface TypeInterface
{
    public function configureOptions(OptionsResolver $resolver);
}
