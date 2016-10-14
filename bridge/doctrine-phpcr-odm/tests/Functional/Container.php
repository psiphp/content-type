<?php

namespace Psi\Bridge\ContentType\Doctrine\PhpcrOdm\Tests\Functional;

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\DBAL\DriverManager;
use Doctrine\ODM\PHPCR\Configuration;
use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\ODM\PHPCR\Mapping\Driver\AnnotationDriver;
use Doctrine\ODM\PHPCR\Mapping\Driver\XmlDriver;
use Doctrine\ODM\PHPCR\NodeTypeRegistrator;
use Jackalope\RepositoryFactoryDoctrineDBAL;
use Jackalope\Transport\DoctrineDBAL\RepositorySchema;
use PHPCR\SimpleCredentials;
use Psi\Bridge\ContentType\Doctrine\PhpcrOdm\CollectionIdentifierUpdater;
use Psi\Bridge\ContentType\Doctrine\PhpcrOdm\FieldMapper;
use Psi\Bridge\ContentType\Doctrine\PhpcrOdm\NodeTypeRegistrator as CtNodeTypeRegistrator;
use Psi\Bridge\ContentType\Doctrine\PhpcrOdm\PropertyEncoder;
use Psi\Bridge\ContentType\Doctrine\PhpcrOdm\Subscriber\CollectionSubscriber;
use Psi\Bridge\ContentType\Doctrine\PhpcrOdm\Subscriber\MetadataSubscriber;
use Psi\Component\ContentType\Tests\Functional\Container as BaseContainer;

class Container extends BaseContainer
{
    public function __construct(array $config)
    {
        parent::__construct(array_merge([
            'mapping' => [],
            'db_path' => __DIR__ . '/../../../../cache/test.sqlite',
        ], $config));

        $this->loadDoctrineDbal();
        $this->loadPhpcrOdm();
    }

    private function loadDoctrineDbal()
    {
        $this['dbal.connection'] = function () {
            return DriverManager::getConnection([
                'driver'    => 'pdo_sqlite',
                'path' => $this['config']['db_path'],
            ]);
        };
    }

    private function loadPhpcrOdm()
    {
        $this['psi_content_type.storage.doctrine.phpcr_odm.property_encoder'] = function ($container) {
            return new PropertyEncoder('psict', 'https://github.com/psiphp/content-type');
        };

        $this['psi_content_type.storage.doctrine.phpcr_odm.field_mapper'] = function ($container) {
            return new FieldMapper(
                $container['psi_content_type.storage.doctrine.phpcr_odm.property_encoder'],
                $container['psi_content_type.field_loader']
            );
        };

        $this['psi_content_type.storage.doctrine.phpcr_odm.collection_updater'] = function ($container) {
            return new CollectionIdentifierUpdater(
                $container['psi_content_type.metadata.factory'],
                $container['psi_content_type.storage.doctrine.phpcr_odm.property_encoder']
            );
        };

        $this['doctrine_phpcr.document_manager'] = function ($container) {
            $registerNodeTypes = false;

            // automatically setup the schema if the db doesn't exist yet.
            if (!file_exists($container['config']['db_path'])) {
                if (!file_exists($dir = dirname($container['config']['db_path']))) {
                    mkdir($dir);
                }

                $connection = $container['dbal.connection'];

                $schema = new RepositorySchema();
                foreach ($schema->toSql($connection->getDatabasePlatform()) as $sql) {
                    $connection->exec($sql);
                }

                $registerNodeTypes = true;
            }

            // register the phpcr session
            $factory = new RepositoryFactoryDoctrineDBAL();
            $repository = $factory->getRepository([
                'jackalope.doctrine_dbal_connection' => $container['dbal.connection'],
            ]);
            $session = $repository->login(new SimpleCredentials(null, null), 'default');

            if ($registerNodeTypes) {
                $typeRegistrator = new NodeTypeRegistrator();
                $typeRegistrator->registerNodeTypes($session);
                $ctTypeRegistrator = new CtNodeTypeRegistrator(
                    $container['psi_content_type.storage.doctrine.phpcr_odm.property_encoder']
                );
                $ctTypeRegistrator->registerNodeTypes($session);
            }

            // annotation driver
            $annotationDriver = new AnnotationDriver($container['annotation_reader'], [
                __DIR__ . '/../../vendor/doctrine/phpcr-odm/lib/Doctrine/ODM/PHPCR/Document',
                __DIR__ . '/Example',
            ]);
            $xmlDriver = new XmlDriver([__DIR__ . '/mappings']);
            $annotationDriver = new AnnotationDriver($container['annotation_reader'], [
                __DIR__ . '/../../vendor/doctrine/phpcr-odm/lib/Doctrine/ODM/PHPCR/Document',
                __DIR__ . '/Example',
            ]);
            $chain = new MappingDriverChain();
            $chain->addDriver($annotationDriver, 'Psi\Bridge\ContentType\Doctrine\PhpcrOdm\Tests\Functional\Example');
            $chain->addDriver($xmlDriver, 'Psi\Component\ContentType\Tests\Functional\Example\Model');
            $chain->addDriver($annotationDriver, 'Doctrine');

            $config = new Configuration();
            $config->setMetadataDriverImpl($chain);

            $manager = DocumentManager::create($session, $config);
            $manager->getEventManager()->addEventSubscriber(new MetadataSubscriber(
                $container['psi_content_type.metadata.factory'],
                $container['psi_content_type.field_loader'],
                $container['psi_content_type.storage.doctrine.phpcr_odm.field_mapper']
            ));
            $manager->getEventManager()->addEventSubscriber(new CollectionSubscriber(
                $container['psi_content_type.metadata.factory'],
                $container['psi_content_type.storage.doctrine.phpcr_odm.property_encoder']
            ));

            return $manager;
        };
    }
}
