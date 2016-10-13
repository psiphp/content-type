<?php

namespace Psi\Component\ContentType\View;

use Metadata\MetadataFactory;
use Metadata\NullMetadata;
use Psi\Component\ContentType\FieldLoader;

use Psi\Component\ContentType\LoadedField;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ViewBuilder
{
    private $metadataFactory;
    private $fieldLoader;
    private $viewRegistry;

    public function __construct(
        MetadataFactory $metadataFactory,
        FieldLoader $fieldLoader,
        ViewRegistry $viewRegistry
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->fieldLoader = $fieldLoader;
        $this->viewRegistry = $viewRegistry;
    }

    public function build($content)
    {
        $classFqn = get_class($content);
        $metadata = $this->metadataFactory->getMetadataForClass($classFqn);

        if ($metadata instanceof NullMetadata) {
            throw new \RuntimeException(sprintf(
                'Class "%s" is not mapped',
                $classFqn
            ));
        }

        $contentView = new View('psi/container');

        foreach ($metadata->getPropertyMetadata() as $propertyMetadata) {
            $data = $propertyMetadata->getValue($content);
            $childView = $this->createView($propertyMetadata->getType(), $data, $propertyMetadata->getOptions());
            $contentView[$propertyMetadata->getName()] = $childView;
        }

        return $contentView;
    }

    public function createView(string $fieldType, $data, array $options)
    {
        $fieldService = $this->fieldLoader->load($fieldType, $options);
        $viewService = $this->viewRegistry->get($fieldService->getInnerField()->getViewType());
        $options = $this->resolveOptions($fieldService, $viewService, $options);

        $view = new View($options['template']);
        $viewService->buildView($view, $data, $options);

        return $view;
    }

    private function resolveOptions(LoadedField $field, ViewInterface $view, array $options)
    {
        $options = $field->getViewOptions();

        $viewOptionResolver = new OptionsResolver();
        $viewOptionResolver->setDefault('template', null);
        $viewOptionResolver->setAllowedTypes('template', ['string', 'null']);

        $view->configureOptions($viewOptionResolver);
        $options = $viewOptionResolver->resolve($options);

        return $options;
    }
}
