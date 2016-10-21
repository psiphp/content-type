<?php

namespace Psi\Component\ContentType\Benchmark;

use Psi\Bridge\ContentType\Doctrine\PhpcrOdm\Tests\Functional\PhpcrOdmTestCase;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Article;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Image;

/**
 * @BeforeMethods({"setUp"})
 * @Revs(1)
 * @Iterations(10)
 * @OutputTimeUnit("milliseconds", precision=2)
 */
class DoctrinePhpcrBench extends PhpcrOdmTestCase
{
    private $documentManager;

    public function setUp()
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
        $this->documentManager = $container->get('doctrine_phpcr.document_manager');
        $this->initPhpcr($this->documentManager);
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

        $this->documentManager->persist($image);
        $this->documentManager->flush();
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

        $this->documentManager->persist($article);
        $this->documentManager->flush();
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

        $this->documentManager->persist($article);
        $this->documentManager->flush();
    }
}
