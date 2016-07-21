<?php

namespace Symfony\Cmf\Component\ContentType\Tests\Functional\Storage\Doctrine\PhpcrOdm;

use Symfony\Cmf\Component\ContentType\Tests\Functional\BaseTestCase;
use Symfony\Cmf\Component\ContentType\Tests\Functional\Example\Model\Image;
use Symfony\Cmf\Component\ContentType\Tests\Functional\Example\Storage\Doctrine\PhpcrOdm\Article;

class ContentTypeDriverTest extends PhpcrOdmTestCase
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
                        ],
                        'image' => [
                            'type' => 'image',
                        ],
                    ],
                ]
            ]
        ]);
        $this->documentManager = $container->get('doctrine_phpcr.document_manager');
        $this->initPhpcr($this->documentManager);
    }

    /**
     * It should persist a mapped content type document.
     */
    public function testFieldMapping()
    {
        $image = new Image();
        $image->id = '/test/image';
        $image->path = '/path/to/image';
        $image->width = 100;
        $image->height = 200;
        $image->mimetype = 'image/jpeg';

        $this->documentManager->persist($image);
        $this->documentManager->flush();
    }

    /**
     * The user document should be persisted with the content-type data.
     */
    public function testUserMapping()
    {
        $article = new Article();
        $article->id = '/test/article';
        $article->title = 'Hello';
        $article->image = new Image();
        $article->image->path = '/path/to/image';
        $article->image->width = 100;
        $article->image->height = 200;
        $article->image->mimeType = 'image/jpeg';

        $this->documentManager->persist($article);
        $this->documentManager->flush();
    }
}
