<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\Standard\View;

use Psi\Component\ContentType\View\ViewInterface;

class ScalarView implements ViewInterface
{
    private $template;
    private $value;
    private $tag;

    public function __construct(string $template, $value, string $tag = null)
    {
        $this->template = $template;
        $this->value = $value;
        $this->tag = $tag;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getTag()
    {
        return $this->tag;
    }

    public function getValue()
    {
        return $this->value;
    }
}
