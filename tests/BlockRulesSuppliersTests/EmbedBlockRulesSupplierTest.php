<?php

namespace BlockRulesSuppliersTests;

use FurisonTech\LaraveditorJS\BlockRulesSuppliers\EmbedBlockRulesSupplier;
use FurisonTech\LaraveditorJS\Utils\EmbedServicesRegex;
use PHPUnit\Framework\TestCase;

final class EmbedBlockRulesSupplierTest extends TestCase
{
    public function testGetRulesReturnsExpectedArray()
    {
        $embedServicesRegex = new EmbedServicesRegex();
        $embedRegexRules = $embedServicesRegex->getRegexRulesForServices(['vimeo', 'youtube']);
        $supplier = new EmbedBlockRulesSupplier($embedRegexRules, 255, 10);

        $rules = $supplier->rules();

        $this->assertIsArray($rules);
        $this->assertArrayHasKey('caption', $rules);
        $this->assertArrayHasKey('service', $rules);
        $this->assertArrayHasKey('source', $rules);
        $this->assertArrayHasKey('embed', $rules);
        $this->assertArrayHasKey('width', $rules);
        $this->assertArrayHasKey('height', $rules);
        $this->assertEquals('nullable|string|max:255', $rules['caption']);
        $this->assertEquals('required|string|in:vimeo,youtube', $rules['service']);
        $this->assertEquals(['required', 'url', 'regex:(/(?:http[s]?:\/\/)?(?:www.)?(?:player.)?vimeo\.co(?:.+\/([^\/]\d+)(?:#t=[\d]+)?s?$)/|/(?:https?:\/\/)?(?:www\.)?(?:(?:youtu\.be\/)|(?:youtube\.com)\/(?:v\/|u\/\w\/|embed\/|watch))(?:(?:\?v=)?([^#&?=]*))?((?:[?&]\w*=\w*)*)/)'], $rules['source']);
        $this->assertEquals(['required', 'url', 'regex:(/https:\/\/player\.vimeo\.com\/video\/([^\/\?\&]+)\?title=0&byline=0/|/https:\/\/www\.youtube\.com\/embed\/([^\/\?\&]+)/)'], $rules['embed']);
        $this->assertEquals('sometimes|integer|min:1', $rules['width']);
        $this->assertEquals('sometimes|integer|min:1', $rules['height']);
    }
}