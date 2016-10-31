<?php

namespace Psi\Component\ContentType\Metadata\Annotations;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Field
{
    public $type;
    public $shared = [];
    public $form = [];
    public $view = [];
    public $storage = [];
    public $role;
    public $group;
}
