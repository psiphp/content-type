<?php

namespace Symfony\Cmf\Component\ContentType\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class SerializeTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return serialize($value);
    }

    public function reverseTransform($value)
    {
        return unserialize($value);
    }
}
