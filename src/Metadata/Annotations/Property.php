<?php

namespace Psi\Component\ContentType\Metadata\Annotations;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Property
{
    public $type;
    public $options = [];
    public $role;
}
