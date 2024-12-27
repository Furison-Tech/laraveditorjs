<?php

namespace FurisonTech\LaraveditorJS;

use FurisonTech\LaraveditorJS\BlockRulesSuppliers\BlockRulesSupplier;
use FurisonTech\LaraveditorJS\BlockRulesSuppliers\NestedBlockRulesSupplier;
use FurisonTech\LaraveditorJS\Rules\AllowedHtmlRule;
use Illuminate\Support\Number;

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
     * @param $allowedVersions
     * @return array
     */
    public function buildRules($field, $data, $allowedVersions): array
    {
        $rules = [];

        $blocks = $data['blocks'] ?? [];

        // Initial validation rules
        $rules["$field"] = ['required', 'array'];
        $rules["$field.time"] = ['required', "integer"];
        $rules["$field.version"] = ['required', "in:".implode(",", $allowedVersions)];
        $rules["$field.blocks"] = ['required', 'array'];
        $rules["$field.blocks.*"] = ['required', 'array'];

        $rules["$field.blocks.*.id"] = ['required', 'string', 'size:10'];
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
            if ($this->occurrencesSurpassesThreshold($blockTypeOccurrences[$blockType], $blockType))
            {
                $rules["$field.blocks.$index"] = "missing";
            }

            if ($supplier instanceof NestedBlockRulesSupplier) {
                $supplier->setNestedData($block['data'] ?? []);
                $supplier->setAllowedVersions($allowedVersions);
            }

            //Get block specific rules
            $dataRules = $supplier->rules();

            foreach ($dataRules as $key => $rule) {
                $absoluteKey = "$field.blocks.$index.data.$key";

                if ($supplier instanceof HTMLContainable &&
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
            $blockTypeOccurrences[$blockType] = ($blockTypeOccurrences[$blockType] ?? 0) + 1;

            if ($this->occurrencesSurpassesThreshold($blockTypeOccurrences[$blockType], $blockType))
            {
                $rulesMessages["$field.blocks.$index.missing"] =
                    $this->tooManyOccurrencesErrorString($blockType, $this->blockTypeMaxes[$blockType],
                        $blockTypeOccurrences[$blockType]);
            }

            $dataRulesMessages = $supplier->errorMessages();

            if ($supplier instanceof NestedBlockRulesSupplier) {
                $supplier->setNestedData($block['data'] ?? []);
            }

            foreach ($dataRulesMessages as $key => $message) {
                $rulesMessages["$field.blocks.$index.data.$key"] = $message;
            }
        }

        return $rulesMessages;
    }

    private function occurrencesSurpassesThreshold($occurrences, $blockType): bool
    {
        $max = $this->blockTypeMaxes[$blockType] ?? null;
        if ($max === null) {
            return false;
        }
        return $occurrences > $max;
    }

    private function tooManyOccurrencesErrorString($blockType, $maxBlocks, $occurrences): string
    {
        $occOrdinal = Number::ordinal($occurrences);
        return "The block of type '$blockType' may not occur more than $maxBlocks times. This is the $occOrdinal occurrence.";
    }

    public function getAllowedHtmlRules(): array
    {
        return $this->allowedHtmlRules;
    }
}
