<?php

namespace FurisonTech\LaraveditorJS\BlockRulesSuppliers;

abstract class NestedBlockRulesSupplier extends BlockRulesSupplier
{

    protected array $blockRuleSuppliers;
    private array $nestedData;

    public function __construct(array $blockRuleSuppliers)
    {
        $this->blockRuleSuppliers = $blockRuleSuppliers;
    }

    public function setNestedData(array $nestedData): void
    {
        $this->nestedData = $nestedData;
    }
    
    public function set()
    {
        
    }
}