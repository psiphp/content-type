<?php

namespace Psi\Component\ContentType\Standard\Storage;

use Psi\Component\ContentType\Storage\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Value should be persisted as an "object". Note that it is (your)
 * responsibility to map the object with the relevant persistance system.
 */
class ObjectType implements TypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('class', null);
    }
}
