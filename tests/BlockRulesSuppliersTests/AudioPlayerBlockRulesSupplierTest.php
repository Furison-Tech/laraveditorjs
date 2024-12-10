<?php

namespace BlockRulesSuppliersTests;

use FurisonTech\LaraveditorJS\BlockRulesSuppliers\AudioBlockRulesSupplier;
use PHPUnit\Framework\TestCase;

final class AudioPlayerBlockRulesSupplierTest extends TestCase
{
    public function testGetRulesReturnsExpectedArray(): void
    {
        $supplier = new AudioBlockRulesSupplier(null);
        //todo : fix (was audioPlayer, not anymore)

        $rules = $supplier->rules();

        $this->assertIsArray($rules);
        $this->assertArrayHasKey('src', $rules);
        $this->assertEquals('required|url', $rules['src']);
    }

    public function testGetRulesReturnsExpectedArrayWhenUrlStartNotNull(): void
    {
        $supplier = new AudioBlockRulesSupplier("https://example.com");

        $rules = $supplier->rules();

        $this->assertIsArray($rules);
        $this->assertArrayHasKey('src', $rules);
        $this->assertEquals('required|url|starts_with:https://example.com', $rules['src']);
    }
}