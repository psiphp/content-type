<?php

namespace Symfony\Cmf\Component\ContentType\Tests\Functional\Storage\Doctrine\PhpcrOdm;

use Symfony\Cmf\Component\ContentType\Tests\Functional\BaseTestCase;
use Doctrine\ODM\PHPCR\DocumentManagerInterface;

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
}
