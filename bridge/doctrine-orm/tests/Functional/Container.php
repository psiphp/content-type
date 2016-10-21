<?php

namespace Psi\Bridge\ContentType\Doctrine\Orm\Tests\Functional;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Psi\Bridge\ContentType\Doctrine\Orm\FieldMapper;
use Psi\Bridge\ContentType\Doctrine\Orm\Subscriber\MetadataSubscriber;
use Psi\Component\ContentType\Tests\Functional\Container as BaseContainer;

class Container extends BaseContainer
{
    public function __construct(array $config)
    {
        parent::__construct(array_merge([
            'mapping' => [],
            'db_path' => __DIR__ . '/../../../../cache/test.sqlite',
        ], $config));

        $this->loadOrm();
    }

    private function loadOrm()
    {
        $this['psi_content_type.storage.doctrine.orm.field_mapper'] = function ($container) {
            return new FieldMapper(
            );
        };
        $this['doctrine.entity_manager'] = function ($container) {
            $dbParams = [
                'driver'    => 'pdo_sqlite',
                'path' => $this['config']['db_path'],
            ];
            $paths = [
                __DIR__ . '/mappings',
            ];
            $config = Setup::createXMLMetadataConfiguration($paths, true);
            $manager = EntityManager::create($dbParams, $config);
            $manager->getEventManager()->addEventSubscriber(new MetadataSubscriber(
                $container['psi_content_type.metadata.factory'],
                $container['psi_content_type.field_loader'],
                $container['psi_content_type.storage.doctrine.orm.field_mapper']
            ));

            return $manager;
        };
    }
}
