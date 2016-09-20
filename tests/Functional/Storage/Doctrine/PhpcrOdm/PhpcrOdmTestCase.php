<?php

namespace Psi\Component\ContentType\Tests\Functional\Storage\Doctrine\PhpcrOdm;

use Doctrine\ODM\PHPCR\DocumentManagerInterface;
use Psi\Component\ContentType\Tests\Functional\BaseTestCase;

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
