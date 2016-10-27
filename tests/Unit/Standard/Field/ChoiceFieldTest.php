<?php

namespace Psi\Component\ContentType\Tests\Unit\Standard\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\Standard\Field\ChoiceField;

class ChoiceFieldTest extends FieldTestCase
{
    protected function getField(): FieldInterface
    {
        return new ChoiceField();
    }
}
