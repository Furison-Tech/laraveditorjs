<?php

namespace FurisonTech\LaraveditorJS\BlockRulesSuppliers;

use FurisonTech\LaraveditorJS\EditorJSRequestFieldRuleBuilder;

class ColumnBlockRulesSupplier extends BlockRulesSupplier
{

    private EditorJSRequestFieldRuleBuilder $ruleBuilder;

    public function __construct(EditorJSRequestFieldRuleBuilder $ruleBuilder)
    {
        $this->ruleBuilder = $ruleBuilder;
    }


    public function rules(): array
    {
        $rules = [
            'cols' => 'required|array|between:1,6',
        ];
        return array_merge($rules, $this->ruleBuilder->buildRules());
    }

    public function errorMessages(): array
    {
        return [];
    }
}