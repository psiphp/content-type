<?php

namespace Psi\Component\ContentType\Storage\Mapping\Type;

use Psi\Component\ContentType\Storage\Mapping\TypeInterface;
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
