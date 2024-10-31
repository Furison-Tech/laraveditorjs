<?php

namespace TestDummies;

use FurisonTech\LaraveditorJS\BlockRulesSuppliers\BlockRulesSupplier;

class DummyBlockRulesSupplier extends BlockRulesSupplier
{
    public function __construct(int|null $maxBlocks)
    {
        parent::__construct($maxBlocks);
    }


    public function getRules(): array
    {
        return [];
    }

    public function getRulesErrorMessages(): array
    {
        return [];
    }
}