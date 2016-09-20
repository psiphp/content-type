<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psi\Component\ContentType\Tests\Functional\Example\View;

use Psi\Component\ContentType\ContentView;
use Psi\Component\ContentType\ContentViewBuilder;
use Psi\Component\ContentType\ViewInterface;

class ImageView implements ViewInterface
{
    public function buildView(ContentViewBuilder $builder, ContentView $view, $data, array $options)
    {
        $view['width'] = $data->width;
        $view['height'] = $data->height;
        $view['mimetype'] = $data->mimetype;
        $view['path'] = $data->path;
        $view->setValue($data);
    }
}
