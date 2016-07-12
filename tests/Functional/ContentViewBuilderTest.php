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

use Symfony\Cmf\Component\ContentType\Tests\Functional\Model\Article;

class ContentViewBuilderTest extends BaseTestCase
{
    public function testFormBuilder()
    {
        $builder = $this->getContainer([
            'mapping' => [
                Article::class => [
                    'properties' => [
                        'title' => [
                            'type' => 'text',
                        ],
                    ],
                ],
            ],
        ])->get('cmf_content_type.view_builder');

        $article = new Article();
        $article->title = 'Hello';
        $view = $builder->build($article);

        $this->assertEquals('Hello', $view['title']);
    }
}
