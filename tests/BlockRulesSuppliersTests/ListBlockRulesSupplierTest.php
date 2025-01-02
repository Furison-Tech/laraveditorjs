<?php

namespace BlockRulesSuppliersTests;

use FurisonTech\LaraveditorJS\EditorJSBlocks\ListBlock;
use PHPUnit\Framework\TestCase;

class ListBlockRulesSupplierTest extends TestCase
{
    public function testGetRulesReturnsExpectedArray(): void
    {
        $supplier = new ListBlock(10, 255, 1);

        $rules = $supplier->rules();

        $this->assertIsArray($rules);
        $this->assertArrayHasKey('style', $rules);
        $this->assertArrayHasKey('items', $rules);
        $this->assertArrayHasKey('items.*', $rules);
        $this->assertEquals('required|in:ordered,unordered', $rules['style']);
        $this->assertEquals('required|array|max:10', $rules['items']);
        $this->assertEquals('required|string|max:255', $rules['items.*']);
    }
}