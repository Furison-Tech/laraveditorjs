<?php

namespace TestScenarioData;

require_once __DIR__ . '/RequestTestScenarioJsonizableDataHelper.php';

use TestObjects\MockEditorJSFormRequestNoOverride;

class RequestTestScenarioHappyFlowNoAdditonalFieldHelper extends RequestTestScenarioJsonizableDataHelper
{

    public function __construct()
    {
        parent::__construct(new MockEditorJSFormRequestNoOverride());
    }

    public function getRequestData(): array
    {
        return [
            'article' => [
                'time' => 1730310036407,
                'version' => '2.22.2',
                'blocks' => [
                    [
                        'id' => '8dDU550iMa',
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Dit is een test paragraaf met een dikgedrukte link',
                        ]
                    ]
                ]
            ],
            'additional_unvalidated_field' => 42
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
            "article.blocks.*.type" => ["required", "string", "in:paragraph"],
            "article.blocks.*.data" => ["required", "array"],
            "article.blocks.0.data.text" => "required|string|max:2500"
        ];
    }

    public function getExpectedPossibleMessages(): array
    {
        return [
            "article.blocks.0.data.text.max" => "Paragraphs for this article may not exceed 2500 characters.",
        ];
    }

    public function getExpectedOutput(): array
    {
        return [
            'article' => [
                'time' => 1730310036407,
                'version' => '2.22.2',
                'blocks' => [
                    [
                        'id' => '8dDU550iMa',
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Dit is een test paragraaf met een dikgedrukte link',
                        ]
                    ]
                ]
            ]
        ];
    }

    public function getExpectedHtmlValidationErrors(): array
    {
        return [];
    }

    public function getExpectedJsonizedOutput(): array
    {
        return $this->getExpectedOutput();
    }
}