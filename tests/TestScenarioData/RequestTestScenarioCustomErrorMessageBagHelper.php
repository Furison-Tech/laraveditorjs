<?php

namespace TestScenarioData;

class RequestTestScenarioCustomErrorMessageBagHelper extends RequestTestScenarioDataHelper
{

    public function getRequestData(): array
    {
        return [
            'article' => [
                'time' => 1730310036407,
                'version' => '2.22.2',
                'blocks' => [
                    [
                        'id' => '8dDU550iMy',
                        'type' => 'header',
                        'data' => [
                            'text' => 'Dit is een heading. Dit is een heading. Dit is een heading. Dit is een heading. Dit is een heading. Dit is een heading. Dit is een heading. Dit is een heading. Dit is een heading. Dit is een heading. Dit is een heading. Dit is een heading. Dit is een heading. Dit is een heading.',
                            'level' => 2
                        ]
                    ]
                ],
            ],
            'additional_field' => 43.5
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
            "article.blocks.*.id" => ['required', 'string', 'size:10'],
            "article.blocks.*.type" => ["required", "string", "in:table,header,paragraph,image,audio,embed,list,columns"],
            "article.blocks.*.data" => ["required", "array"],
            "article.blocks.0.data.level" => "required|integer|min:2|max:6",
            "article.blocks.0.data.text" => "required|string|max:255",
            "additional_field" => "required|integer|size:42"
        ];
    }

    public function getExpectedPossibleMessages(): array
    {
        return [
            "article.blocks.0.data.text.max" => "Headers for this article may not exceed 255 characters.",
            "additional_field.required" => "The additional field is required.",
            "additional_field.integer" => "The additional field must be an integer.",
            "additional_field.size" => "The additional field must be 42, the answer of the universe."
        ];
    }

    public function getExpectedOutput(): array
    {
        return [
            "article.blocks.0.data.text" => [
                "Headers for this article may not exceed 255 characters."
            ],
            "additional_field" => [
                "The additional field must be an integer.",
                "The additional field must be 42, the answer of the universe."
            ]
        ];
    }

    public function getExpectedHtmlValidationErrors(): array
    {
        return [];
    }
}