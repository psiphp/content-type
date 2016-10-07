<?php

namespace Psi\Component\ContentType\Storage\Mapping\Type;

use Psi\Component\ContentType\Storage\Mapping\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Store the value as a reference to another object (value and return value
 * should be the referenced object).
 */
class ReferenceType implements TypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
