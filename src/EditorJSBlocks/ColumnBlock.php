<?php

namespace FurisonTech\LaraveditorJS\EditorJSBlocks;

class ColumnBlock extends NestedBlock
{

    public function __construct(Block ...$BlockRulesSuppliers)
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