<?php

namespace Psi\Component\ContentType\Tests\Functional\Example\Model;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
 */
class Image
{
    /**
     * @PHPCR\Id()
     */
    public $id;

    /**
     * @PHPCR\Path()
     */
    public $path;

    /**
     * @PHPCR\Field(type="long")
     */
    public $height;

    /**
     * @PHPCR\Field(type="long")
     */
    public $width;

    /**
     * @PHPCR\Field(type="string")
     */
    public $mimetype;

    public function __construct($path = null, $width = null, $height = null, $mimetype = null)
    {
        $this->path = $path;
        $this->width = $width;
        $this->height = $height;
        $this->mimetype = $mimetype;
    }
}
