<?php

namespace TestDummies;

use FurisonTech\LaraveditorJS\BlockRulesSuppliers\AudioPlayerBlockRulesSupplier;
use FurisonTech\LaraveditorJS\BlockRulesSuppliers\EmbedBlockRulesSupplier;
use FurisonTech\LaraveditorJS\BlockRulesSuppliers\HeaderBlockRulesSupplier;
use FurisonTech\LaraveditorJS\BlockRulesSuppliers\ImageBlockRulesSupplier;
use FurisonTech\LaraveditorJS\BlockRulesSuppliers\ListBlockRulesSupplier;
use FurisonTech\LaraveditorJS\BlockRulesSuppliers\ParagraphBlockRulesSupplier;
use FurisonTech\LaraveditorJS\BlockRulesSuppliers\TableBlockRulesSupplier;
use FurisonTech\LaraveditorJS\EditorJSFormRequest;
use FurisonTech\LaraveditorJS\EditorJSRequestFieldRuleBuilder;
use FurisonTech\LaraveditorJS\Utils\EmbedServicesRegex;

class DummyEditorJSFormRequest extends EditorJSFormRequest
{
    //todo add code block, audio upload, and columns.


    public function __construct()
    {
        $embedServicesRegex = new EmbedServicesRegex();
        $embedRegexRules = $embedServicesRegex->getRegexRulesForServices(['coub']);

        parent::__construct([
                "article" => new EditorJSRequestFieldRuleBuilder(
                    new TableBlockRulesSupplier(200, 20, 255),
                    new HeaderBlockRulesSupplier(255, 2, 6),
                    new ParagraphBlockRulesSupplier(2500),
                    new ImageBlockRulesSupplier(255, null),
                    new AudioPlayerBlockRulesSupplier(null),
                    new EmbedBlockRulesSupplier($embedRegexRules, 255),
                    new ListBlockRulesSupplier(100, 500),
                )
            ]
        );
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
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
            "additional_field.size" => "The additional field must be 42."
        ];
    }
}