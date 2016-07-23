<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Component\ContentType\Tests\Functional\Example\Model;

class Image
{
    public $id;
    public $path;
    public $height;
    public $width;
    public $mimetype;

    public function __construct($path = null, $width = null, $height = null, $mimetype = null)
    {
        $this->path = $path;
        $this->width = $width;
        $this->height = $height;
        $this->mimetype = $mimetype;
    }
}
