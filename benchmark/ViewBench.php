<?php

namespace Psi\Component\ContentType\Benchmark;

use Psi\Component\ContentType\Standard\View\ObjectType;
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
    private $factory;

    public function setUp()
    {
        $container = $this->getContainer([
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
        ]);
        $this->factory = $container->get('psi_content_type.view.factory');
    }

    /**
     * @Subject()
     */
    public function create_object_view()
    {
        $article = new Article();
        $article->title = 'Hello';
        $article->image = new Image('/path/to/image.jpg', 100, 100, 'image/jpeg');
        $this->factory->create(ObjectType::class, $article, []);
    }

    /**
     * @Subject()
     */
    public function create_object_view_and_iterate()
    {
        $article = new Article();
        $article->title = 'Hello';
        $article->image = new Image('/path/to/image.jpg', 100, 100, 'image/jpeg');
        $view = $this->factory->create(ObjectType::class, $article, []);
        iterator_to_array($view);
    }
}
