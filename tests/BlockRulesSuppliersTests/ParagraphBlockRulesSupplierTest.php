<?php

namespace BlockRulesSuppliersTests;

use FurisonTech\LaraveditorJS\EditorJSBlocks\ParagraphBlock;
use PHPUnit\Framework\TestCase;

class ParagraphBlockRulesSupplierTest extends TestCase
{
    public function testGetRulesReturnsExpectedArray(): void
    {
        $supplier = new ParagraphBlock(255);

        $rules = $supplier->rules();

        $this->assertIsArray($rules);
        $this->assertArrayHasKey('text', $rules);
        $this->assertEquals('required|string|max:255', $rules['text']);
    }
}