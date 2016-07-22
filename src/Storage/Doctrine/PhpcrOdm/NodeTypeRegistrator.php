<?php

namespace Symfony\Cmf\Component\ContentType\Storage\Doctrine\PhpcrOdm;

use Doctrine\ODM\PHPCR\Translation\Translation;
use PHPCR\SessionInterface;

/**
 * Encapsulates the logic for registering system node types.
 */
final class NodeTypeRegistrator
{
    /**
     * Register the system node types on the given session.
     *
     * @param SessionInterface
     */
    public function registerNodeTypes(SessionInterface $session)
    {
        $cnd = <<<CND
<cmfct='https://github.com/symfony-cmf/content-type'>
CND
        ;

        $nodeTypeManager = $session->getWorkspace()->getNodeTypeManager();
        $nodeTypeManager->registerNodeTypesCnd($cnd, true);
    }
}
