<?php

namespace Psi\Component\ContentType\Tests\Functional;

use Psi\Component\ContentType\Tests\Functional\Example\Model\Article;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Image;

class ViewBuilderTest extends BaseTestCase
{
    public function testContentView()
    {
        $builder = $this->getContainer([
            'mapping' => [
                Article::class => [
                    'fields' => [
                        'title' => [
                            'type' => 'text',
                        ],
                        'image' => [
                            'type' => 'image',
                        ],
                    ],
                ],
            ],
        ])->get('psi_content_type.view_builder');

        $article = new Article();
        $article->title = 'Hello';
        $article->image = new Image('/path/to/image.jpg', 100, 100, 'image/jpeg');
        $view = $builder->build($article);

        $this->assertEquals('Hello', $view['title']);
        $this->assertEquals(100, $view['image']['width']);
        $this->assertEquals(100, $view['image']['height']);
        $this->assertEquals('/path/to/image.jpg', $view['image']['path']);
    }
}
