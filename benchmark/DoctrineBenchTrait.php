<?php

namespace Psi\Component\ContentType\Benchmark;

use Pimple\Container;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Article;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Image;

/**
 * @BeforeMethods({"setUp"})
 * @Revs(1)
 * @Iterations(10)
 * @OutputTimeUnit("milliseconds", precision=2)
 */
trait DoctrineBenchTrait
{
    protected $objectManager;

    public function getBenchContainer()
    {
        $container = $this->getContainer([
            'mapping' => [
                Article::class => [
                    'properties' => [
                        'title' => [
                            'type' => 'text',
                            'role' => 'title',
                        ],
                        'image' => [
                            'type' => 'image',
                            'role' => 'image',
                        ],
                        'slideshow' => [
                            'type' => 'collection',
                            'options' => [
                                'field_type' => 'image',
                            ],
                        ],
                        'date' => [
                            'type' => 'datetime',
                        ],
                        'referencedImage' => [
                            'type' => 'object_reference',
                        ],
                        'paragraphs' => [
                            'type' => 'collection',
                            'options' => [
                                'field_type' => 'text',
                                'field_options' => [],
                            ],
                        ],
                        'numbers' => [
                            'type' => 'collection',
                            'options' => [
                                'field_type' => 'integer',
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        return $container;
    }

    /**
     * @Subject()
     */
    public function image_phpcr_odm_only()
    {
        static $id;
        $image = new Image(
            '/path/to/image', 100, 200, 'image/jpeg'
        );
        $image->id = '/test/image' . $id++;

        $this->objectManager->persist($image);
        $this->objectManager->flush();
    }

    /**
     * @Subject()
     */
    public function ct_article()
    {
        static $id;
        $article = new Article();
        $article->id = '/test/article' . $id++;
        $article->title = 'Hello';
        $article->date = new \DateTime('2016-01-01 00:00:00');

        $this->objectManager->persist($article);
        $this->objectManager->flush();
    }

    /**
     * @Subject()
     */
    public function ct_article_with_image()
    {
        static $id;
        $article = new Article();
        $article->id = '/test/article' . $id++;
        $article->title = 'Hello';
        $article->date = new \DateTime('2016-01-01 00:00:00');

        $article->image = new Image(
            '/path/to/image', 100, 200, 'image/jpeg'
        );

        $this->objectManager->persist($article);
        $this->objectManager->flush();
    }

    abstract protected function getContainer(array $config = []): Container;
}
