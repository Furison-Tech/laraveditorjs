<?php

namespace TestObjects;

use FurisonTech\LaraveditorJS\BlockRulesSuppliers\ParagraphBlockRulesSupplier;
use FurisonTech\LaraveditorJS\ContentFormatConvertable;

class MockConvertableParagraphBlockRulesSupplier extends ParagraphBlockRulesSupplier implements ContentFormatConvertable
{

    public function __construct(int $maxLength)
    {
        parent::__construct($maxLength);
    }

    public function allowedHtmlTagsAndAttributes(): array
    {
        return [
            'span' => [
                'style' => [
                    'color' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$'
                ]
            ],
            'a' => [
                'attributes' => [
                    'href'
                ]
            ],
            'b' => [],
            'i' => [],
        ];
    }

    public function htmlableBlockDataFields(): array
    {
        return [
            'text'
        ];
    }
}