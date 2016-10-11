<?php

namespace Psi\Component\ContentType\Tests\Functional\Storage\Doctrine\PhpcrOdm;

use Doctrine\ODM\PHPCR\ChildrenCollection;
use Psi\Component\ContentType\Tests\Functional\Example\Storage\Doctrine\PhpcrOdm\Article;
use Psi\Component\ContentType\Tests\Functional\Example\Storage\Doctrine\PhpcrOdm\ArticleWithRestrictedChildren;
use Psi\Component\ContentType\Tests\Functional\Example\Storage\Doctrine\PhpcrOdm\Image;

class ObjectTest extends PhpcrOdmTestCase
{
    private $documentManager;
    private $updater;

    public function setUp()
    {
        $container = $this->getContainer([
            'mapping' => [
                Article::class => [
                    'fields' => [
                        'slideshow' => [
                            'type' => 'collection',
                            'options' => [
                                'field' => 'image',
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

    /**
     * It should persist object colletions.
     */
    public function testCollectionType()
    {
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

    /**
     * It should update an object collection.
     */
    public function testCollectionTypeUpdate()
    {
        $article = $this->createArticleSlideshow();
        $this->updater->update($this->documentManager, $article);
        $this->documentManager->persist($article);
        $this->documentManager->flush();
        $this->documentManager->clear();

        $image = $this->createImage('/path/to/image7', 100, 200, 'image/jpeg');

        $article = $this->documentManager->find(null, '/test/article');
        $article->slideshow->add($image);
        $this->assertCount(4, iterator_to_array($article->slideshow));

        $this->documentManager->persist($article);
        $this->updater->update($this->documentManager, $article);
        $this->documentManager->flush();
        $this->documentManager->clear();
        $article = $this->documentManager->find(null, '/test/article');

        $slideshow = iterator_to_array($article->slideshow);
        $this->assertCount(4, $slideshow);
    }

    /**
     * Children should be removed from collections.
     *
     * @depends testCollectionType
     */
    public function testCollectionTypeChildrenRemoved()
    {
        $article = $this->createArticleSlideshow();
        $this->updater->update($this->documentManager, $article);
        $this->documentManager->persist($article);
        $this->documentManager->flush();
        $this->documentManager->clear();

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
        $this->updater->update($this->documentManager, $article);
        $this->documentManager->flush();
        $this->documentManager->clear();

        $image0 = $this->documentManager->find(null, '/test/article/psict:slideshow-0');
        $image1 = $this->documentManager->find(null, '/test/article/psict:slideshow-1');

        $this->assertNotNull($image0);
        $this->assertNotNull($image1);
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

    /**
     * It should throw an exception when persisting a collection and the "updater" has not been invoked on
     * the collection.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage It is currently necessary to envoke the CollectionIdentifierUpdater on all documents (at least those which have collections) before they are persisted.
     */
    public function testCollectionPersistNoUpdater()
    {
        $article = $this->createArticleSlideshow();
        $this->documentManager->persist($article);
        $this->documentManager->flush();
        $this->documentManager->clear();
    }

    /**
     * It should throw an exception when a document in a collection does not have the "ASSIGNED" ID generator.
     */
    public function testCollectionPersistNoAssignedGenerator()
    {
        $this->markTestIncomplete('TODO');
    }
    /**
     * It should store arrays of references.
     */
    public function testStoreArrayOfReferences()
    {
        $this->markTestIncomplete('TODO');
    }

    private function createArticleSlideshow()
    {
        $article = new Article();
        $article->id = '/test/article';

        $image1 = $this->createImage('/path/to/image1', 100, 200, 'image/jpeg');
        $image2 = $this->createImage('/path/to/image2', 100, 200, 'image/jpeg');
        $image3 = $this->createImage('/path/to/image3', 100, 200, 'image/jpeg');

        $article->slideshow = [
            $image1,
            $image2,
            $image3,
        ];

        return $article;
    }
}
