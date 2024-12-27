<?php

namespace TestScenarioData;

class RequestTestScenarioInvalidBlockTypeErrorHelper extends RequestTestScenarioDataHelper
{

    public function getRequestData(): array
    {
        return [
            'article' => [
                'time' => 1730310036407,
                'version' => '2.22.2',
                'blocks' => [
                    [
                        'id' => '8dDU550iMa',
                        'type' => 'invalid',
                        'data' => [
                            'fake' => 'data'
                        ]
                    ]
                ]
            ],
            'additional_field' => 42
        ];
    }

    public function getExpectedRules(): array
    {
        return [
            "article" => ["required", "array"],
            "article.time" => ["required", "integer"],
            "article.version" => ["required", "in:2.22.2"],
            "article.blocks" => ["required", "array"],
            "article.blocks.*" => ["required", "array"],
            "article.blocks.*.id" => ["required", "string", "size:10"],
            "article.blocks.*.type" => ["required", "string", "in:table,header,paragraph,image,audio,embed,list,columns"],
            "article.blocks.*.data" => ["required", "array"],
            "additional_field" => "required|integer|size:42"
        ];
    }

    public function getExpectedPossibleMessages(): array
    {
        return [
            "additional_field.required" => "The additional field is required.",
            "additional_field.integer" => "The additional field must be an integer.",
            "additional_field.size" => "The additional field must be 42, the answer of the universe.",
        ];
    }

    public function getExpectedOutput(): array
    {
        return [
            "article.blocks.0.type" => [
                "The selected article.blocks.0.type is invalid."
            ]
        ];
    }

    public function getExpectedHtmlValidationErrors(): array
    {
        return [];
    }
}