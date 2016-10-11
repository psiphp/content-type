<?php

namespace Psi\Component\ContentType\Tests\Functional\Storage\Doctrine\PhpcrOdm;

use Doctrine\ODM\PHPCR\DocumentManagerInterface;
use Psi\Component\ContentType\Tests\Functional\BaseTestCase;
use Psi\Component\ContentType\Tests\Functional\Example\Storage\Doctrine\PhpcrOdm\Image;

class PhpcrOdmTestCase extends BaseTestCase
{
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
