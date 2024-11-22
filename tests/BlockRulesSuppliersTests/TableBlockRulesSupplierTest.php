<?php

namespace BlockRulesSuppliersTests;

use FurisonTech\LaraveditorJS\BlockRulesSuppliers\TableBlockRulesSupplier;
use PHPUnit\Framework\TestCase;

class TableBlockRulesSupplierTest extends TestCase
{
    public function testGetRulesReturnsExpectedArray(): void
    {
        $supplier = new TableBlockRulesSupplier(10, 5, 255, 1);
        $rules = $supplier->rules();
        $this->assertIsArray($rules);
        $this->assertArrayHasKey('withHeadings', $rules);
        $this->assertArrayHasKey('content', $rules);
        $this->assertArrayHasKey('content.*', $rules);
        $this->assertArrayHasKey('content.*.*', $rules);
        $this->assertEquals('required|boolean', $rules['withHeadings']);
        $this->assertEquals('required|array|max:10', $rules['content']);
        $this->assertEquals('required|array|max:5', $rules['content.*']);
        $this->assertEquals('required|string|max:255', $rules['content.*.*']);
    }
}