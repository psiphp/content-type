<?php

namespace Symfony\Cmf\Component\ContentType;

interface StorageInterface
{
    /**
     * Return true if the field managed by this storage type is  a scalar value.
     *
     * @return boolean
     */
    public function isScalar();


}
