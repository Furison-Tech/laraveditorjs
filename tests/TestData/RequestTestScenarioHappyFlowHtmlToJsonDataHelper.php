<?php

namespace TestData;

use FurisonTech\LaraveditorJS\Rules\AllowedHtmlRule;
use TestDummies\DummyEditorJSFormRequest;
use FurisonTech\LaraveditorJS\EditorJSFormRequest;

class RequestTestScenarioHappyFlowHtmlToJsonDataHelper extends RequestTestScenarioDataHelper
{
    public function __construct()
    {
        parent::__construct(new DummyEditorJSFormRequest());
    }

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
                            'text' => 'Dit is een <span style="color: #00FF00;">test</span> heading',
                            'level' => 2
                        ]
                    ],
                    [
                        'id' => 'giYxDG3DZx',
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Dit is een test paragraaf met een <a href="https://google.nl"><span style="color: #FF00FF;">dikgedrukte</span></a> link',
                        ]
                    ],
                    [
                        'id' => 'lWBwJX0Q8j',
                        'type' => 'list',
                        'data' => [
                            'style' => 'ordered',
                            'items' => [
                                'Lijst item een',
                                'Lijst <a href="https://youtube.com">item</a><b><i></i></b> twee',
                                'Lijst item drie'
                            ],
                        ]
                    ],
                    [
                        'id' => 'GHRiO1tLCC',
                        'type' => 'table',
                        'data' => [
                            'withHeadings' => true,
                            'content' => [
                                [
                                    '<i><span style="color: #0000FF;"><span style="color: #FF0000;">Kolomn</span></span></i> 1',
                                    'Kolomn 2'
                                ],
                                [
                                    'Kolomn 3',
                                    '<b><i><font style="color: #00FFFF;">kolomn</font></i></b> 4'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => 'GHRiO1tLCD',
                        'type' => 'image',
                        'data' => [
                            'file' => [
                                'url' => 'https://www.tesla.com/tesla_theme/assets/img/_vehicle_redesign/roadster_and_semi/roadster/hero.jpg'
                            ],
                            'caption' => 'Roadster // tesla.com',
                            'withBorder' => false,
                            'withBackground' => false,
                            'stretched' => true
                        ]
                    ],
                    [
                        'id' => 'GHRiO1tLCE',
                        'type' => 'audio',
                        'data' => [
                            'file' => [
                                'url' => 'https://www.tesla.com/tesla_theme/assets/img/_vehicle_redesign/roadster_and_semi/roadster/hero.mp3'
                            ],
                            'canDownload' => true
                        ]
                    ],
                    [
                        'type' => 'embed',
                        'data' => [
                            'service' => 'coub',
                            'source' => 'https://coub.com/view/1czcdf',
                            'embed' => 'https://coub.com/embed/1czcdf',
                            'width' => 580,
                            'height' => 320,
                            'caption' => 'My Life'
                        ]
                    ],
                    [
                        'id' => 'BVOoPd1qjD',
                        'type' => 'columns',
                        'data' => [
                            'cols' => [
                                [
                                    'time' => 1730030546507,
                                    'blocks' => [
                                        [
                                            'id' => 'p0Xe7vQePh',
                                            'type' => 'paragraph',
                                            'data' => [
                                                'text' => 'test'
                                            ]
                                        ],
                                        [
                                            'id' => 'RGowT2udzC',
                                            'type' => 'list',
                                            'data' => [
                                                'style' => 'ordered',
                                                'items' => ['Pwoah', 'test']
                                            ]
                                        ],
                                        [
                                            'id' => '8jm3Aejg3o',
                                            'type' => 'paragraph',
                                            'data' => ['text' => 'asdad']
                                        ]
                                    ],
                                    'version' => '2.29.1'
                                ],
                                [
                                    'time' => 1730030546507,
                                    'blocks' => [
                                        [
                                            'id' => 'eywNVBgCHa',
                                            'type' => 'paragraph',
                                            'data' => [
                                                'text' => 'test2'
                                            ]
                                        ],
                                        [
                                            'id' => '1yPmWMR9Gw',
                                            'type' => 'header',
                                            'data' => [
                                                'text' => 'bwoah',
                                                'level' => 3
                                            ]
                                        ]
                                    ],
                                    'version' => '2.29.1'
                                ]
                            ]
                        ]
                    ]
                ],
                'time' => 1213123123123123
            ],
            'additional_field' => 42
        ];
    }

    protected function getFormRequest(): EditorJSFormRequest
    {
        return new DummyEditorJSFormRequest();
    }

    public function getExpectedRules(): array
    {
        $allowedParagraphHtmlRule = new AllowedHtmlRule([
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
            'article' => ['required', 'array'],
            'article.blocks' => ['required', 'array'],
            'article.blocks.*' => ['required', 'array'],
            'article.blocks.*.type' => [
                'required',
                'string',
                'in:table,header,paragraph,image,audio,embed,list,columns',
            ],
            'article.blocks.*.data' => ['required', 'array'],
            'article.blocks.0.data.level' => 'required|integer|min:2|max:6',
            'article.blocks.0.data.text' => 'required|string|max:255',
            'article.blocks.1.data.text' => [
                'required',
                'string',
                'max:2500',
                $allowedParagraphHtmlRule
            ],
            'article.blocks.2.data.style' => 'required|in:ordered,unordered',
            'article.blocks.2.data.items' => 'required|array|max:100',
            'article.blocks.2.data.items.*' => 'required|string|max:500',
            'article.blocks.3.data.withHeadings' => 'required|boolean',
            'article.blocks.3.data.content' => 'required|array|max:200',
            'article.blocks.3.data.content.*' => 'required|array|max:20',
            'article.blocks.3.data.content.*.*' => 'required|string|max:255',
            'article.blocks.4.data.withBorder' => 'sometimes|boolean',
            'article.blocks.4.data.withBackground' => 'sometimes|boolean',
            'article.blocks.4.data.stretched' => 'sometimes|boolean',
            'article.blocks.4.data.file' => 'required|array',
            'article.blocks.4.data.file.url' => ['required', 'url'],
            'article.blocks.4.data.caption' => 'nullable|string|max:255',
            'article.blocks.5.data.canDownload' => 'sometimes|boolean',
            'article.blocks.5.data.file' => 'required|array',
            'article.blocks.5.data.file.url' => ['required', 'url'],
            'article.blocks.6.data.caption' => 'nullable|string|max:255',
            'article.blocks.6.data.service' => 'required|string|in:coub',
            'article.blocks.6.data.source' => ['required', 'url', 'regex:/https?:\/\/coub\.com\/view\/([^\/\?\&]+)/'],
            'article.blocks.6.data.embed' => ['required', 'url', 'regex:/https:\/\/coub\.com\/embed\/([^\/\?\&]+)/'],
            'article.blocks.6.data.width' => 'sometimes|integer|min:1',
            'article.blocks.6.data.height' => 'sometimes|integer|min:1',
            'article.blocks.7.data.cols.0' => [
                'required',
                'array'
            ],
            'article.blocks.7.data.cols.0.blocks' => [
                'required',
                'array'
            ],
            'article.blocks.7.data.cols.0.blocks.*' => [
                'required',
                'array'
            ],
            'article.blocks.7.data.cols.0.blocks.*.type' => [
                'required',
                'string',
                'in:paragraph,header,list'
            ],
            'article.blocks.7.data.cols.0.blocks.*.data' => [
                'required',
                'array'
            ],
            'article.blocks.7.data.cols.0.blocks.0.data.text' => [
                'required',
                'string',
                'max:2500',
                $allowedParagraphHtmlRule
            ],
            'article.blocks.7.data.cols.0.blocks.1.data.style' => 'required|in:ordered,unordered',
            'article.blocks.7.data.cols.0.blocks.1.data.items' => 'required|array|max:100',
            'article.blocks.7.data.cols.0.blocks.1.data.items.*' => 'required|string|max:500',
            'article.blocks.7.data.cols.0.blocks.2.data.text' => [
                'required',
                'string',
                'max:2500',
                $allowedParagraphHtmlRule
            ],
            'article.blocks.7.data.cols.1' => [
                'required',
                'array'
            ],
            'article.blocks.7.data.cols.1.blocks' => [
                'required',
                'array'
            ],
            'article.blocks.7.data.cols.1.blocks.*' => [
                'required',
                'array'
            ],
            'article.blocks.7.data.cols.1.blocks.*.type' => [
                'required',
                'string',
                'in:paragraph,header,list'
            ],
            'article.blocks.7.data.cols.1.blocks.*.data' => [
                'required',
                'array'
            ],
            'article.blocks.7.data.cols.1.blocks.0.data.text' => [
                'required',
                'string',
                'max:2500',
                $allowedParagraphHtmlRule
            ],
            'additional_field' => 'required|integer|size:42',
            'article.blocks.7.data.cols.1.blocks.1.data.level' => 'required|integer|min:3|max:6',
            'article.blocks.7.data.cols.1.blocks.1.data.text' => 'required|string|max:255'
        ];
    }

    public function getExpectedMessages(): array
    {
        return [];
    }

    public function getExpectedOutput(): array
    {
        return [
            "article" => [
                "blocks" => [
                    [
                        "data" => [
                            "level" => 2,
                            "text" => "Dit is een <span style=\"color: #00FF00;\">test</span> heading"
                        ],
                        "type" => "header"
                    ],
                    [
                        "data" => [
                            "text" => [
                                "Dit is een test paragraaf met een ",
                                [
                                    "tag" => "a",
                                    "attributes" => [
                                        "href" => "https://google.nl"
                                    ],
                                    "children" => [
                                        [
                                            "tag" => "span",
                                            "attributes" => [
                                                "style" => [
                                                    "color" => "#FF00FF"
                                                ]
                                            ],
                                            "children" => [
                                                "dikgedrukte"
                                            ]
                                        ]
                                    ]
                                ],
                                " link"
                            ]
                        ],
                        "type" => "paragraph"
                    ],
                    [
                        "data" => [
                            "style" => "ordered",
                            "items" => [
                                "Lijst item een",
                                "Lijst <a href=\"https://youtube.com\">item</a><b><i></i></b> twee",
                                "Lijst item drie"
                            ]
                        ],
                        "type" => "list"
                    ],
                    [
                        "data" => [
                            "withHeadings" => true,
                            "content" => [
                                [
                                    "<i><span style=\"color: #0000FF;\"><span style=\"color: #FF0000;\">Kolomn</span></span></i> 1",
                                    "Kolomn 2"
                                ],
                                [
                                    "Kolomn 3",
                                    "<b><i><font style=\"color: #00FFFF;\">kolomn</font></i></b> 4"
                                ]
                            ]
                        ],
                        "type" => "table"
                    ],
                    [
                        "data" => [
                            "withBorder" => false,
                            "withBackground" => false,
                            "stretched" => true,
                            "file" => [
                                "url" => "https://www.tesla.com/tesla_theme/assets/img/_vehicle_redesign/roadster_and_semi/roadster/hero.jpg"
                            ],
                            "caption" => "Roadster // tesla.com"
                        ],
                        "type" => "image"
                    ],
                    [
                        "data" => [
                            "canDownload" => true,
                            "file" => [
                                "url" => "https://www.tesla.com/tesla_theme/assets/img/_vehicle_redesign/roadster_and_semi/roadster/hero.mp3"
                            ]
                        ],
                        "type" => "audio"
                    ],
                    [
                        "data" => [
                            "caption" => "My Life",
                            "service" => "coub",
                            "source" => "https://coub.com/view/1czcdf",
                            "embed" => "https://coub.com/embed/1czcdf",
                            "width" => 580,
                            "height" => 320
                        ],
                        "type" => "embed"
                    ],
                    [
                        "data" => [
                            "cols" => [
                                [
                                    "blocks" => [
                                        [
                                            "data" => [
                                                "text" => "test"
                                            ],
                                            "type" => "paragraph"
                                        ],
                                        [
                                            "data" => [
                                                "style" => "ordered",
                                                "items" => [
                                                    "Pwoah",
                                                    "test"
                                                ]
                                            ],
                                            "type" => "list"
                                        ],
                                        [
                                            "data" => [
                                                "text" => "asdad"
                                            ],
                                            "type" => "paragraph"
                                        ]
                                    ]
                                ],
                                [
                                    "blocks" => [
                                        [
                                            "data" => [
                                                "text" => "test2"
                                            ],
                                            "type" => "paragraph"
                                        ],
                                        [
                                            "data" => [
                                                "level" => 3,
                                                "text" => "bwoah"
                                            ],
                                            "type" => "header"
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        "type" => "columns"
                    ]
                ]
            ],
            "additional_field" => 42
        ];
    }
}