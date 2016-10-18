<?php

declare(strict_types=1);

namespace Psi\Bridge\ContentType\Twig;

use Psi\Component\ContentType\View\RendererInterface;
use Psi\Component\ContentType\View\ViewInterface;

class TwigRenderer implements RendererInterface
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function render(ViewInterface $view): string
    {
        $templateName = $view->getTemplate();
        $names = [
            $templateName,
            sprintf('%s.html.twig', $templateName),
            sprintf('%s.twig', $templateName),
        ];

        foreach ($names as $name) {
            try {
                $template = $this->twig->loadTemplate($name);
                break;
            } catch (\Twig_Error_Loader $e) {
            }
        }

        if (!$template) {
            throw new \InvalidArgumentException(sprintf(
                'Could not load template "%s", tried: "%s"',
                $templateName, implode('", "', $names)
            ));
        }

        return $template->render([
            'view' => $view,
        ]);
    }
}
