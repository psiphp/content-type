<?php

namespace Psi\Component\ContentType\Tests\Functional\Metadata\Driver\Model;

use Psi\Component\ContentType\Metadata\Annotations as CMFCT;

class Article
{
    /**
     * @CMFCT\Field(type="text", role="title", group="foobar")
     */
    public $title;

    /**
     * @CMFCT\Field(type="markdown")
     */
    public $body;

    /**
     * @CMFCT\Field(type="image-collection", options={ "max" = 10 })
     */
    public $slider;
}
