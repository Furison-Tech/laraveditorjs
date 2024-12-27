<?php

namespace FurisonTech\LaraveditorJS\BlockRulesSuppliers;

class ColumnBlockRulesSupplier extends NestedBlockRulesSupplier
{

    public function __construct(BlockRulesSupplier ...$BlockRulesSuppliers)
    {
        parent::__construct($BlockRulesSuppliers ,'columns', 'cols');
    }

    protected function miscDataFieldsRules(): array
    {
        return [
            'cols' => 'required|array|min:2|max:3',
        ];
    }
}