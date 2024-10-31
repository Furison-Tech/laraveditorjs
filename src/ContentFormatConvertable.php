<?php

namespace FurisonTech\LaraveditorJS;

interface ContentFormatConvertable
{
    public function htmlableBlockDataFields(): array;
    public function allowedHtmlTagsAndAttributes(): array;
}