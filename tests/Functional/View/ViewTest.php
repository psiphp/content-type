<?php

namespace Psi\Component\ContentType\Tests\Functional\Form\Extension;

use Psi\Component\ContentType\Standard\View\ObjectType;
use Psi\Component\ContentType\Tests\Functional\BaseTestCase;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Article;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Image;

class ViewTest extends BaseTestCase
{
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
                        'image' => [
                            'type' => 'image',
                        ],
                        'slideshow' => [
                            'type' => 'collection',
                            'options' => [
                                'field_type' => 'image',
                                'field_options' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $this->renderer = $container->get('psi_content_type.view.renderer.php');
        $this->viewFactory = $container->get('psi_content_type.view.factory');
    }

    /**
     * It should render an object.
     */
    public function testRender()
    {
        $image1 = new Image();
        $image1->path = '/one.jpg';
        $image2 = new Image();
        $image2->path = '/two.jpg';
        $article = new Article();
        $article->title = 'Hello World';
        $article->image = new Image();
        $article->image->height = 100;
        $article->image->width = 100;
        $article->image->mimetype = 'image/jpeg';
        $article->image->path = '/path/to';
        $article->slideshow = [
            $image1, $image2,
        ];

        $view = $this->viewFactory->create(ObjectType::class, $article, []);
        $output = $this->renderer->render($view);
        $this->assertEquals(<<<'EOT'
    <div>
        Hello World    </div>
    <div>
        <img src="/path/to" />
    </div>
    <div>
        <ul>
    <li>
                    <img src="/one.jpg" />
                    <img src="/two.jpg" />
            </li>
</ul>
    </div>

EOT
        , $output);
    }
}
