<?php

namespace Psi\Bridge\ContentType\Twig\Tests\Unit;

use Psi\Bridge\ContentType\Twig\TwigRenderer;
use Psi\Component\ContentType\View\ViewInterface;

class TwigRendererTest extends \PHPUnit_Framework_TestCase
{
    private $renderer;
    private $twig;
    private $template;
    private $view;

    public function setUp()
    {
        $this->twig = $this->prophesize(\Twig_Environment::class);
        $this->renderer = new TwigRenderer($this->twig->reveal());

        $this->view = $this->prophesize(ViewInterface::class);
        $this->template = $this->prophesize(\Twig_Template::class);
    }

    /**
     * It should try each extension until it find one that works...
     */
    public function testExtensions()
    {
        $this->view->getTemplate()->willReturn('foobar');
        $this->twig->loadTemplate('foobar.html.twig')->willThrow(new \Twig_Error_Loader('foobar'));
        $this->twig->loadTemplate('foobar')->willThrow(new \Twig_Error_Loader('foobar'));
        $this->twig->loadTemplate('foobar.twig')->willReturn($this->template->reveal());
        $this->template->render([
            'view' => $this->view->reveal(),
        ])->willReturn('This is a test');

        $output = $this->renderer->render($this->view->reveal());
        $this->assertEquals('This is a test', $output);
    }

    /**
     * If no template was found it should throw an exception.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Could not load template "foobar"
     */
    public function testNoTemplate()
    {
        $this->view->getTemplate()->willReturn('foobar');
        $this->twig->loadTemplate('foobar.html.twig')->willThrow(new \Twig_Error_Loader('foobar'));
        $this->twig->loadTemplate('foobar')->willThrow(new \Twig_Error_Loader('foobar'));
        $this->twig->loadTemplate('foobar.twig')->willThrow(new \Twig_Error_Loader('foobar'));

        $this->renderer->render($this->view->reveal());
    }
}
