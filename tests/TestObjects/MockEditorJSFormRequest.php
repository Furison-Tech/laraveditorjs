<?php

namespace TestObjects;

use FurisonTech\LaraveditorJS\BlockRulesSuppliers\AudioBlockRulesSupplier;
use FurisonTech\LaraveditorJS\BlockRulesSuppliers\ColumnBlockRulesSupplier;
use FurisonTech\LaraveditorJS\BlockRulesSuppliers\EmbedBlockRulesSupplier;
use FurisonTech\LaraveditorJS\BlockRulesSuppliers\HeaderBlockRulesSupplier;
use FurisonTech\LaraveditorJS\BlockRulesSuppliers\ImageBlockRulesSupplier;
use FurisonTech\LaraveditorJS\BlockRulesSuppliers\ListBlockRulesSupplier;
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
                new HeaderBlockRulesSupplier(255, 2, 6),
                new MockConvertableParagraphBlockRulesSupplier(2500),
                new ImageBlockRulesSupplier(255, null),
                new AudioBlockRulesSupplier(null),
                new EmbedBlockRulesSupplier($embedRegexRules, 255),
                new ListBlockRulesSupplier(100, 500),
                new ColumnBlockRulesSupplier(
                    new MockConvertableParagraphBlockRulesSupplier(2500),
                    new HeaderBlockRulesSupplier(255, 3, 6),
                    new ListBlockRulesSupplier(100, 500)
                )
            )
        ];

        $allowedVersions = "2.22.2";
        parent::__construct($allowedVersions, $fieldRulesSuppliersMap);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
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