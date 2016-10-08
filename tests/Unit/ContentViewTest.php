<?php

namespace Psi\Component\ContentType\Tests\Unit;

use Psi\Component\ContentType\ContentView;

class ContentViewTest extends \PHPUnit_Framework_TestCase
{
    /**
     * It should set and get values.
     */
    public function testSetGet()
    {
        $view = new ContentView();
        $view['foobar'] = 'Hello';

        $this->assertEquals('Hello', $view['foobar']);
        $this->assertTrue(isset($view['foobar']));
    }

    /**
     * It should throw an exception if the requested view variable does not exist.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage View value "bazbar" has not been set, available values: "foobar", "barfoo"
     */
    public function testUnknownKey()
    {
        $view = new ContentView();
        $view['foobar'] = 'hello';
        $view['barfoo'] = 'goodbye';

        $view['bazbar'];
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage View value "bazbar" has not been set, available values: ""
     */
    public function testUnknownKeyEmpty()
    {
        $view = new ContentView();
        $view['bazbar'];
    }
}
