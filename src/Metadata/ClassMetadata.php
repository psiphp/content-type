<?php

namespace Psi\Component\ContentType\Metadata;

use Metadata\MergeableClassMetadata;
use Metadata\PropertyMetadata;
use Psi\Component\ContentType\Metadata\PropertyMetadata as PsiPropertyMetadata;

class ClassMetadata extends MergeableClassMetadata
{
    private $roles = [];

    public function __construct(
        $name
    ) {
        parent::__construct($name);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPropertyMetadata()
    {
        return $this->propertyMetadata;
    }

    public function addPropertyMetadata(PropertyMetadata $metadata)
    {
        $this->doAddPropertyMetadata($metadata);
    }

    public function hasPropertyByRole($role)
    {
        return isset($this->roles[$role]);
    }

    public function getPropertyByRole($role)
    {
        if (!isset($this->roles[$role])) {
            throw new \InvalidArgumentException(sprintf(
                'No property exists with role "%s"',
                $role
            ));
        }

        return $this->propertyMetadata[$this->roles[$role]];
    }

    private function doAddPropertyMetadata(PsiPropertyMetadata $metadata)
    {
        parent::addPropertyMetadata($metadata);
        if (!$metadata->getRole()) {
            return;
        }

        if (isset($this->roles[$metadata->getRole()])) {
            $propertyName = $this->roles[$metadata->getRole()];
            throw new \InvalidArgumentException(sprintf(
                'Role "%s" has already been assigned to property "%s" (on property "%s")',
                $metadata->getRole(), $propertyName, $metadata->getName()
            ));
        }

        $this->roles[$metadata->getRole()] = $metadata->name;
    }
}
