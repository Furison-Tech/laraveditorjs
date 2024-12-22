<?php

namespace FurisonTech\LaraveditorJS\BlockRulesSuppliers;

use FurisonTech\LaraveditorJS\EditorJSRequestFieldRuleBuilder;

abstract class NestedBlockRulesSupplier extends BlockRulesSupplier
{

    private EditorJSRequestFieldRuleBuilder $ruleBuilder;
    private array $nestedData = [];
    private string $nestedFieldKey;

    private array $allowedVersions = [];

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

    public function setAllowedVersions(array $allowedVersions): void
    {
        $this->allowedVersions = $allowedVersions;
    }

    final public function rules(): array
    {
        $rules = $this->miscDataFieldsRules();

        if ( isset($this->nestedData[$this->nestedFieldKey]) && is_array($this->nestedData[$this->nestedFieldKey]) ){
            //todo: also add required|array rule here for nested field and merge with any additional rules for this key
            // if any for that key are received from $this->miscDataFieldsRules();
            // also do not forget to explode on | if value for key is a string.

            for($i = 0; $i < count($this->nestedData[$this->nestedFieldKey]); $i++){
                $data = $this->nestedData[$this->nestedFieldKey][$i];
                $rules = array_merge($rules,
                    $this->ruleBuilder->buildRules("$this->nestedFieldKey.$i", $data, $this->allowedVersions)
                );
            }
        }

        return $rules;
    }

    final public function errorMessages(): array
    {
        $rules = $this->miscDataFieldsErrorMessages();

        if ( isset($this->nestedData[$this->nestedFieldKey]) && is_array($this->nestedData[$this->nestedFieldKey]) ){
            //todo: also add required|array rule here for nested field and merge with any additional rules for this key
            // if any for that key are received from $this->miscDataFieldsRules();
            // also do not forget to explode on | if value for key is a string.

            for($i = 0; $i < count($this->nestedData[$this->nestedFieldKey]); $i++){
                $data = $this->nestedData[$this->nestedFieldKey][$i];
                $rules = array_merge($rules, $this->ruleBuilder->buildMessages("$this->nestedFieldKey.$i", $data));
            }
        }

        return $rules;
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