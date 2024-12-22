<?php

use FurisonTech\LaraveditorJS\Utils\EmbedServicesRegex;
use PHPUnit\Framework\TestCase;

class EmbedServicesRegexTest extends TestCase
{

    public function testGetRegexRulesForAllServices()
    {
        $embedServicesRegex = new EmbedServicesRegex();
        $rules = $embedServicesRegex->getRegexRulesForServices();
        $this->assertIsArray($rules);
        $this->assertCount(19, $rules);
    }

    public function testGetRegexRulesForAllowedServices()
    {
        $embedServicesRegex = new EmbedServicesRegex();
        $rules = $embedServicesRegex->getRegexRulesForServices(['youtube', 'vimeo']);
        $this->assertIsArray($rules);
        $this->assertArrayHasKey('youtube', $rules);
        $this->assertArrayHasKey('vimeo', $rules);
        $this->assertCount(2, $rules);
    }

    public function testPutCustomServiceAddsService()
    {
        $embedServicesRegex = new EmbedServicesRegex();
        $embedServicesRegex->putCustomService('custom', '/custom-source/', '/custom-embed/');
        $rules = $embedServicesRegex->getRegexRulesForServices(['custom']);
        $this->assertArrayHasKey('custom', $rules);
        $this->assertCount(1, $rules);
        $this->assertEquals('/custom-source/', $rules['custom']['source']);
        $this->assertEquals('/custom-embed/', $rules['custom']['embed']);
    }

}