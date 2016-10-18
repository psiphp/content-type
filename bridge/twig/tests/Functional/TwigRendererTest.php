<?php

namespace Psi\Bridge\ContentType\Twig\Tests\Functional;

use Psi\Component\ContentType\Standard\View\CollectionType;
use Psi\Component\ContentType\Standard\View\NullType;
use Psi\Component\ContentType\Standard\View\ObjectType;
use Psi\Component\ContentType\Standard\View\ScalarType;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Article;

class TwigRendererTest extends TestCase
{
    private $renderer;
    private $viewFactory;

    public function setUp()
    {
        $container = $this->getContainer([
            'mapping' => [
                Article::class => [
                    'alias' => 'article',
                    'fields' => [
                        'title' => [
                            'type' => 'text',
                        ],
                    ],
                ],
            ],
        ]);
        $this->renderer = $container->get('psi_content_type.view.twig.renderer');
        $this->viewFactory = $container->get('psi_content_type.view.factory');
    }

    /**
     * It should renderer a views.
     *
     * @dataProvider provideViews
     */
    public function testRender($viewType, $data, array $options, $expected)
    {
        $view = $this->viewFactory->create($viewType, $data, $options);
        $output = $this->renderer->render($view);
        $this->assertEquals($expected, $output);
    }

    public function provideViews()
    {
        $article = new Article();
        $article->title = 'Hello';

        return [
            [
                ScalarType::class,
                'hello',
                [],
                'hello',
            ],
            [
                ObjectType::class,
                $article,
                [],
                <<<'EOT'
    <div>
        Hello
    </div>

EOT
            ],
            [
                CollectionType::class,
                [
                    'hello',
                    'goodbye',
                ],
                [
                    'field_type' => 'text',
                    'field_options' => [],
                ],
                <<<'EOT'
<ul>
            <li>
            hello
        </li>
            <li>
            goodbye
        </li>
    </ul>

EOT
            ],
            [
                NullType::class,
                'nothing',
                [],
                '',
            ],
        ];
    }
}
