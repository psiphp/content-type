<?php

namespace Symfony\Cmf\Component\ContentType\Tests\Functional\Storage\Doctrine\PhpcrOdm;

use Symfony\Cmf\Component\ContentType\Tests\Functional\BaseTestCase;
use Symfony\Cmf\Component\ContentType\Tests\Functional\Example\Model\Image;

class ContentTypeDriverTest extends PhpcrOdmTestCase
{
    private $documentManager;

    public function setUp()
    {
        $container = $this->getContainer([
            'mapping' => [
            ]
        ]);
        $this->documentManager = $container->get('doctrine_phpcr.document_manager');
        $this->initPhpcr($this->documentManager);
    }

    /**
     * It should persist a mapped content type document.
     */
    public function testMapping()
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
}
