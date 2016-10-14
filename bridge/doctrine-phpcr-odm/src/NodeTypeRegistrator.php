<?php

declare(strict_types=1);

namespace Psi\Bridge\ContentType\Doctrine\PhpcrOdm;

use PHPCR\SessionInterface;

/**
 * Encapsulates the logic for registering system node types.
 */
final class NodeTypeRegistrator
{
    private $encoder;

    public function __construct(PropertyEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Register the content-type node types with the given PHPCR session.
     */
    public function registerNodeTypes(SessionInterface $session)
    {
        $cnd = sprintf(
            '<%s=\'https://github.com/symfony-cmf/content-type\'>',
            $this->encoder->getPrefix(),
            $this->encoder->getUri()
        );

        $nodeTypeManager = $session->getWorkspace()->getNodeTypeManager();
        $nodeTypeManager->registerNodeTypesCnd($cnd, true);
    }
}
