<?php

namespace Psi\Bridge\ContentType\Twig\Tests\Functional;

use Psi\Bridge\ContentType\Twig\ContentTypeExtension;
use Psi\Bridge\ContentType\Twig\TwigRenderer;
use Psi\Component\ContentType\View\View;

class TwigRendererTest extends \PHPUnit_Framework_TestCase
{
    private $renderer;

    public function setUp()
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem(__DIR__ . '/templates'), [
            'debug' => true,
            'strict_variables' => true,
        ]);
        $this->renderer = new TwigRenderer($twig);
        $twig->addExtension(new ContentTypeExtension($this->renderer));
    }

    /**
     * It should renderer a view.
     */
    public function testRender()
    {
        $view = new View('psi/test');
        $view['foobar'] = 'hello';
        $view->setValue('boo');
        $output = $this->renderer->render($view);
        $this->assertEquals(<<<'EOT'
hello
boo

EOT
, $output);
    }

    /**
     * It should be able to recursively render a view.
     */
    public function testRenderRecursive()
    {
        $view = new View('psi/recurse');
        $view['hello'] = 'goodbye';
        $view['foobar'] = new View('psi/test');
        $view['foobar']['foobar'] = 'hello';
        $view['foobar']->setValue('boo');
        $output = $this->renderer->render($view);
        $this->assertEquals(<<<'EOT'
goodbye
hello
boo


EOT
, $output);
    }
}
