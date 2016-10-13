<?php

namespace Psi\Component\ContentType\Tests\Unit\View;

use Psi\Component\ContentType\View\View;

class ViewTest extends \PHPUnit_Framework_TestCase
{
    /**
     * It should set and get vars.
     */
    public function testSetGet()
    {
        $view = new View('psi/test');
        $view['foobar'] = 'Hello';

        $this->assertEquals('Hello', $view['foobar']);
        $this->assertTrue(isset($view['foobar']));
        $this->assertEquals('psi/test', $view->getTemplate());
    }

    /**
     * It should throw an exception if the requested view variable does not exist.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage View value "bazbar" has not been set, available vars: "foobar", "barfoo"
     */
    public function testUnknownKey()
    {
        $view = new View('psi/test');
        $view['foobar'] = 'hello';
        $view['barfoo'] = 'goodbye';

        $view['bazbar'];
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage View value "bazbar" has not been set, available vars: ""
     */
    public function testUnknownKeyEmpty()
    {
        $view = new View('psi/tViewest');
        $view['bazbar'];
    }
}
