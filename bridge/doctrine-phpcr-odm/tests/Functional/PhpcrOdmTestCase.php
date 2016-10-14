<?php

namespace Psi\Bridge\ContentType\Doctrine\PhpcrOdm\Tests\Functional;

use Doctrine\ODM\PHPCR\DocumentManagerInterface;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Image;

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
}
