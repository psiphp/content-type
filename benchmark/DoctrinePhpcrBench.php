<?php

namespace Psi\Component\ContentType\Benchmark;

use Psi\Component\ContentType\Tests\Functional\Example\Storage\Doctrine\PhpcrOdm\Article;
use Psi\Component\ContentType\Tests\Functional\Example\Storage\Doctrine\PhpcrOdm\Image;
use Psi\Component\ContentType\Tests\Functional\Storage\Doctrine\PhpcrOdm\PhpcrOdmTestCase;

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
                                'field' => 'image',
                            ],
                        ],
                        'date' => [
                            'type' => 'datetime',
                        ],
                        'referencedImage' => [
                            'type' => 'image_reference',
                        ],
                        'paragraphs' => [
                            'type' => 'collection',
                            'options' => [
                                'field' => 'text',
                                'field_options' => [],
                            ],
                        ],
                        'numbers' => [
                            'type' => 'collection',
                            'options' => [
                                'field' => 'integer',
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
