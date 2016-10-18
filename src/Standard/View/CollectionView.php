<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\Standard\View;

use Psi\Component\ContentType\View\ViewFactory;
use Psi\Component\ContentType\View\ViewInterface;

class CollectionView implements ViewInterface, \Iterator
{
    private $collection;
    private $factory;
    private $template;
    private $viewType;
    private $viewOptions;

    public function __construct(
        string $template,
        ViewFactory $factory,
        \Traversable $collection,
        string $viewType,
        array $viewOptions
    ) {
        $this->template = $template;
        $this->factory = $factory;
        $this->collection = $collection;
        $this->viewType = $viewType;
        $this->viewOptions = $viewOptions;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function current()
    {
        return $this->factory->create(
            $this->viewType,
            current($this->collection),
            $this->viewOptions
        );
    }

    public function next()
    {
        next($this->collection);
    }

    public function key()
    {
        return key($this->collection);
    }

    public function rewind()
    {
        reset($this->collection);
    }

    public function valid()
    {
        return key($this->collection) !== null;
    }
}
