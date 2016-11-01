<?php

namespace Psi\Component\ContentType\Tests\Functional\Standard\Field;

class BirthdayFieldTest extends FieldTestCase
{
    protected function getFieldName(): string
    {
        return 'birthday';
    }

    protected function getDefaultData()
    {
        return new \DateTime('05/09/1980');
    }
}
