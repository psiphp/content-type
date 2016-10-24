<?php

namespace Psi\Bridge\ContentType\Doctrine\Orm\Tests\Functional;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Psi\Component\ContentType\Tests\Functional\Example\Model\Image;

class OrmTestCase extends \PHPUnit_Framework_TestCase
{
    protected function getContainer(array $config = [])
    {
        return new Container($config);
    }

    protected function initOrm(EntityManagerInterface $entityManager)
    {
        $schemaTool = new SchemaTool($entityManager);
        $metadatas = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadatas);
        $schemaTool->createSchema($metadatas);
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
