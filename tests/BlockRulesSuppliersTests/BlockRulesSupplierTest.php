<?php

namespace BlockRulesSuppliersTests;

require_once __DIR__ . '/../TestDummies/DummyBlockRulesSupplier.php';

use PHPUnit\Framework\TestCase;
use TestDummies\DummyBlockRulesSupplier;

class BlockRulesSupplierTest extends TestCase
{
    public function testGetMaxBlocks(): void
    {
        $expectedMax = 42;
        $supplier = new DummyBlockRulesSupplier($expectedMax);

        $actualMax = $supplier->getMaxBlocks();

        $this->assertEquals($expectedMax, $actualMax);
    }

    public function testGetRulesReturnsExpectedArray(): void
    {
        $supplier = new DummyBlockRulesSupplier(1);

        $rules = $supplier->rules();

        $this->assertIsArray($rules);
        $this->assertEmpty($rules);
    }

    public function testGetRulesErrorMessagesReturnsExpectedArray(): void
    {
        $supplier = new DummyBlockRulesSupplier(1);

        $messages = $supplier->errorMessages();

        $this->assertIsArray($messages);
        $this->assertEmpty($messages);
    }
}