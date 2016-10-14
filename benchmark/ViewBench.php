<?php

namespace Psi\Component\ContentType\Benchmark;

use Psi\Component\ContentType\Tests\Functional\BaseTestCase;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Article;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Image;

/**
 * @BeforeMethods({"setUp"})
 * @Revs(500)
 * @Iterations(10)
 * @OutputTimeUnit("milliseconds", precision=2)
 */
class ViewBench extends BaseTestCase
{
    private $viewBuilder;

    public function setUp()
    {
        $this->viewBuilder = $this->getContainer([
            'mapping' => [
                Article::class => [
                    'alias' => 'article',
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
        ])->get('psi_content_type.view_builder');
    }

    /**
     * @Subject()
     */
    public function create_view_build()
    {
        $article = new Article();
        $article->title = 'Hello';
        $article->image = new Image('/path/to/image.jpg', 100, 100, 'image/jpeg');
        $this->viewBuilder->build($article);
    }
}
