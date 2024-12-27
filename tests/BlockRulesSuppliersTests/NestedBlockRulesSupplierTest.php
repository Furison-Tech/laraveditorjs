<?php

namespace BlockRulesSuppliersTests;

require_once __DIR__ . '/../TestObjects/DummyNestedBlockRulesSupplier.php';

use PHPUnit\Framework\TestCase;
use TestObjects\DummyNestedBlockRulesSupplier;

class NestedBlockRulesSupplierTest extends TestCase
{
    public function testGetRulesReturnsExpectedArray(): void
    {
        $supplier = new DummyNestedBlockRulesSupplier();

        $this->assertEquals([], $supplier->rules());
        $this->assertEquals([], $supplier->errorMessages());
    }

}