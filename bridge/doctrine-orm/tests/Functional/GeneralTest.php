<?php

namespace Psi\Bridge\ContentType\Doctrine\Orm\Tests\Functional;

use Psi\Component\ContentType\Tests\Functional\Example\Model\Article;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Image;
use Psi\Component\ContentType\Tests\Functional\Storage\StorageTestTrait;

class GeneralTest extends OrmTestCase
{
    use StorageTestTrait;

    private $entityManager;

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
                            'options' => [
                                'class' => Image::class,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        $this->entityManager = $container->get('doctrine.entity_manager');
        $this->initOrm($this->entityManager);
    }

    public function testString()
    {
        $article = new Article();
        $article->title = 'Hello';
        $article = $this->persistAndReloadArticle($article);
        $this->assertEquals('Hello', $article->title);
    }

    public function testInteger()
    {
        $article = new Article();
        $article->integer = 45;
        $article = $this->persistAndReloadArticle($article);
        $this->assertEquals(45, $article->integer);
        $this->assertInternalType('int', $article->integer);
    }

    public function testDate()
    {
        $article = new Article();
        $article->date = new \DateTime('2016-01-01 00:00:00');
        $article = $this->persistAndReloadArticle($article);
        $this->assertEquals(new \DateTime('2016-01-01 00:00:00'), $article->date);
    }

    public function testObject()
    {
        $article = new Article();
        $article->image = $this->createImage(
            '/path/to/image', 100, 200, 'image/jpeg'
        );
        $article = $this->persistAndReloadArticle($article);
        $this->assertInstanceOf(Image::class, $article->image);
        $this->assertEquals(100, $article->image->width);
        $this->assertEquals('image/jpeg', $article->image->mimetype);
    }

    public function testReference()
    {
        $image = $this->createImage('/path/to/image1', 100, 200, 'image/jpeg');
        $image->id = '/test/image';
        $article = new Article();
        $article->id = '/test/article';
        $article->title = 'Foo';
        $article->date = new \DateTime();
        $article->referencedImage = $image;

        $this->entityManager->persist($article);
        $this->entityManager->flush();
        $this->entityManager->clear();

        $article = $this->entityManager->find(Article::class, '/test/article');
        $image = $article->referencedImage;
        $this->assertInstanceOf(Image::class, $image);
    }

    private function persistAndReloadArticle(Article $article)
    {
        $article->id = 'article';
        $this->entityManager->persist($article);
        $this->entityManager->flush();

        $this->entityManager->clear();

        return $this->entityManager->find(Article::class, 'article');
    }
}
