<?php

namespace BlockRulesSuppliersTests;

use FurisonTech\LaraveditorJS\BlockRulesSuppliers\AudioBlockRulesSupplier;
use PHPUnit\Framework\TestCase;

final class AudioBlockRulesSupplierTest extends TestCase
{
    public function testGetRulesReturnsExpectedArray(): void
    {
        $supplier = new AudioBlockRulesSupplier(null);

        $rules = $supplier->rules();

        $this->assertArrayHasKey('file', $rules);
        $this->assertArrayHasKey('file.url', $rules);
        $this->assertArrayHasKey('canDownload', $rules);
        $this->assertEquals('sometimes|boolean', $rules['canDownload']);
        $this->assertEquals('required|array', $rules['file']);
        $this->assertEquals(['required', 'url'], $rules['file.url']);
    }

    public function testGetRulesReturnsExpectedArrayWhenUrlRegexNotNull(): void
    {
        $supplier = new AudioBlockRulesSupplier("https://example.com");

        $rules = $supplier->rules();

        $this->assertArrayHasKey('file', $rules);
        $this->assertArrayHasKey('file.url', $rules);
        $this->assertArrayHasKey('canDownload', $rules);
        $this->assertEquals('sometimes|boolean', $rules['canDownload']);
        $this->assertEquals('required|array', $rules['file']);
        $this->assertEquals(['required', 'url', 'regex:https://example.com'], $rules['file.url']);
    }
}