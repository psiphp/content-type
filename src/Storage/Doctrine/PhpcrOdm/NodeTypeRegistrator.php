<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Component\ContentType\Storage\Doctrine\PhpcrOdm;

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
        $cnd = <<<'CND'
<cmfct='https://github.com/symfony-cmf/content-type'>
CND;

        $nodeTypeManager = $session->getWorkspace()->getNodeTypeManager();
        $nodeTypeManager->registerNodeTypesCnd($cnd, true);
    }
}
