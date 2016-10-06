<?php

namespace Psi\Component\ContentType\Tests\Functional;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\DBAL\DriverManager;
use Doctrine\ODM\PHPCR\Configuration;
use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\ODM\PHPCR\Mapping\Driver\AnnotationDriver;
use Doctrine\ODM\PHPCR\NodeTypeRegistrator;
use Jackalope\RepositoryFactoryDoctrineDBAL;
use Jackalope\Transport\DoctrineDBAL\RepositorySchema;
use Metadata\Driver\DriverChain;
use Metadata\MetadataFactory;
use PHPCR\SimpleCredentials;
use Pimple\Container as PimpleContainer;
use Psi\Component\ContentType\ContentViewBuilder;
use Psi\Component\ContentType\Field\CollectionField;
use Psi\Component\ContentType\Field\DateTimeField;
use Psi\Component\ContentType\Field\IntegerField;
use Psi\Component\ContentType\Field\TextField;
use Psi\Component\ContentType\FieldLoader;
use Psi\Component\ContentType\FieldRegistry;
use Psi\Component\ContentType\Form\Extension\FieldExtension;
use Psi\Component\ContentType\Metadata\Driver\AnnotationDriver as CTAnnotationDriver;
use Psi\Component\ContentType\Metadata\Driver\ArrayDriver;
use Psi\Component\ContentType\Storage\Doctrine\PhpcrOdm\FieldMapper;
use Psi\Component\ContentType\Storage\Doctrine\PhpcrOdm\NodeTypeRegistrator as CtNodeTypeRegistrator;
use Psi\Component\ContentType\Storage\Doctrine\PhpcrOdm\PropertyEncoder;
use Psi\Component\ContentType\Storage\Doctrine\PhpcrOdm\Subscriber\CollectionSubscriber;
use Psi\Component\ContentType\Storage\Doctrine\PhpcrOdm\Subscriber\MetadataSubscriber;
use Psi\Component\ContentType\Storage\Mapping\Type\CollectionType;
use Psi\Component\ContentType\Storage\Mapping\Type\DateTimeType;
use Psi\Component\ContentType\Storage\Mapping\Type\IntegerType;
use Psi\Component\ContentType\Storage\Mapping\Type\ObjectType;
use Psi\Component\ContentType\Storage\Mapping\Type\ReferenceType;
use Psi\Component\ContentType\Storage\Mapping\Type\StringType;
use Psi\Component\ContentType\Storage\Mapping\TypeFactory;
use Psi\Component\ContentType\Storage\Mapping\TypeRegistry;
use Psi\Component\ContentType\Tests\Functional\Example\Field\ImageField;
use Psi\Component\ContentType\Tests\Functional\Example\Field\ImageReferenceField;
use Psi\Component\ContentType\Tests\Functional\Example\View\ImageView;
use Psi\Component\ContentType\View\ScalarView;
use Psi\Component\ContentType\ViewRegistry;
use Symfony\Component\Form\Forms;

class Container extends PimpleContainer
{
    public function __construct(array $config = [])
    {
        $this['config'] = array_merge([
            'mapping' => [],
            'db_path' => __DIR__ . '/../../cache/test.sqlite',
        ], $config);

        $this->loadGeneral();
        $this->loadPsiContentType();
        $this->loadSymfonyForm();
        $this->loadDoctrineDbal();
        $this->loadPhpcrOdm();
    }

    public function get($serviceId)
    {
        return $this[$serviceId];
    }

    private function loadGeneral()
    {
        $this['annotation_reader'] = function () {
            return new AnnotationReader();
        };
    }

    private function loadPsiContentType()
    {
        $this['psi_content_type.metadata.driver.array'] = function ($container) {
            return new ArrayDriver($container['config']['mapping']);
        };
        $this['psi_content_type.metadata.driver.annotation'] = function ($container) {
            return new CTAnnotationDriver($container['annotation_reader']);
        };
        $this['psi_content_type.metadata.driver.chain'] = function ($container) {
            return new DriverChain([
                $container['psi_content_type.metadata.driver.array'],
                $container['psi_content_type.metadata.driver.annotation'],
            ]);
        };

        $this['psi_content_type.metadata.factory'] = function ($container) {
            return new MetadataFactory(
                $container['psi_content_type.metadata.driver.chain']
            );
        };

        $this['psi_content_type.registry.field'] = function ($container) {
            $registry = new FieldRegistry();
            $registry->register('text', new TextField());
            $registry->register('integer', new IntegerField());
            $registry->register('datetime', new DateTimeField());
            $registry->register('image', new ImageField());
            $registry->register('image_reference', new ImageReferenceField());
            $registry->register('collection', new CollectionField($registry));

            return $registry;
        };

        $this['psi_content_type.registry.view'] = function ($container) {
            $registry = new ViewRegistry();
            $registry->register(ScalarView::class, new ScalarView());
            $registry->register(ImageView::class, new ImageView());

            return $registry;
        };

        $this['psi_content_type.registry.type'] = function ($container) {
            $registry = new TypeRegistry();
            $registry->register('string', new StringType());
            $registry->register('integer', new IntegerType());
            $registry->register('datetime', new DateTimeType());
            $registry->register('reference', new ReferenceType());
            $registry->register('object', new ObjectType());
            $registry->register('collection', new CollectionType());

            return $registry;
        };

        $this['psi_content_type.field_loader'] = function ($container) {
            return new FieldLoader(
                $container->get('psi_content_type.storage.type_factory'),
                $container->get('psi_content_type.registry.field')
            );
        };

        $this['psi_content_type.storage.type_factory'] = function ($container) {
            return new TypeFactory($container->get('psi_content_type.registry.type'));
        };

        $this['psi_content_type.view_builder'] = function ($container) {
            return new ContentViewBuilder(
                $container['psi_content_type.metadata.factory'],
                $container['psi_content_type.registry.field'],
                $container['psi_content_type.registry.view']
            );
        };
    }

    private function loadSymfonyForm()
    {
        $this['symfony.form_factory'] = function ($container) {
            return Forms::createFormFactoryBuilder()
                ->addExtension(new FieldExtension(
                    $container['psi_content_type.metadata.factory'],
                    $container['psi_content_type.registry.field']
                ))
                ->getFormFactory();
        };
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
            return new PropertyEncoder('cmfct', 'https://github.com/symfony-cmf/content-type');
        };

        $this['psi_content_type.storage.doctrine.phpcr_odm.field_mapper'] = function ($container) {
            return new FieldMapper(
                $container['psi_content_type.storage.doctrine.phpcr_odm.property_encoder'],
                $container['psi_content_type.field_loader']
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
                __DIR__ . '/Example/Storage/Doctrine/PhpcrOdm',
            ]);
            $chain = new MappingDriverChain();
            $chain->addDriver($annotationDriver, 'Psi\Component\ContentType\Tests\Functional\Example\Storage\Doctrine\PhpcrOdm');
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
