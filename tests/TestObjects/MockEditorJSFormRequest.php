<?php

namespace TestObjects;

use FurisonTech\LaraveditorJS\EditorJSBlocks\AudioBlock;
use FurisonTech\LaraveditorJS\EditorJSBlocks\ColumnBlock;
use FurisonTech\LaraveditorJS\EditorJSBlocks\EmbedBlock;
use FurisonTech\LaraveditorJS\EditorJSBlocks\HeaderBlock;
use FurisonTech\LaraveditorJS\EditorJSBlocks\ImageBlock;
use FurisonTech\LaraveditorJS\EditorJSBlocks\ListBlock;
use FurisonTech\LaraveditorJS\EditorJSFormRequest;
use FurisonTech\LaraveditorJS\EditorJSRequestFieldRuleBuilder;
use FurisonTech\LaraveditorJS\Utils\EmbedServicesRegex;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

require_once __DIR__ . '/MockConvertableParagraphBlockRulesSupplier.php';
require_once __DIR__ . '/MockConvertableTableBlockRulesSupplier.php';

class MockEditorJSFormRequest extends EditorJSFormRequest
{

    public function __construct()
    {
        $embedServicesRegex = new EmbedServicesRegex();
        $embedRegexRules = $embedServicesRegex->getRegexRulesForServices(['coub']);

        $blockTypeMaxOccurences = [
            "header" => 3,
            "image" => 6,
            "audio" => 6,
            "embed" => 3,
            "table" => 3,
            "list" => 10,
            "columns" => 1
        ];

        $fieldRulesSuppliersMap = [
            "article" => new EditorJSRequestFieldRuleBuilder(
                $blockTypeMaxOccurences,
                new MockConvertableTableBlockRulesSupplier(200, 20, 255),
                new HeaderBlock(255, 2, 6),
                new MockConvertableParagraphBlockRulesSupplier(2500),
                new ImageBlock(255, null),
                new AudioBlock(null),
                new EmbedBlock($embedRegexRules, 255),
                new ListBlock(100, 500),
                new ColumnBlock(
                    new MockConvertableParagraphBlockRulesSupplier(2500),
                    new HeaderBlock(255, 3, 6),
                    new ListBlock(100, 500)
                )
            )
        ];

        $allowedVersions = "2.22.2";
        parent::__construct($allowedVersions, $fieldRulesSuppliersMap);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function passesAuthorization(): bool
    {
        return true;
    }

    protected function additionalRules(): array
    {
        return [
            "additional_field" => "required|integer|size:42"
        ];
    }

    protected function additionalMessages(): array
    {
        return [
            "additional_field.required" => "The additional field is required.",
            "additional_field.integer" => "The additional field must be an integer.",
            "additional_field.size" => "The additional field must be 42, the answer of the universe."
        ];
    }

    protected function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new ValidationException($validator);
    }
}