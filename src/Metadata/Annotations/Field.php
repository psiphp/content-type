<?php

namespace Psi\Component\ContentType\Metadata\Annotations;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Field
{
    public $type;
    public $options = [];
    public $role;
}
