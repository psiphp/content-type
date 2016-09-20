<?php

namespace Psi\Component\ContentType\Tests\Functional\Metadata\Driver\Model;

use Psi\Component\ContentType\Metadata\Annotations as CMFCT;

class Article
{
    /**
     * @CMFCT\Property(type="text")
     */
    public $title;

    /**
     * @CMFCT\Property(type="markdown")
     */
    public $body;

    /**
     * @CMFCT\Property(type="image-collection", options={ "max" = 10 })
     */
    public $slider;
}
