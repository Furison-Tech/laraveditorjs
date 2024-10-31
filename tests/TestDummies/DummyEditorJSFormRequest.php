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

        parent::__construct(
            new EditorJSRequestFieldRuleBuilder('article', [
                'table' => new TableBlockRulesSupplier(200, 20, 255, 3),
                'header' => new HeaderBlockRulesSupplier(255, 2, 6,null),
                'paragraph' => new ParagraphBlockRulesSupplier(2500, null),
                'image' => new ImageBlockRulesSupplier(255, null,10),
                'audioPlayer' => new AudioPlayerBlockRulesSupplier(null, 12),
                'embed' => new EmbedBlockRulesSupplier($embedRegexRules, 255, 5),
                'list' => new ListBlockRulesSupplier(100, 500, null),
            ])
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