<?php

namespace FurisonTech\LaraveditorJS;

interface HTMLContainable
{
    public function htmlableBlockDataFields(): array;
    public function allowedHtmlTagsAndAttributes(): array;
}