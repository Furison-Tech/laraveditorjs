<?php

namespace BlockRulesSuppliersTests;

use FurisonTech\LaraveditorJS\BlockRulesSuppliers\HeaderBlockRulesSupplier;
use PHPUnit\Framework\TestCase;

class HeaderBlockRulesSupplierTest extends TestCase
{
    public function testGetRulesReturnsExpectedArray(): void
    {
        $supplier = new HeaderBlockRulesSupplier(255, 2,6,1);

        $rules = $supplier->getRules();

        $this->assertIsArray($rules);
        $this->assertArrayHasKey('text', $rules);
        $this->assertEquals('required|string|max:255', $rules['text']);
        $this->assertArrayHasKey('level', $rules);
        $this->assertEquals('required|integer|min:2|max:6', $rules['level']);
    }
}