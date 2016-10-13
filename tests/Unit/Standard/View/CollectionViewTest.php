<?php

namespace Psi\Component\ContentType\Tests\Unit\Standard\View;

use Prophecy\Argument;
use Psi\Component\ContentType\Standard\View\CollectionView;
use Psi\Component\ContentType\View\View;
use Psi\Component\ContentType\View\ViewBuilder;
use Psi\Component\ContentType\View\ViewIterator;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionViewTest extends \PHPUnit_Framework_TestCase
{
    private $view;
    private $builder;

    public function setUp()
    {
        $this->builder = $this->prophesize(ViewBuilder::class);
        $this->view = new CollectionView($this->builder->reveal());
    }

    /**
     * It should accept arrays.
     */

    /**
     * It should create a view iterator.
     *
     * @dataProvider provideViewIterate
     */
    public function testViewIterate($data)
    {
        $view = new View('psi/foo');
        $subView1 = new View('foo/bar');
        $subView2 = new View('foo/bar');

        $this->view->configureOptions($resolver = new OptionsResolver());
        $options = $resolver->resolve([
            'field_type' => 'foo',
            'field_options' => ['foo' => 'bar'],
        ]);
        $this->view->buildView($view, $data, $options);

        $this->assertInstanceOf(ViewIterator::class, $view->getValue());

        $this->builder->createView('foo', Argument::type('object'), ['foo' => 'bar'])->will(function ($args) use ($data, $subView1, $subView2) {
            if ($data[0] === $args[1]) {
                return $subView1;
            }
            if ($data[1] === $args[1]) {
                return $subView2;
            }
        });

        $newView = $view->getValue()->current();
        $this->assertSame($subView1, $newView);

        $newView = $view->getValue()->next();
        $newView = $view->getValue()->current();
        $this->assertSame($subView2, $newView);
    }

    public function provideViewIterate()
    {
        return [
            [
                [
                    new \stdClass(),
                    new \stdClass(),
                    new \stdClass(),
                ],
            ],
            [
                new \ArrayObject([
                    new \stdClass(),
                    new \stdClass(),
                    new \stdClass(),
                ]),
            ],
        ];
    }

    /**
     * It should throw an exception if a non-traversale or non-array was passed as the value.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Data must be traversable or an array, got: "string"
     */
    public function testNonTraversable()
    {
        $view = new View('psi/foo');
        $data = 'hello';
        $this->view->buildView($view, $data, []);
    }
}
