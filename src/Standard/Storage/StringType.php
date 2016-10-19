<?php

namespace Psi\Component\ContentType\Standard\Storage;

use Psi\Component\ContentType\Storage\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Store the value as a string.
 */
final class StringType implements TypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
