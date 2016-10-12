<?php

namespace Psi\Component\ContentType\Form\Extension\Type;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\FieldRegistry;
use Psi\Component\ContentType\Metadata\ClassMetadata;
use Psi\Component\ContentType\Metadata\PropertyMetadata;
use Psi\Component\ContentType\OptionsResolver\FieldOptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Surrogate type for virtual user "content" form types.
 *
 * For example, if the user manages an "Article" class with the content type
 * system, this class will act as its form type.
 */
class SurrogateType extends AbstractType
{
    /**
     * @var string
     */
    private $contentFqn;

    /**
     * @var ClassMetadata
     */
    private $classMetadata;

    /**
     * @var FieldRegistry
     */
    private $fieldRegistry;

    public function __construct(
        $contentFqn,
        FieldRegistry $fieldRegistry,
        ClassMetadata $classMetadata
    ) {
        $this->contentFqn = $contentFqn;
        $this->fieldRegistry = $fieldRegistry;
        $this->classMetadata = $classMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $grouped = [];
        $ungrouped = [];
        foreach ($this->classMetadata->getPropertyMetadata() as $propertyMetadata) {
            if (null === $group = $propertyMetadata->getGroup()) {
                $ungrouped[] = $propertyMetadata;
                continue;
            }

            if (!isset($grouped[$group])) {
                $grouped[$group] = [];
            }

            $grouped[$group][] = $propertyMetadata;
        }

        $this->addPropertyMetadatas($builder, $ungrouped);

        foreach ($grouped as $group => $propertyMetadatas) {
            if (false === $builder->has($group)) {
                $builder->add(
                    $builder->create($group, FormType::class, [
                        'inherit_data' => true,
                    ])
                );
            }

            $this->addPropertyMetadatas($builder->get($group), $propertyMetadatas);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', $this->contentFqn);
    }

    private function addPropertyMetadatas(FormBuilderInterface $builder, array $propertyMetadatas)
    {
        foreach ($propertyMetadatas as $propertyMetadata) {
            $field = $this->fieldRegistry->get($propertyMetadata->getType());
            $builder->add(
                $propertyMetadata->getName(),
                $field->getFormType(),
                $this->getFormOptions($field, $propertyMetadata)
            );
        }
    }

    private function getFormOptions(FieldInterface $field, PropertyMetadata $propertyMetadata)
    {
        $formOptions = $propertyMetadata->getOptions();

        try {
            $resolver = new FieldOptionsResolver();
            $field->configureOptions($resolver);

            return $resolver->resolveFormOptions($formOptions);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException(sprintf(
                'Could not resolve options for property "%s#%s" (type "%s")',
                $propertyMetadata->getClass(), $propertyMetadata->getName(), $propertyMetadata->getType()
            ), null, $e);
        }
    }
}
