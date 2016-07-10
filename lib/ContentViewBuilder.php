<?php

namespace Symfony\Cmf\Component\ContentType;

use Symfony\Cmf\Component\ContentType\FieldRegistry;
use Symfony\Cmf\Component\ContentType\ViewRegistry;
use Symfony\Cmf\Component\ContentType\ContentView;

class ContentViewBuilder
{
    private $metadataFactory;
    private $fieldRegistry;
    private $viewRegistry;

    public function __construct(
        MetadataFactory $metadataFactory,
        FieldRegistry $fieldRegistry,
        ViewRegistry $viewRegistry
    )
    {
        $this->metadataFactory = $metadataFactory;
        $this->fieldRegistry = $fieldRegistry;
        $this->viewRegistry = $viewRegistry;
    }

    public function build($data)
    {
        $classFqn = get_class($data);
        $metadata = $this->metadataFactory->getMetadataForClass($classFqn);

        if ($metadata instanceof NullMetadata) {
            throw new \InvalidArgumentException(sprintf(
                'Class "%s" has no content mapping',
                $classFqn
            ));
        }

        $contentView = new ContentView();

        foreach ($metadata->getPropertyMetadata() as $propertyMetadata) {
            $subView = new ContentView();
            $field = $this->fieldRegistry->get($propertyMetadata->getType());
            $view = $this->viewRegistry->get($field->getViewType());

            $resolver = new OptionsResolver();
            $field->configureOptions($resolver);
            $options = $resolver->resolve($propertyMetadata->getOptions());
            $value = $propertyMetadata->getValue($data);
            $view->buildView($this, $subView, $value, $options);
            $contentView[$propertyMetadata->getName()] = $subView;
        }

        return $contentView;
    }
}
