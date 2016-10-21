<?php

namespace Psi\Bridge\ContentType\Doctrine\PhpcrOdm\Tests\Functional;

use Doctrine\ODM\PHPCR\DocumentManagerInterface;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Image;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Article;

class PhpcrOdmTestCase extends \PHPUnit_Framework_TestCase
{
    public function getContainer(array $config = [])
    {
        return new Container($config);
    }

    protected function initPhpcr(DocumentManagerInterface $documentManager)
    {
        $session = $documentManager->getPhpcrSession();

        $rootNode = $session->getRootNode();
        if ($rootNode->hasNode('test')) {
            $rootNode->getNode('test')->remove();
        }

        $rootNode->addNode('test');
    }

    protected function createImage($path, $width, $height, $mimeType)
    {
        $image = new Image();
        $image->path = $path;
        $image->width = $width;
        $image->height = $height;
        $image->mimetype = $mimeType;

        return $image;
    }

    protected function createArticleSlideshow()
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
