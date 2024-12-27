<?php

use FurisonTech\LaraveditorJS\ContentFormatConverter;
use PHPUnit\Framework\TestCase;

class HtmlToJsonConverterTest extends TestCase
{
    public function testHtmlToJson()
    {
        $htmlString = 'This is a <span style="color: #0F0"><a href="https://en.wikipedia.org/wiki/HTML">HTML</a> String</span>!';
        $allowList = [
            'span' => [
                'style' => [
                    'color' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$'
                ]
            ],
            'a' => [
                'attributes' => [
                    'href'
                ],
                'style' => [
                    'color' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$'
                ]
            ]
        ];

        $expected = [
            "This is a ",
            [
                "tag" => "span",
                "attributes" => [
                    "style" => [
                        'color' => '#0F0'
                    ]
                ],
                "children" => [
                    [
                        "tag" => "a",
                        "attributes" => [
                            "href" => "https://en.wikipedia.org/wiki/HTML"
                        ],
                        "children" => [
                            "HTML"
                        ]
                    ],
                    " String"
                ]
            ],
            "!"
        ];

        $result = ContentFormatConverter::htmlToJson($htmlString, $allowList);

        $this->assertEquals($expected, $result);
    }

    public function testHtmlToJsonWithNonTextOrElementNodeTypes()
    {
        $htmlString = '<p>this line <!-- has a comment -->!</p>';
        $allowList = [
            'p' => [],
        ];

        $expected = [
            [
                "tag" => "p",
                "children" => [
                    "this line ",
                    "!"
                ]
            ]
        ];

        $result = ContentFormatConverter::htmlToJson($htmlString, $allowList);

        $this->assertEquals($expected, $result);
    }
}