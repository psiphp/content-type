<?php

namespace Psi\Component\ContentType\Storage\Mapping\Type;

use Psi\Component\ContentType\Storage\Mapping\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Value should be mapped as a collection (of scalars or sub-objects).
 */
class CollectionType implements TypeInterface
{
    public function configureOptions(OptionsResolver $options)
    {
    }
}
