<?php

namespace TestScenarioData;

use FurisonTech\LaraveditorJS\Exceptions\InvalidHtmlException;
use FurisonTech\LaraveditorJS\Rules\AllowedHtmlRule;

class RequestTestScenarioHtmlValidationErrorHelper extends RequestTestScenarioDataHelper
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
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Dit is een test <small>paragraaf</small> met een dikgedrukte link',
                        ]
                    ],
                    [
                        'id' => '8dDU550iMb',
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Dit is een test paragraaf met een <a onclick="window.location.href = \'https://malicioussite.com\'" href="https://google.nl"><span style="color: #FF00FF; opacity: 0">dikgedrukte</span></a> link',
                        ]
                    ],
                    [
                        'id' => '8dDU550iMc',
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Dit is een test paragraaf met een <span style="color: #FF00FF; opacity: 0">dikgedrukte</span> tekst',
                        ]
                    ],
                    [
                        'id' => '8dDU550iMd',
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Dit is een test paragraaf met een <span style="color: transparent;">dikgedrukte</span> tekst',
                        ]
                    ]
                ]
            ]
        ];
    }

    public function getExpectedRules(): array
    {
        $allowedHtmlRule = new AllowedHtmlRule([
            'span' => [
                'style' => [
                    'color' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$'
                ]
            ],
            'a' => [
                'attributes' => [
                    'href'
                ]
            ],
            'b' => [],
            'i' => [],
        ]);

        return [
            "article" => ["required", "array"],
            "article.time" => ["required", "integer"],
            "article.version" => ["required", "in:2.22.2"],
            "article.blocks" => ["required", "array"],
            "article.blocks.*" => ["required", "array"],
            "article.blocks.*.id" => ["required", "string", "size:10"],
            "article.blocks.*.type" => ["required", "string", "in:table,header,paragraph,image,audio,embed,list,columns"],
            "article.blocks.*.data" => ["required", "array"],
            "article.blocks.0.data.text" => ["required", "string", "max:2500", $allowedHtmlRule],
            "article.blocks.1.data.text" => ["required", "string", "max:2500", $allowedHtmlRule],
            "article.blocks.2.data.text" => ["required", "string", "max:2500", $allowedHtmlRule],
            "article.blocks.3.data.text" => ["required", "string", "max:2500", $allowedHtmlRule],
            "additional_field" => "required|integer|size:42"
        ];
    }

    public function getExpectedPossibleMessages(): array
    {
        return [
            "article.blocks.0.data.text.max" => "Paragraphs for this article may not exceed 2500 characters.",
            "article.blocks.1.data.text.max" => "Paragraphs for this article may not exceed 2500 characters.",
            "article.blocks.2.data.text.max" => "Paragraphs for this article may not exceed 2500 characters.",
            "article.blocks.3.data.text.max" => "Paragraphs for this article may not exceed 2500 characters.",
            "additional_field.required" => "The additional field is required.",
            "additional_field.integer" => "The additional field must be an integer.",
            "additional_field.size" => "The additional field must be 42, the answer of the universe.",
        ];
    }

    public function getExpectedOutput(): array
    {
        return [
            "article.blocks.0.data.text" => ["article.blocks.0.data.text contains invalid HTML"],
            "article.blocks.1.data.text" => ["article.blocks.1.data.text contains invalid HTML"],
            "article.blocks.2.data.text" => ["article.blocks.2.data.text contains invalid HTML"],
            "article.blocks.3.data.text" => ["article.blocks.3.data.text contains invalid HTML"],
            "additional_field" => ["The additional field is required."]
        ];
    }

    public function getExpectedHtmlValidationErrors(): array
    {
        $allowList = [
            'span' => [
                'style' => [
                    'color' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$'
                ]
            ],
            'a' => [
                'attributes' => [
                    'href'
                ]
            ],
            'b' => [],
            'i' => [],
        ];

        return [
            "article.blocks.0.data.text" => new InvalidHtmlException("Tag <small> is not allowed.", "Dit is een test <small>paragraaf</small> met een dikgedrukte link", $allowList),
            "article.blocks.1.data.text" => new InvalidHtmlException("Attribute 'onclick' is not allowed in tag <a>.", "Dit is een test paragraaf met een <a onclick=\"window.location.href = 'https://malicioussite.com'\" href=\"https://google.nl\"><span style=\"color: #FF00FF; opacity: 0\">dikgedrukte</span></a> link", $allowList),
            "article.blocks.2.data.text" => new InvalidHtmlException("Style property 'opacity' is not allowed.", "Dit is een test paragraaf met een <span style=\"color: #FF00FF; opacity: 0\">dikgedrukte</span> tekst", $allowList),
            "article.blocks.3.data.text" => new InvalidHtmlException("Style property 'color' has an invalid value: 'transparent'.", "Dit is een test paragraaf met een <span style=\"color: transparent;\">dikgedrukte</span> tekst", $allowList),
        ];
    }
}