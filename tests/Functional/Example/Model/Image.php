<?php

namespace Psi\Component\ContentType\Tests\Functional\Example\Model;

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
