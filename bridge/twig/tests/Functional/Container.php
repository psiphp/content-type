<?php

namespace Psi\Bridge\ContentType\Twig\Tests\Functional;

use Psi\Bridge\ContentType\Twig\ContentTypeExtension;
use Psi\Bridge\ContentType\Twig\TwigRenderer;
use Psi\Component\ContentType\Tests\Functional\Container as BaseContainer;

class Container extends BaseContainer
{
    public function __construct(array $config)
    {
        parent::__construct(array_merge([
            'mapping' => [],
            'db_path' => __DIR__ . '/../../../../cache/test.sqlite',
        ], $config));

        $this->loadTwig();
    }

    private function loadTwig()
    {
        $this['psi_content_type.view.twig.renderer'] = function ($container) {
            return new TwigRenderer($container['twig']);
        };
        $this['twig'] = function () {
            $twig = new \Twig_Environment(new \Twig_Loader_Filesystem(__DIR__ . '/../../templates'), [
                'debug' => true,
                'strict_variables' => true,
            ]);

            return $twig;
        };

        $this['twig']->addExtension(new ContentTypeExtension($this['psi_content_type.view.twig.renderer']));
    }
}
