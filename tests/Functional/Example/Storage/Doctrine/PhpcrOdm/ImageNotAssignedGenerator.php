<?php

namespace Psi\Component\ContentType\Tests\Functional\Example\Storage\Doctrine\PhpcrOdm;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
 */
class ImageNotAssignedGenerator
{
    /**
     * @PHPCR\Id(strategy="parent")
     */
    public $id;

    /**
     * @PHPCR\ParentDocument()
     */
    public $parent;

    /**
     * @PHPCR\Nodename()
     */
    public $name;
}
