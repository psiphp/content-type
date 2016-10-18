<?php

namespace Psi\Bridge\ContentType\Twig\Tests\Functional;

use Psi\Bridge\ContentType\Twig\ContentTypeExtension;
use Psi\Bridge\ContentType\Twig\TwigRenderer;
use Psi\Component\ContentType\Standard\View\ScalarView;
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
        $view = new ScalarView('psi/test', 'foobar');
        $output = $this->renderer->render($view);
        $this->assertEquals(<<<'EOT'
foobar

EOT
, $output);
    }
}
