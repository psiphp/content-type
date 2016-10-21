<?php

namespace Psi\Bridge\ContentType\Doctrine\PhpcrOdm\Tests\Functional;

use Psi\Bridge\ContentType\Doctrine\PhpcrOdm\Tests\Functional\Example\Article;
use Psi\Bridge\ContentType\Doctrine\PhpcrOdm\Tests\Functional\Example\ArticleWithRestrictedChildren;
use Psi\Bridge\ContentType\Doctrine\PhpcrOdm\Tests\Functional\Example\Image;

class ObjectUnrestrictTest extends PhpcrOdmTestCase
{
    private $documentManager;
    private $updater;

    public function init(array $mapping)
    {
        $container = $this->getContainer([
            'mapping' => $mapping,
        ]);
        $this->documentManager = $container->get('doctrine_phpcr.document_manager');
        $this->initPhpcr($this->documentManager);
        $this->updater = $container->get('psi_content_type.storage.doctrine.phpcr_odm.collection_updater');
    }

    /**
     * It should automatically allow mapped content objects as children.
     */
    public function testChildAddToValidChildren()
    {
        $this->init([
            ArticleWithRestrictedChildren::class => [
                'fields' => [
                    'title' => [
                        'type' => 'text',
                        'role' => 'title',
                    ],
                    'image' => [
                        'type' => 'image',
                        'role' => 'image',
                    ],
                ],
            ],
        ]);

        $image = $this->createImage('/path/to/image1', 100, 200, 'image/jpeg');
        $image->id = '/test/image';
        $article = new ArticleWithRestrictedChildren();
        $article->id = '/test/article';
        $article->title = 'Foo';
        $article->image = $image;

        $this->documentManager->persist($article);
        $this->updater->update($this->documentManager, $article);
        $this->documentManager->flush();
        $this->documentManager->clear();

        $this->documentManager->find(null, '/test/article');
    }

    /**
     * It should automatically allow mapped collection objects as children.
     */
    public function testCollectionAddToValidChildren()
    {
        $this->init([
            ArticleWithRestrictedChildren::class => [
                'fields' => [
                    'title' => [
                        'type' => 'text',
                        'role' => 'title',
                    ],
                    'slideshow' => [
                        'type' => 'collection',
                        'options' => [
                            'field_type' => 'image',
                        ],
                    ],
                ],
            ],
        ]);

        $image1 = $this->createImage('/path/to/image1', 100, 200, 'image/jpeg');
        $image2 = $this->createImage('/path/to/image2', 100, 200, 'image/jpeg');
        $image3 = $this->createImage('/path/to/image3', 100, 200, 'image/jpeg');

        $article = new ArticleWithRestrictedChildren();
        $article->id = '/test/article';
        $article->title = 'Foo';
        $article->slideshow = [$image1, $image2, $image3];

        $this->documentManager->persist($article);
        $this->updater->update($this->documentManager, $article);
        $this->documentManager->flush();
        $this->documentManager->clear();

        $this->documentManager->find(null, '/test/article');
    }
}
