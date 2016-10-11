<?php

namespace Psi\Component\ContentType\Storage\Doctrine\PhpcrOdm\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\Event\ManagerEventArgs;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ODM\PHPCR\Event;
use Doctrine\ODM\PHPCR\Mapping\ClassMetadata;
use Metadata\MetadataFactory;
use Metadata\MetadataFactoryInterface;
use PHPCR\Util\PathHelper;
use Psi\Component\ContentType\Storage\Doctrine\PhpcrOdm\PropertyEncoder;

/**
 * This class provides exception messages for cases when the collection
 * identifier updater has not been invoked.
 *
 * It is purely for debugging purposes and may safely be disabled.
 */
class CollectionSubscriber implements EventSubscriber
{
    private $metadataFactory;
    private $encoder;
    private $stack = [];

    public function __construct(
        MetadataFactoryInterface $metadataFactory,
        PropertyEncoder $encoder
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->encoder = $encoder;
    }

    public function getSubscribedEvents()
    {
        return [
            Event::prePersist,
            Event::preFlush,
        ];
    }

    public function preFlush(ManagerEventArgs $args)
    {
        if (empty($this->stack)) {
            return;
        }

        $metadataFactory = $args->getObjectManager()->getMetadataFactory();

        foreach ($this->stack as $data) {
            $children = $data['children'];
            $ctMetadata = $data['ct_metadata'];
            $childrenField = $data['field'];

            $index = 0;

            foreach ($children as $child) {
                $childMetadata = $metadataFactory->getMetadataFor(ClassUtils::getRealClass(get_class($child)));
                $expectedId = $this->encoder->encode($childrenField, $index++);
                $identifier = $childMetadata->getIdentifierValue($child);
                $idGenerator = $childMetadata->idGenerator;

                if ($idGenerator !== ClassMetadata::GENERATOR_TYPE_ASSIGNED) {
                    throw new \InvalidArgumentException(sprintf(
                        'Currently, all documents which belong to a mapped collection must use the ' .
                        'assigned ID generator strategy, "%s" is using "%s".',
                        $childMetadata->getName(), $idGenerator
                    ));
                }

                if (!$identifier || PathHelper::getNodeName($identifier) !== $expectedId) {
                    throw new \InvalidArgumentException(sprintf(
                        'Child mapped to content type "%s" on field "%s" has an unexpected ID "%s". ' .
                        'It is currently necessary to envoke the CollectionIdentifierUpdater on all ' .
                        'documents (at least those which have collections) before they are persisted.',
                        $ctMetadata->getType(),
                        $childrenField,
                        $identifier
                    ));
                }
            }
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $document = $args->getObject();
        $metadataFactory = $args->getObjectManager()->getMetadataFactory();
        $odmMetadata = $metadataFactory->getMetadataFor(ClassUtils::getRealClass(get_class($document)));

        if (null === $ctMetadata = $this->metadataFactory->getMetadataForClass($odmMetadata->getName())) {
            return;
        }

        foreach ($odmMetadata->childrenMappings as $childrenField) {

            // if the children field is not managed by the CT component,
            // continue
            if (!isset($ctMetadata->propertyMetadata[$childrenField])) {
                continue;
            }

            $childCtMetadata = $ctMetadata->propertyMetadata[$childrenField];
            $children = $odmMetadata->getFieldValue($document, $childrenField);

            if (!$children) {
                continue;
            }

            $this->stack[] = [
                'children' => $children,
                'ct_metadata' => $childCtMetadata,
                'field' => $childrenField,
            ];
        }
    }
}
