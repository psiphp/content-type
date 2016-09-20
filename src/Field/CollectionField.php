<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psi\Component\ContentType\Field;

use Psi\Component\ContentType\FieldInterface;
use Psi\Component\ContentType\Form\Extension\Type\FieldCollectionType;
use Psi\Component\ContentType\MappingBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionField implements FieldInterface
{
    public function getViewType()
    {
        return CollectionView::class;
    }

    public function getFormType()
    {
        return FieldCollectionType::class;
    }

    public function getMapping(MappingBuilder $builder)
    {
        return $builder->collection();
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setRequired([
            'entry_type',
            'allow_add',
        ]);
    }
}
