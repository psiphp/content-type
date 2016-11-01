<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

class UrlFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'url';
    }

    protected function getDefaultData()
    {
        return 'http://www.example.com';
    }
}
