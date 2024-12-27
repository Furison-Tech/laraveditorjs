<?php

namespace TestObjects;

use FurisonTech\LaraveditorJS\BlockRulesSuppliers\NestedBlockRulesSupplier;

class DummyNestedBlockRulesSupplier extends NestedBlockRulesSupplier
{
    public function __construct()
    {
        parent::__construct([], "dummy", "nested");
    }
}