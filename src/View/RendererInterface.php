<?php

namespace Psi\Component\ContentType\View;

interface RendererInterface
{
    public function render(ViewInterface $view): string;
}
