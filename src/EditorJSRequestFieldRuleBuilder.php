<?php

namespace FurisonTech\LaraveditorJS;


use FurisonTech\LaraveditorJS\BlockRulesSuppliers\BlockRulesSupplier;
use FurisonTech\LaraveditorJS\Exceptions\InvalidHtmlException;
use FurisonTech\LaraveditorJS\Rules\AllowedHtmlRule;
use Illuminate\Foundation\Http\FormRequest;

class EditorJSRequestFieldRuleBuilder
{
    /**
     * @var string
     */
    private string $fieldName;

    /**
     * @var array
     */
    private array $blockRuleSuppliers;
    private array $allowedHtmlRules = [];

    /**
     * EditorJSRequestFieldRuleBuilder constructor.
     *
     * @param string $fieldName
     * @param array $blockRuleSuppliers Array of block types to [BlockRulesProviderInterface, max count]
     */
    public function __construct(string $fieldName, array $blockRuleSuppliers)
    {
        $this->fieldName = $fieldName;
        $this->blockRuleSuppliers = $blockRuleSuppliers;
    }

    /**
     * Build validation rules for the Editor.js field.
     *
     * @param FormRequest $request
     * @return array
     */
    public function buildRules(FormRequest $request): array
    {
        $rules = [];

        $field = $this->fieldName;
        $blocks = $request->input("$field.blocks", []);

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
            if ($this->occurrencesSurpassesThreshold($blockTypeOccurrences[$blockType], $supplier->getMaxBlocks())) {
                $rules["$field.blocks.$index"][] =
                    $this->failByTooManyOccurrences($blockType, $supplier->getMaxBlocks());
            }

            //Get block specific rules
            $dataRules = $supplier->getRules();

            foreach ($dataRules as $key => $rule) {
                $absoluteKey = "$field.blocks.$index.data.$key";

                if ($supplier instanceof ContentFormatConvertable &&
                    in_array($absoluteKey, $supplier->htmlableBlockDataFields())) {

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
     * @param FormRequest $request
     * @return array
     */
    public function buildMessages(FormRequest $request): array
    {
        $rulesMessages = [];
        $field = $this->fieldName;

        foreach ($request->input("$field.blocks", []) as $index => $block) {
            $blockType = $block['type'] ?? null;

            if (!$blockType || !isset($this->blockRuleSuppliers[$blockType])) {
                continue;
            }

            /** @var BlockRulesSupplier $supplier */
            $supplier = $this->blockRuleSuppliers[$blockType];

            $dataRulesMessages = $supplier->getRulesErrorMessages();

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
