<?php

namespace TestScenarioData;

class RequestTestScenarioMaxBlocksErrorHelper extends RequestTestScenarioDataHelper
{

    public function getRequestData(): array
    {
        return [
            'article' => [
                'version' => '2.22.2',
                'blocks' => [
                    [
                        'id' => '8dDU550iMy',
                        'type' => 'header',
                        'data' => [
                            'text' => 'Dit is een heading.',
                            'level' => 2
                        ]
                    ],
                    [
                        'id' => '8dDU550iMx',
                        'type' => 'header',
                        'data' => [
                            'text' => 'Dit is een heading.',
                            'level' => 3
                        ]
                    ],
                    [
                        'id' => '8dDU550iMw',
                        'type' => 'header',
                        'data' => [
                            'text' => 'Dit is een heading.',
                            'level' => 4
                        ]
                    ],
                    [
                        'id' => '8dDU550iMu',
                        'type' => 'header',
                        'data' => [
                            'text' => 'Dit is een heading.',
                            'level' => 5
                        ]
                    ],
                ],
                'time' => 1213123123123123
            ],
            'additional_field' => 42
        ];
    }

    public function getExpectedRules(): array
    {
        return [
            "article" => ["required", "array"],
            "article.version" => ["required", "in:2.22.2"],
            "article.blocks" => ["required", "array"],
            "article.blocks.*" => ["required", "array"],
            "article.blocks.*.type" => ["required", "string", "in:table,header,paragraph,image,audio,embed,list,columns"],
            "article.blocks.*.data" => ["required", "array"],
            "article.blocks.0.data.text" => "required|string|max:255",
            "article.blocks.0.data.level" => "required|integer|min:2|max:6",
            "article.blocks.1.data.text" => "required|string|max:255",
            "article.blocks.1.data.level" => "required|integer|min:2|max:6",
            "article.blocks.2.data.text" => "required|string|max:255",
            "article.blocks.2.data.level" => "required|integer|min:2|max:6",
            "article.blocks.3" => "missing",
            "article.blocks.3.data.text" => "required|string|max:255",
            "article.blocks.3.data.level" => "required|integer|min:2|max:6",
            "additional_field" => "required|integer|size:42"
        ];
    }

    public function getExpectedPossibleMessages(): array
    {
        return [
            "article.blocks.0.data.text.max" => "Headers for this article may not exceed 255 characters.",
            "article.blocks.1.data.text.max" => "Headers for this article may not exceed 255 characters.",
            "article.blocks.2.data.text.max" => "Headers for this article may not exceed 255 characters.",
            "article.blocks.3.data.text.max" => "Headers for this article may not exceed 255 characters.",
            "additional_field.required" => "The additional field is required.",
            "additional_field.integer" => "The additional field must be an integer.",
            "additional_field.size" => "The additional field must be 42, the answer of the universe.",
            "article.blocks.3.missing" => "The block of type 'header' may not occur more than 3 times. This is the 4th occurrence.",
        ];
    }

    public function getExpectedOutput(): array
    {
        return ["article.blocks.3" => ["The block of type 'header' may not occur more than 3 times. This is the 4th occurrence."]];
    }
}