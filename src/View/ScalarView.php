<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psi\Component\ContentType\View;

use Psi\Component\ContentType\ContentView;
use Psi\Component\ContentType\ContentViewBuilder;
use Psi\Component\ContentType\ViewInterface;

class ScalarView implements ViewInterface
{
    public function buildView(ContentViewBuilder $builder, ContentView $view, $data, array $options)
    {
        if (!is_scalar($data)) {
            throw new \InvalidArgumentException(sprintf(
                'Scalar view only accepts scalar values! Got "%s"',
                is_object($data) ? get_class($data) : gettype($data)
            ));
        }

        $view->setValue($data);
    }
}
