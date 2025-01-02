<?php

namespace BlockRulesSuppliersTests;

require_once __DIR__ . '/../TestObjects/DummyBlock.php';

use PHPUnit\Framework\TestCase;
use TestObjects\DummyBlock;

class BlockRulesSupplierTest extends TestCase
{
    public function testGetBlockTypeReturnsBlock(): void
    {
        $supplier = new DummyBlock();

        $blockType = $supplier->getBlockType();

        $this->assertEquals("block", $blockType);
    }

    public function testGetRulesReturnsExpectedArray(): void
    {
        $supplier = new DummyBlock();

        $rules = $supplier->rules();

        $this->assertIsArray($rules);
        $this->assertEmpty($rules);
    }

    public function testGetRulesErrorMessagesReturnsExpectedArray(): void
    {
        $supplier = new DummyBlock();

        $messages = $supplier->errorMessages();

        $this->assertIsArray($messages);
        $this->assertEmpty($messages);
    }
}