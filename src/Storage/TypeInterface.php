<?php

namespace Psi\Component\ContentType\Storage;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface for a configurable storage type.
 */
interface TypeInterface
{
    public function configureOptions(OptionsResolver $resolver);
}
