<?php

namespace Psi\Bridge\ContentType\Doctrine\PhpcrOdm\Tests\Functional\Example;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(
 *     childClasses={
 *         "stdClass"
 *     }
 * )
 */
class ArticleWithRestrictedChildren
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
