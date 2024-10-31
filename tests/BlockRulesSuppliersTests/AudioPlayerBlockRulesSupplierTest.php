<?php

namespace BlockRulesSuppliersTests;

use FurisonTech\LaraveditorJS\BlockRulesSuppliers\AudioPlayerBlockRulesSupplier;
use PHPUnit\Framework\TestCase;

final class AudioPlayerBlockRulesSupplierTest extends TestCase
{
    public function testGetRulesReturnsExpectedArray(): void
    {
        $supplier = new AudioPlayerBlockRulesSupplier(null,1);

        $rules = $supplier->getRules();

        $this->assertIsArray($rules);
        $this->assertArrayHasKey('src', $rules);
        $this->assertEquals('required|url', $rules['src']);
    }

    public function testGetRulesReturnsExpectedArrayWhenUrlStartNotNull(): void
    {
        $supplier = new AudioPlayerBlockRulesSupplier("https://example.com",1);

        $rules = $supplier->getRules();

        $this->assertIsArray($rules);
        $this->assertArrayHasKey('src', $rules);
        $this->assertEquals('required|url|starts_with:https://example.com', $rules['src']);
    }
}