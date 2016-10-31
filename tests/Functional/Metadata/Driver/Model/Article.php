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
     * @CMFCT\Field(type="image-collection", shared={ "max" = 10 }, form={ "foo": "bar" }, view={"tag": "h1"}, storage={"serialize": false})
     */
    public $slider;
}
