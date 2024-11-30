<?php

namespace FurisonTech\LaraveditorJS;

use FurisonTech\LaraveditorJS\BlockRulesSuppliers\BlockRulesSupplier;
use FurisonTech\LaraveditorJS\BlockRulesSuppliers\NestedBlockRulesSupplier;
use FurisonTech\LaraveditorJS\Rules\AllowedHtmlRule;

class EditorJSRequestFieldRuleBuilder
{

    /**
     * @var array
     */
    private array $blockRuleSuppliers;
    private array $allowedHtmlRules = [];
    private array $blockTypeMaxes;

    /**
     * EditorJSRequestFieldRuleBuilder constructor.
     *
     * @param array $blockTypeMaxes
     * @param BlockRulesSupplier ...$BlockRulesSuppliers
     */
    public function __construct(array $blockTypeMaxes, BlockRulesSupplier ...$BlockRulesSuppliers)
    {
        foreach ($BlockRulesSuppliers as $blockRuleSupplier) {
            $this->blockRuleSuppliers[$blockRuleSupplier->getBlockType()] = $blockRuleSupplier;
        }

        $this->blockTypeMaxes = $blockTypeMaxes;
    }

    /**
     * Build validation rules for the Editor.js field.
     *
     * @param $field
     * @param $data
     * @return array
     */
    public function buildRules($field, $data): array
    {
        $rules = [];

        $blocks = $data['blocks'] ?? [];

        // Initial validation rules
        $rules["$field"] = ['required', 'array'];
        //todo: version
        $rules["$field.blocks"] = ['required', 'array'];
        $rules["$field.blocks.*"] = ['required', 'array'];

        $rules["$field.blocks.*.type"] = ['required', 'string', 'in:'.implode(',', array_keys($this->blockRuleSuppliers))];
        $rules["$field.blocks.*.data"] = ['required', 'array'];

        $blockTypeOccurrences = [];

        // Build dynamic rules based on blocks
        foreach ($blocks as $index => $block) {
            $blockType = $block['type'] ?? null;

            if (!$blockType || !isset($this->blockRuleSuppliers[$blockType])) {
                continue; // Invalid block type, will be caught by initial validation
            }

            /** @var BlockRulesSupplier $supplier */
            $supplier = $this->blockRuleSuppliers[$blockType];
            $blockTypeOccurrences[$blockType] = ($blockTypeOccurrences[$blockType] ?? 0) + 1;

            // Check max count if specified
            if ($this->occurrencesSurpassesThreshold($blockTypeOccurrences[$blockType], $this->blockTypeMaxes[$blockType]))
            {
                $rules["$field.blocks.$index"][] =
                    $this->failByTooManyOccurrences($blockType, $this->blockTypeMaxes[$blockType]);
            }

            if ($supplier instanceof NestedBlockRulesSupplier) {
                $supplier->setNestedData($block['data'] ?? []);
                $supplier->setCurrentBaseFieldKey("$field.blocks.$index.data");
            }

            //Get block specific rules
            $dataRules = $supplier->rules();

            foreach ($dataRules as $key => $rule) {
                $absoluteKey = "$field.blocks.$index.data.$key";

                if ($supplier instanceof ContentFormatConvertable &&
                    in_array($key, $supplier->htmlableBlockDataFields())) {

                    $rule = is_array($rule) ? $rule : explode('|', $rule);
                    $rule[] = $this->allowedHtmlRules[$absoluteKey] =
                        new AllowedHtmlRule($supplier->allowedHtmlTagsAndAttributes());
                }

                $rules[$absoluteKey] = $rule;
            }

        }

        return $rules;
    }

    /**
     * Build validation rules for the Editor.js field.
     *
     * @param $field
     * @param $data
     * @return array
     */
    public function buildMessages($field, $data): array
    {
        $rulesMessages = [];

        foreach ($data['blocks'] ?? [] as $index => $block) {
            $blockType = $block['type'] ?? null;

            if (!$blockType || !isset($this->blockRuleSuppliers[$blockType])) {
                continue;
            }

            /** @var BlockRulesSupplier $supplier */
            $supplier = $this->blockRuleSuppliers[$blockType];

            $dataRulesMessages = $supplier->errorMessages();

            if ($supplier instanceof NestedBlockRulesSupplier) {
                $supplier->setNestedData($block['data'] ?? []);
                $supplier->setCurrentBaseFieldKey("$field.blocks.$index.data");
            }

            foreach ($dataRulesMessages as $key => $message) {
                $rulesMessages["$field.blocks.$index.data.$key"] = $message;
            }
        }

        return $rulesMessages;
    }

    private function occurrencesSurpassesThreshold($occurrences, $max): bool
    {
        if ($max === null) {
            return false;
        }
        return $occurrences > $max;
    }

    private function failByTooManyOccurrences($blockType, $maxBlocks): callable
    {
        return function ($attribute, $value, $fail) use ($blockType, $maxBlocks) {
            $fail("The block of type '$blockType' may not appear more than $maxBlocks times.");
        };
    }

    public function getAllowedHtmlRules(): array
    {
        return $this->allowedHtmlRules;
    }
}
