<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\Standard\View;

use Psi\Component\ContentType\View\ViewInterface;

class ScalarView implements ViewInterface
{
    private $template;
    private $value;
    private $raw;
    private $tag;

    public function __construct(string $template, $value, string $tag = null, bool $raw = false)
    {
        $this->template = $template;
        $this->value = $value;
        $this->tag = $tag;
        $this->raw = $raw;
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

    public function isRaw()
    {
        return $this->raw;
    }
}
