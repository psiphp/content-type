<?php

namespace Psi\Bridge\ContentType\Twig;

use Psi\Component\ContentType\View\View;

class ContentTypeExtension extends \Twig_Extension
{
    private $renderer;

    public function __construct(TwigRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('psi_content_render', [$this, 'renderContent'], ['is_safe' => ['html']]),
        ];
    }

    public function renderContent(View $view)
    {
        return $this->renderer->render($view);
    }
}
