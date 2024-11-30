<?php

namespace FurisonTech\LaraveditorJS\BlockRulesSuppliers;

use FurisonTech\LaraveditorJS\EditorJSRequestFieldRuleBuilder;

abstract class NestedBlockRulesSupplier extends BlockRulesSupplier
{

    private EditorJSRequestFieldRuleBuilder $ruleBuilder;
    private array $nestedData;
    private string $currentBaseFieldKey;
    private string $nestedFieldKey;

    public function __construct(array $blockRuleSuppliers, $blockType, $nestedFieldKey)
    {
        parent::__construct($blockType);
        $this->ruleBuilder = new EditorJSRequestFieldRuleBuilder([], ...$blockRuleSuppliers);
        $this->nestedFieldKey = $nestedFieldKey;
    }

    public function setNestedData(array $nestedData): void
    {
        $this->nestedData = $nestedData;
    }

    public function setCurrentBaseFieldKey(string $currentBaseFieldKey): void
    {
        $this->currentBaseFieldKey = $currentBaseFieldKey;
    }

    final public function rules(): array
    {
        $result = $this->ruleBuilder->buildRules($this->currentBaseFieldKey.".$this->nestedFieldKey", $this->nestedData);
        return array_merge($result, $this->miscDataFieldsRules());
    }

    final public function errorMessages(): array
    {
        $result = $this->ruleBuilder->buildMessages($this->currentBaseFieldKey.".$this->nestedFieldKey", $this->nestedData);
        return array_merge($result, $this->miscDataFieldsErrorMessages());
    }

    protected function miscDataFieldsRules(): array
    {
        return [];
    }

    protected function miscDataFieldsErrorMessages(): array
    {
        return [];
    }
}