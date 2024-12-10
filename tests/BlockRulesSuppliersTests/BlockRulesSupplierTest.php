<?php

namespace BlockRulesSuppliersTests;

require_once __DIR__ . '/../TestDummies/DummyBlockRulesSupplier.php';

use PHPUnit\Framework\TestCase;
use TestDummies\DummyBlockRulesSupplier;

class BlockRulesSupplierTest extends TestCase
{
    public function testGetBlockTypeReturnsBlock(): void
    {
        $supplier = new DummyBlockRulesSupplier();

        $blockType = $supplier->getBlockType();

        $this->assertEquals("block", $blockType);
    }

    public function testGetRulesReturnsExpectedArray(): void
    {
        $supplier = new DummyBlockRulesSupplier();

        $rules = $supplier->rules();

        $this->assertIsArray($rules);
        $this->assertEmpty($rules);
    }

    public function testGetRulesErrorMessagesReturnsExpectedArray(): void
    {
        $supplier = new DummyBlockRulesSupplier();

        $messages = $supplier->errorMessages();

        $this->assertIsArray($messages);
        $this->assertEmpty($messages);
    }
}