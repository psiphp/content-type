<?php

namespace Psi\Bridge\ContentType\Tests\Unit\Doctrine\PhpcrOdm;

use Psi\Bridge\ContentType\Doctrine\PhpcrOdm\PropertyEncoder;

class PropertyEncoderTest extends \PHPUnit_Framework_TestCase
{
    private $encoder;

    public function setUp()
    {
        $this->encoder = new PropertyEncoder('prefix', 'https://example.com');
    }

    /**
     * It should encode a field name.
     */
    public function testEncode()
    {
        $propertyName = $this->encoder->encode('hello');

        $this->assertEquals('prefix:hello', $propertyName);
    }

    /**
     * Encode field name with key.
     */
    public function testEncodeWithKey()
    {
        $propertyName = $this->encoder->encode('hello', 'goodbye');

        $this->assertEquals('prefix:hello-goodbye', $propertyName);
    }
}
