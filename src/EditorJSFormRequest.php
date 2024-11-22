<?php

namespace FurisonTech\LaraveditorJS;

use FurisonTech\LaraveditorJS\Rules\AllowedHtmlRule;
use Illuminate\Foundation\Http\FormRequest;

abstract class EditorJSFormRequest extends FormRequest
{

    protected bool $convertInlineHtmlToJson = true;

    private array $editorJSFieldRuleBuilders = [];

    /**
     * EditorJSFormRequest constructor.
     * @param array<String, EditorJSRequestFieldRuleBuilder> $editorJSFieldRuleBuilders
     */
    public function __construct(array $editorJSFieldRuleBuilders)
    {
        parent::__construct();
        $this->editorJSFieldRuleBuilders = $editorJSFieldRuleBuilders;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    final public function rules(): array
    {
        $rules = [];

        // Build rules for each Editor.js field
        foreach ($this->editorJSFieldRuleBuilders as $field => $builder) {
            $rules = array_merge($rules, $builder->buildRules($field, $this->input($field)));
        }

        // Merge with additional rules
        return array_merge($rules, $this->additionalRules());
    }

    /**
     * Additional validation rules.
     * @return array
     */
    protected function additionalRules(): array
    {
        return [];
    }

    final public function messages(): array
    {
        $messages = [];

        // Build custom error messages for each Editor.js field
        foreach ($this->editorJSFieldRuleBuilders as $field => $builder) {
            $messages = array_merge($messages, $builder->buildMessages($field, $this->input($field)));
        }

        // Merge with additional rules
        return array_merge($messages, $this->additionalMessages());
    }

    /**
     * Additional validation rules error messages.
     * @return array
     */
    protected function additionalMessages(): array
    {
        return [];
    }

    final protected function passedValidation(): void
    {
        if (!$this->convertInlineHtmlToJson){
            return;
        }

        $data = $this->validated();

        foreach ($this->editorJSFieldRuleBuilders as $builder) {
            foreach ($builder->getAllowedHtmlRules() as $ruleField => $allowedHtmlRule){
                /* @var $allowedHtmlRule AllowedHtmlRule */
                data_set($data, $ruleField, $allowedHtmlRule->getConvertedJson());
            }
        }

        $this->replace($data);

        $this->additionalPassedValidation();
    }

    protected function additionalPassedValidation(): void
    {

    }

    public function getAllHtmlValidationErrors(): array
    {
        $allErrors = [];
        foreach ($this->editorJSFieldRuleBuilders as $builder) {
            foreach ($builder->getAllowedHtmlRules() as $ruleField => $allowedHtmlRule){
                /* @var $allowedHtmlRule AllowedHtmlRule */
                $error = $allowedHtmlRule->getError();
                if ($error) $allErrors[$ruleField] = $error;
            }
        }
        return $allErrors;
    }

}

