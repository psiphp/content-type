<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

class LanguageFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'language';
    }

    protected function getDefaultData()
    {
        return 'en';
    }
}
