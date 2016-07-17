<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Component\ContentType\Tests\Unit;

use Symfony\Cmf\Component\ContentType\ContentView;

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
}