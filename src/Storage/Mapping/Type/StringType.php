<?php

namespace Psi\Component\ContentType\Storage\Mapping\Type;

use Psi\Component\ContentType\Storage\Mapping\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Store the value as a string.
 */
class StringType implements TypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
