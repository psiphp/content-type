<?php

declare(strict_types=1);

namespace Psi\Component\ContentType\Standard\View;

use Psi\Component\ContentType\View\ViewInterface;

class ScalarView implements ViewInterface
{
    private $template;
    private $value;

    public function __construct(string $template, $value)
    {
        $this->template = $template;
        $this->value = $value;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getValue()
    {
        return $this->value;
    }
}
