<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
