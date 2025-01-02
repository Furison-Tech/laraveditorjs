<?php

namespace BlockRulesSuppliersTests;

use FurisonTech\LaraveditorJS\EditorJSBlocks\ColumnBlock;
use FurisonTech\LaraveditorJS\EditorJSBlocks\ParagraphBlock;
use PHPUnit\Framework\TestCase;

class ColumnBlockRulesSupplierTest extends TestCase
{
    public function testGetRulesReturnsExpectedArray(): void
    {
        $supplier = new ColumnBlock(new ParagraphBlock(255));

        $supplier->setAllowedVersions(['2.22.2']);
        $supplier->setNestedData([
            'cols' => [
                [
                    'time' => 1730310036407,
                    'version' => '2.22.2',
                    'blocks' => [
                        [
                            'id' => '8dDU550iMy',
                            'type' => 'header',
                            'data' => [
                                'text' => 'Dit is een heading.',
                                'level' => 2
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $expectedRules = [
            "cols" => "required|array|min:2|max:3",
            "cols.0" => [
                "required",
                "array"
            ],
            "cols.0.time" => [
                "required",
                "integer"
            ],
            "cols.0.version" => [
                "required",
                "in:2.22.2"
            ],
            "cols.0.blocks" => [
                "required",
                "array"
            ],
            "cols.0.blocks.*" => [
                "required",
                "array"
            ],
            "cols.0.blocks.*.id" => [
                "required",
                "string",
                "size:10"
            ],
            "cols.0.blocks.*.type" => [
                "required",
                "string",
                "in:paragraph"
            ],
            "cols.0.blocks.*.data" => [
                "required",
                "array"
            ]
        ];

        $rules = $supplier->rules();

        $this->assertEquals($expectedRules, $rules);
    }
}