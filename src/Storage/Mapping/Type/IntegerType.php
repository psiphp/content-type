<?php

namespace Psi\Component\ContentType\Storage\Mapping\Type;

use Psi\Component\ContentType\Storage\Mapping\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Value should be persisted as an integer.
 */
class IntegerType implements TypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
