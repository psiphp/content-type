<?php

namespace Psi\Component\ContentType\Storage\Doctrine\PhpcrOdm;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ODM\PHPCR\Event;
use Metadata\MetadataFactory;
use Metadata\MetadataFactoryInterface;
use Psi\Component\ContentType\Storage\Doctrine\PhpcrOdm\PropertyEncoder;
use Doctrine\ODM\PHPCR\DocumentManagerInterface;

/**
 * The collection identifier updater updates the IDs (paths) of any documents
 * mapped under a "CollectionField" to conform with the expected filter.
 *
 * This is necessary as it is not currently possible to achieve this in an
 * event subscriber within the PHPCR-ODM itself, see:
 *
 *     https://github.com/doctrine/phpcr-odm/issues/726
 *
 * Instead it is necessary to invoke this on any documents (at least those
 * which have a CollectionField mapping) before the DocumentManager#flush()
 * method is called. This could easily be done from the controller /
 * persistence agent of the consuming library.
 */
class CollectionIdentifierUpdater
{
    private $metadataFactory;
    private $encoder;

    public function __construct(
        MetadataFactoryInterface $metadataFactory,
        PropertyEncoder $encoder
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->encoder = $encoder;
    }

    public function update(DocumentManagerInterface $documentManager, $document)
    {
        $metadataFactory = $documentManager->getMetadataFactory();
        $classFqn = ClassUtils::getRealClass(get_class($document));
        $odmMetadata = $metadataFactory->getMetadataFor($classFqn);

        if (null === $ctMetadata = $this->metadataFactory->getMetadataForClass($classFqn)) {
            return;
        }

        $documentId = $odmMetadata->getIdentifierValue($document);

        foreach ($odmMetadata->childrenMappings as $childrenField) {

            // if the children field is not managed by the CT component,
            // continue
            if (!isset($ctMetadata->propertyMetadata[$childrenField])) {
                continue;
            }

            $children = $odmMetadata->getFieldValue($document, $childrenField);

            // note that we do not preserve array keys. PHPCR ODM will return a
            // children collection using the PHPCR property names as keys, so
            // we currently have no control over how these keys populated.
            $index = 0;
            foreach ($children as $child) {
                $childMetadata = $metadataFactory->getMetadataFor(ClassUtils::getRealClass(get_class($child)));
                $newId = sprintf(
                    '%s/%s',
                    $documentId,
                    $this->encoder->encode($childrenField, $index++)
                );

                $childMetadata->setIdentifierValue($child, $newId);
            }
        }
    }
}

