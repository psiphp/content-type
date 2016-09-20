<?php

namespace Psi\Component\ContentType\Tests\Functional\Example\Storage\Doctrine\PhpcrOdm;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document()
 */
class Article
{
    /**
     * @PHPCR\Id()
     */
    public $id;

    // mapped via. the content-type metadata
    public $title;
    public $image;
    public $slideshow;
}
