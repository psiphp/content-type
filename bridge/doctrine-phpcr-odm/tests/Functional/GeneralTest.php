<?php

namespace Psi\Bridge\ContentType\Doctrine\PhpcrOdm\Tests\Functional;

use Doctrine\ODM\PHPCR\ChildrenCollection;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Article;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Image;
use Psi\Component\ContentType\Tests\Functional\Storage\StorageTestTrait;

class GeneralTest extends PhpcrOdmTestCase
{
    use StorageTestTrait;

    private $documentManager;
    private $updater;

    public function initGeneralArticle()
    {
        $container = $this->getContainer([
            'mapping' => [
                Article::class => [
                    'fields' => [
                        'title' => [
                            'type' => 'text',
                            'role' => 'title',
                        ],
                        'integer' => [
                            'type' => 'integer',
                        ],
                        'image' => [
                            'type' => 'image',
                            'role' => 'image',
                        ],
                        'date' => [
                            'type' => 'datetime',
                        ],
                        'referencedImage' => [
                            'type' => 'object_reference',
                        ],
                        'boolean' => [
                            'type' => 'checkbox',
                        ],
                        'double' => [
                            'type' => 'range',
                        ],
                    ],
                ],
            ],
        ]);
        $this->documentManager = $container->get('doctrine_phpcr.document_manager');
        $this->initPhpcr($this->documentManager);
    }

    public function initCollectionArticle()
    {
        $container = $this->getContainer([
            'mapping' => [
                Article::class => [
                    'fields' => [
                        'slideshow' => [
                            'type' => 'collection',
                            'shared' => [
                                'field_type' => 'image',
                            ],
                        ],
                        'objectReferences' => [
                            'type' => 'collection',
                            'shared' => [
                                'field_type' => 'object_reference',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        $this->documentManager = $container->get('doctrine_phpcr.document_manager');
        $this->initPhpcr($this->documentManager);
        $this->updater = $container->get('psi_content_type.storage.doctrine.phpcr_odm.collection_updater');
    }

    public function initScalarCollectionArticle()
    {
        $container = $this->getContainer([
            'mapping' => [
                Article::class => [
                    'fields' => [
                        'paragraphs' => [
                            'type' => 'collection',
                            'shared' => [
                                'field_type' => 'text',
                                'field_options' => [],
                            ],
                        ],
                        'numbers' => [
                            'type' => 'collection',
                            'shared' => [
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

    public function testString()
    {
        $this->initGeneralArticle();

        $article = new Article();
        $article->title = 'Hello';
        $article = $this->persistAndReloadArticle($article);
        $this->assertEquals('Hello', $article->title);
    }

    public function testInteger()
    {
        $this->initGeneralArticle();

        $article = new Article();
        $article->integer = 45;
        $article = $this->persistAndReloadArticle($article);
        $this->assertEquals(45, $article->integer);
        $this->assertInternalType('int', $article->integer);
    }

    public function testDate()
    {
        $this->initGeneralArticle();

        $article = new Article();
        $article->date = new \DateTime('2016-01-01 00:00:00');
        $article = $this->persistAndReloadArticle($article);
        $this->assertEquals(new \DateTime('2016-01-01 00:00:00'), $article->date);
    }

    public function testBoolean()
    {
        $this->initGeneralArticle();

        $article = new Article();
        $article->boolean = true;
        $article = $this->persistAndReloadArticle($article);
        $this->assertEquals(true, $article->boolean);
    }

    public function testDouble()
    {
        $this->initGeneralArticle();

        $article = new Article();
        $article->double = 12.5;
        $article = $this->persistAndReloadArticle($article);
        $this->assertEquals(12.5, $article->double);
    }

    public function testObject()
    {
        $this->initGeneralArticle();

        $article = new Article();
        $article->image = $this->createImage(
            '/path/to/image', 100, 200, 'image/jpeg'
        );
        $article = $this->persistAndReloadArticle($article);
        $this->assertInstanceOf(Image::class, $article->image);
        $this->assertEquals(100, $article->image->width);
        $this->assertEquals('image/jpeg', $article->image->mimetype);
    }

    public function testObjectCollection()
    {
        $this->initCollectionArticle();

        $article = $this->createArticleSlideshow();
        $this->documentManager->persist($article);
        $this->updater->update($this->documentManager, $article);
        $this->documentManager->flush();
        $this->documentManager->clear();

        $article = $this->documentManager->find(null, '/test/article');
        $slideshow = $article->slideshow;
        $this->assertInstanceOf(ChildrenCollection::class, $slideshow);
        $slideshow = iterator_to_array($slideshow);
        $this->assertCount(3, $slideshow);

        $image1 = array_shift($slideshow);
        $image2 = array_shift($slideshow);
        $image3 = array_shift($slideshow);

        $this->assertEquals('/path/to/image1', $image1->path);
        $this->assertEquals('/path/to/image2', $image2->path);
        $this->assertEquals('/path/to/image3', $image3->path);
    }

    public function testReference()
    {
        $this->initGeneralArticle();

        $image = $this->createImage('/path/to/image1', 100, 200, 'image/jpeg');
        $image->id = '/test/image';
        $article = new Article();
        $article->referencedImage = $image;

        $this->persistAndReloadArticle($article);

        $article = $this->documentManager->find(null, '/test/article');
        $image = $article->referencedImage;
        $this->assertInstanceOf(Image::class, $image);
    }

    public function testReferenceCollection()
    {
        $this->initCollectionArticle();

        $article1 = new Article();
        $article1->id = '/test/article1';

        $article2 = new Article();
        $article2->id = '/test/article2';

        $this->documentManager->persist($article1);
        $this->documentManager->persist($article2);
        $this->documentManager->flush();

        $article = new Article();
        $article->objectReferences = [$article1, $article2];
        $article = $this->persistAndReloadArticle($article);

        $this->assertCount(2, $article->objectReferences);
        $this->assertEquals('/test/article1', $article->objectReferences[0]->id);
        $this->assertEquals('/test/article2', $article->objectReferences[1]->id);
    }

    public function testScalarCollection()
    {
        $this->initScalarCollectionArticle();

        $article = new Article();
        $article->id = '/test/article';
        $article->paragraphs = ['one', 'two', 'three'];
        $article = $this->persistAndReloadArticle($article);

        $this->assertSame(['one', 'two', 'three'], $article->paragraphs);
    }

    public function testIntegerCollection()
    {
        $this->initScalarCollectionArticle();

        $article = new Article();
        $article->id = '/test/article';
        $article->numbers = ['12', '13', '14'];
        $article = $this->persistAndReloadArticle($article);

        $this->assertSame([12, 13, 14], $article->numbers);
    }

    private function persistAndReloadArticle(Article $article)
    {
        $article->id = '/test/article';
        $this->documentManager->persist($article);
        $this->documentManager->flush();

        $this->documentManager->clear();

        return $this->documentManager->find(Article::class, '/test/article');
    }
}
