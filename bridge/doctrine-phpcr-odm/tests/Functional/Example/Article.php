<?php

namespace Psi\Bridge\ContentType\Doctrine\PhpcrOdm\Tests\Functional\Example;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
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
    public $date;
    public $referencedImage;
    public $numbers;
    public $paragraphs = [];
    public $objectReferences;
}
