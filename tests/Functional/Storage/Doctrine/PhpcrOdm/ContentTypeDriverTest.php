<?php

namespace Psi\Component\ContentType\Tests\Functional\Storage\Doctrine\PhpcrOdm;

use Doctrine\ODM\PHPCR\ChildrenCollection;
use Psi\Component\ContentType\Tests\Functional\Example\Storage\Doctrine\PhpcrOdm\Article;
use Psi\Component\ContentType\Tests\Functional\Example\Storage\Doctrine\PhpcrOdm\ArticleWithRestrictedChildren;
use Psi\Component\ContentType\Tests\Functional\Example\Storage\Doctrine\PhpcrOdm\Image;

class ContentTypeDriverTest extends PhpcrOdmTestCase
{
    private $documentManager;

    public function setUp()
    {
        $container = $this->getContainer([
            'mapping' => [
                ArticleWithRestrictedChildren::class => [
                    'fields' => [
                        'title' => [
                            'type' => 'text',
                            'role' => 'title',
                        ],
                        'image' => [
                            'type' => 'image',
                            'role' => 'image',
                            'options' => [
                                'class' => Image::class,
                            ],
                        ],
                        'slideshow' => [
                            'type' => 'collection',
                            'options' => [
                                'field' => 'image',
                            ],
                        ],
                    ],
                ],
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
     * It should persist object colletions.
     */
    public function testCollectionType()
    {
        $this->createArticleSlideshow();

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

    /**
     * Children should be removed from collections.
     *
     * @depends testCollectionType
     */
    public function testCollectionTypeChildrenRemoved()
    {
        $this->createArticleSlideshow();

        $article = $this->documentManager->find(null, '/test/article');
        $slideshow = $article->slideshow;
        $slideshow->removeElement($slideshow->first());
        $slideshow->removeElement($slideshow->first());

        $this->documentManager->persist($article);
        $this->documentManager->flush();
        $this->documentManager->clear();

        $image0 = $this->documentManager->find(null, '/test/article/psict:slideshow-0');
        $image1 = $this->documentManager->find(null, '/test/article/psict:slideshow-1');
        $image2 = $this->documentManager->find(null, '/test/article/psict:slideshow-2');

        $this->assertNull($image0);
        $this->assertNull($image1);
        $this->assertNotNull($image2);
    }

    /**
     * It should not preserve array keys.
     */
    public function testCollectionTypeArrayKeys()
    {
        $article = new Article();
        $article->id = '/test/article';
        $article->title = 'Foo';
        $article->date = new \DateTime();

        $image0 = $this->createImage('/path/to/image1', 100, 200, 'image/jpeg');
        $image1 = $this->createImage('/path/to/image2', 100, 200, 'image/jpeg');

        $article->slideshow = [
            'image_0' => $image0,
            'image_1' => $image1,
        ];

        $this->documentManager->persist($article);
        $this->documentManager->flush();
        $this->documentManager->clear();

        $image0 = $this->documentManager->find(null, '/test/article/psict:slideshow-0');
        $image1 = $this->documentManager->find(null, '/test/article/psict:slideshow-1');

        $this->assertNotNull($image0);
        $this->assertNotNull($image1);
    }

    public function testStringCollection()
    {
        $article = new Article();
        $article->id = '/test/article';
        $article->title = 'Foo';
        $article->paragraphs = ['one', 'two', 'three'];

        $this->documentManager->persist($article);
        $this->documentManager->flush();
        $this->documentManager->clear();

        $article = $this->documentManager->find(null, '/test/article');
        $this->assertSame(['one', 'two', 'three'], $article->paragraphs);
    }

    public function testIntegerCollection()
    {
        $article = new Article();
        $article->id = '/test/article';
        $article->title = 'Foo';
        $article->numbers = ['12', '13', '14'];

        $this->documentManager->persist($article);
        $this->documentManager->flush();
        $this->documentManager->clear();

        $article = $this->documentManager->find(null, '/test/article');
        $this->assertSame([12, 13, 14], $article->numbers);
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

    /**
     * It should automatically add the child class to the list of valid children when
     * children are defined.
     */
    public function testCollectionAddToValidChildren()
    {
        $image = $this->createImage('/path/to/image1', 100, 200, 'image/jpeg');
        $image->id = '/test/image';
        $article = new ArticleWithRestrictedChildren();
        $article->id = '/test/article';
        $article->title = 'Foo';
        $article->date = new \DateTime();
        $article->image = $image;

        $this->documentManager->persist($article);
        $this->documentManager->flush();
        $this->documentManager->clear();

        $this->documentManager->find(null, '/test/article');
    }

    private function createImage($path, $width, $height, $mimeType)
    {
        $image = new Image();
        $image->path = $path;
        $image->width = $width;
        $image->height = $height;
        $image->mimetype = $mimeType;

        return $image;
    }

    private function createArticleSlideshow()
    {
        $article = new Article();
        $article->id = '/test/article';
        $article->title = 'Hello';
        $article->date = new \DateTime();
        $article->image = $this->createImage(
            '/path/to/image', 100, 200, 'image/jpeg'
        );

        $image1 = $this->createImage('/path/to/image1', 100, 200, 'image/jpeg');
        $image2 = $this->createImage('/path/to/image2', 100, 200, 'image/jpeg');
        $image3 = $this->createImage('/path/to/image3', 100, 200, 'image/jpeg');

        $article->slideshow = [
            $image1,
            $image2,
            $image3,
        ];

        $this->documentManager->persist($article);
        $this->documentManager->flush();

        $this->documentManager->clear();
    }
}
