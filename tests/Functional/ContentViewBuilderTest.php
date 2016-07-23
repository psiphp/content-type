<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Component\ContentType\Tests\Functional;

use Symfony\Cmf\Component\ContentType\Tests\Functional\Example\Model\Article;
use Symfony\Cmf\Component\ContentType\Tests\Functional\Example\Model\Image;

class ContentViewBuilderTest extends BaseTestCase
{
    public function testContentView()
    {
        $builder = $this->getContainer([
            'mapping' => [
                Article::class => [
                    'properties' => [
                        'title' => [
                            'type' => 'text',
                        ],
                        'image' => [
                            'type' => 'image',
                        ],
                    ],
                ],
            ],
        ])->get('cmf_content_type.view_builder');

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
