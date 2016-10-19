<?php

namespace Psi\Component\ContentType\Standard\Storage;

use Psi\Component\ContentType\Storage\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Value should be mapped as a collection (of scalars or sub-objects).
 */
final class CollectionType implements TypeInterface
{
    public function configureOptions(OptionsResolver $options)
    {
    }
}
