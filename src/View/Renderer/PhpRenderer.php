<?php

namespace Psi\Component\ContentType\View\Renderer;

use Psi\Component\ContentType\View\RendererInterface;
use Psi\Component\ContentType\View\TemplateNotFoundException;
use Psi\Component\ContentType\View\ViewInterface;

class PhpRenderer implements RendererInterface
{
    private $templatePath;

    public function __construct(string $templatePath)
    {
        $this->templatePath = $templatePath;
    }

    public function render(ViewInterface $view): string
    {
        $templateName = $view->getTemplate();
        $templatePath = sprintf('%s/%s.php', $this->templatePath, $templateName);

        if (!file_exists($templatePath)) {
            throw new TemplateNotFoundException(sprintf(
                'Template "%s" not found at "%s"',
                $templateName, $templatePath
            ));
        }

        $output = function () use ($view, $templatePath) {
            ob_start();
            $renderer = $this;
            require $templatePath;

            return ob_get_clean();
        };

        return $output();
    }
}
