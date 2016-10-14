<?php

namespace Psi\Component\ContentType\Tests\Functional;

use Doctrine\Common\Annotations\AnnotationReader;
use Metadata\Driver\DriverChain;
use Metadata\MetadataFactory;
use Pimple\Container as PimpleContainer;
use Psi\Component\ContentType\FieldLoader;
use Psi\Component\ContentType\FieldRegistry;
use Psi\Component\ContentType\Form\Extension\FieldExtension;
use Psi\Component\ContentType\Metadata\Driver\AnnotationDriver as CTAnnotationDriver;
use Psi\Component\ContentType\Metadata\Driver\ArrayDriver;
use Psi\Component\ContentType\Standard\Field\CollectionField;
use Psi\Component\ContentType\Standard\Field\DateTimeField;
use Psi\Component\ContentType\Standard\Field\IntegerField;
use Psi\Component\ContentType\Standard\Field\TextField;
use Psi\Component\ContentType\Standard\Storage\CollectionType;
use Psi\Component\ContentType\Standard\Storage\DateTimeType;
use Psi\Component\ContentType\Standard\Storage\IntegerType;
use Psi\Component\ContentType\Standard\Storage\ObjectType;
use Psi\Component\ContentType\Standard\Storage\ReferenceType;
use Psi\Component\ContentType\Standard\Storage\StringType;
use Psi\Component\ContentType\Standard\View\ScalarView;
use Psi\Component\ContentType\Storage\TypeFactory;
use Psi\Component\ContentType\Storage\TypeRegistry;
use Psi\Component\ContentType\Tests\Functional\Example\Field\ImageField;
use Psi\Component\ContentType\Tests\Functional\Example\Field\ObjectReferenceField;
use Psi\Component\ContentType\Tests\Functional\Example\View\ImageView;
use Psi\Component\ContentType\View\ViewBuilder;
use Psi\Component\ContentType\View\ViewRegistry;
use Symfony\Component\Form\Forms;

class Container extends PimpleContainer
{
    public function __construct(array $config = [])
    {
        $this['config'] = $config;
        $this->loadGeneral();
        $this->loadPsiContentType();
        $this->loadSymfonyForm();
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
            $registry->register('object_reference', new ObjectReferenceField());
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
            return new ViewBuilder(
                $container['psi_content_type.metadata.factory'],
                $container['psi_content_type.field_loader'],
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
}
