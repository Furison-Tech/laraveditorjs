<?php

namespace TestObjects;

use FurisonTech\LaraveditorJS\BlockRulesSuppliers\TableBlockRulesSupplier;
use FurisonTech\LaraveditorJS\HTMLContainable;

class MockConvertableTableBlockRulesSupplier extends TableBlockRulesSupplier implements HTMLContainable
{
    public function __construct(int $maxRows, int $maxColumns, int $maxTextLength)
    {
        parent::__construct($maxRows, $maxColumns, $maxTextLength);
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
            'content.*.*'
        ];
    }
}