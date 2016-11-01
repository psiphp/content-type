<?php

namespace Psi\Component\ContentType\Standard\Storage;

use Psi\Component\ContentType\Storage\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Value should be persisted as an integer.
 */
final class DoubleType implements TypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
