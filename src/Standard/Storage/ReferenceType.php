<?php

namespace Psi\Component\ContentType\Standard\Storage;

use Psi\Component\ContentType\Storage\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Store the value as a reference to another object (value and return value
 * should be the referenced object).
 */
final class ReferenceType implements TypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults('class', null);
        $resolver->setAllowedTypes('class', ['null', 'array']);
    }
}
