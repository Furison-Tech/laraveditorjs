<?php

namespace BlockRulesSuppliersTests;

use FurisonTech\LaraveditorJS\BlockRulesSuppliers\ImageBlockRulesSupplier;
use PHPUnit\Framework\TestCase;

class ImageBlockRulesSupplierTest extends TestCase
{
    public function testGetRulesReturnsExpectedArray(): void
    {
        $supplier = new ImageBlockRulesSupplier(255, null,1);

        $rules = $supplier->rules();

        $this->assertIsArray($rules);
        $this->assertArrayHasKey('withBorder', $rules);
        $this->assertArrayHasKey('withBackground', $rules);
        $this->assertArrayHasKey('stretched', $rules);
        $this->assertArrayHasKey('file', $rules);
        $this->assertArrayHasKey('file.url', $rules);
        $this->assertArrayHasKey('caption', $rules);
        $this->assertEquals('sometimes|boolean', $rules['withBorder']);
        $this->assertEquals('sometimes|boolean', $rules['withBackground']);
        $this->assertEquals('sometimes|boolean', $rules['stretched']);
        $this->assertEquals('required|array', $rules['file']);
        $this->assertEquals('required|url', $rules['file.url']);
        $this->assertEquals('nullable|string|max:255', $rules['caption']);
    }

    public function testGetRulesReturnsExpectedArrayWhenUrlStartNotNull(): void
    {
        $supplier = new ImageBlockRulesSupplier(255, "https://example.com",1);

        $rules = $supplier->rules();

        $this->assertIsArray($rules);
        $this->assertArrayHasKey('withBorder', $rules);
        $this->assertArrayHasKey('withBackground', $rules);
        $this->assertArrayHasKey('stretched', $rules);
        $this->assertArrayHasKey('file', $rules);
        $this->assertArrayHasKey('file.url', $rules);
        $this->assertArrayHasKey('caption', $rules);
        $this->assertEquals('sometimes|boolean', $rules['withBorder']);
        $this->assertEquals('sometimes|boolean', $rules['withBackground']);
        $this->assertEquals('sometimes|boolean', $rules['stretched']);
        $this->assertEquals('required|array', $rules['file']);
        $this->assertEquals('required|url|starts_with:https://example.com', $rules['file.url']);
        $this->assertEquals('nullable|string|max:255', $rules['caption']);
    }
}