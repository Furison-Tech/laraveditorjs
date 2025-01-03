<?php

namespace BlockRulesSuppliersTests;

use FurisonTech\LaraveditorJS\EditorJSBlocks\HeaderBlock;
use PHPUnit\Framework\TestCase;

class HeaderBlockRulesSupplierTest extends TestCase
{
    public function testGetRulesReturnsExpectedArray(): void
    {
        $supplier = new HeaderBlock(255, 2,6,1);

        $rules = $supplier->rules();

        $this->assertIsArray($rules);
        $this->assertArrayHasKey('text', $rules);
        $this->assertEquals('required|string|max:255', $rules['text']);
        $this->assertArrayHasKey('level', $rules);
        $this->assertEquals('required|integer|min:2|max:6', $rules['level']);
    }
}