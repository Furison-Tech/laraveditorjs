<?php

namespace TestDummies;

use FurisonTech\LaraveditorJS\BlockRulesSuppliers\BlockRulesSupplier;

class DummyBlockRulesSupplier extends BlockRulesSupplier
{
    public function __construct()
    {
        parent::__construct("block");
    }

    public function rules(): array
    {
        return [];
    }

    public function errorMessages(): array
    {
        return [];
    }
}