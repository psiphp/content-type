<?php

namespace Psi\Component\ContentType\Standard\Storage;

use Psi\Component\ContentType\Storage\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Value should be persisted as a datetime object.
 */
class DateTimeType implements TypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
