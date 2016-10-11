<?php

namespace Psi\Component\ContentType\Tests\Functional\Storage\Doctrine\PhpcrOdm;

use Doctrine\ODM\PHPCR\ChildrenCollection;
use Psi\Component\ContentType\Tests\Functional\Example\Storage\Doctrine\PhpcrOdm\Article;
use Psi\Component\ContentType\Tests\Functional\Example\Storage\Doctrine\PhpcrOdm\ArticleWithRestrictedChildren;
use Psi\Component\ContentType\Tests\Functional\Example\Storage\Doctrine\PhpcrOdm\Image;

class GeneralTest extends PhpcrOdmTestCase
{
    private $documentManager;

    public function setUp()
    {
        $container = $this->getContainer([
            'mapping' => [
                Article::class => [
                    'fields' => [
                        'title' => [
                            'type' => 'text',
                            'role' => 'title',
                        ],
                        'image' => [
                            'type' => 'image',
                            'role' => 'image',
                        ],
                        'date' => [
                            'type' => 'datetime',
                        ],
                        'referencedImage' => [
                            'type' => 'image_reference',
                        ],
                    ],
                ],
            ],
        ]);
        $this->documentManager = $container->get('doctrine_phpcr.document_manager');
        $this->initPhpcr($this->documentManager);
    }

    /**
     * The user document should be persisted with the content-type data.
     */
    public function testMapping()
    {
        $article = new Article();
        $article->id = '/test/article';
        $article->title = 'Hello';
        $article->date = new \DateTime('2016-01-01 00:00:00');

        $article->image = $this->createImage(
            '/path/to/image', 100, 200, 'image/jpeg'
        );

        $this->documentManager->persist($article);
        $this->documentManager->flush();

        $this->documentManager->clear();

        $article = $this->documentManager->find(null, '/test/article');

        $this->assertInstanceOf(Article::class, $article);
        $this->assertEquals('Hello', $article->title);
        $this->assertInstanceOf(Image::class, $article->image);
        $this->assertEquals(100, $article->image->width);
        $this->assertEquals('image/jpeg', $article->image->mimetype);
        $this->assertEquals(new \DateTime('2016-01-01 00:00:00'), $article->date);
    }

    /**
     * It should map a reference.
     */
    public function testMapReference()
    {
        $image = $this->createImage('/path/to/image1', 100, 200, 'image/jpeg');
        $image->id = '/test/image';
        $article = new Article();
        $article->id = '/test/article';
        $article->title = 'Foo';
        $article->date = new \DateTime();
        $article->referencedImage = $image;

        $this->documentManager->persist($article);
        $this->documentManager->flush();
        $this->documentManager->clear();

        $article = $this->documentManager->find(null, '/test/article');
        $image = $article->referencedImage;
        $this->assertInstanceOf(Image::class, $image);
    }
}
