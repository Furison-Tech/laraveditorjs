<?php

namespace TestScenarioData;

use FurisonTech\LaraveditorJS\Rules\AllowedHtmlRule;

class RequestTestScenarioHappyFlowHelper extends RequestTestScenarioDataHelper
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
                            'text' => 'Dit is een test heading',
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
                                'Lijst item twee',
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
                                    '<b><i><span style="color: #00FFFF;">kolomn</span></i></b> 4'
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
                                    'version' => '2.22.2'
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
                                    'version' => '2.22.2'
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
            'article' => ['required', 'array'],
            'article.version' => ['required', 'in:2.22.2'],
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
                $allowedHtmlRule
            ],
            'article.blocks.2.data.style' => 'required|in:ordered,unordered',
            'article.blocks.2.data.items' => 'required|array|max:100',
            'article.blocks.2.data.items.*' => 'required|string|max:500',
            'article.blocks.3.data.withHeadings' => 'required|boolean',
            'article.blocks.3.data.content' => 'required|array|max:200',
            'article.blocks.3.data.content.*' => 'required|array|max:20',
            'article.blocks.3.data.content.*.*' => ['required', 'string', 'max:255', $allowedHtmlRule],
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
            'article.blocks.7.data.cols.0.version' => [
                'required',
                'in:2.22.2'
            ],
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
                $allowedHtmlRule
            ],
            'article.blocks.7.data.cols.0.blocks.1.data.style' => 'required|in:ordered,unordered',
            'article.blocks.7.data.cols.0.blocks.1.data.items' => 'required|array|max:100',
            'article.blocks.7.data.cols.0.blocks.1.data.items.*' => 'required|string|max:500',
            'article.blocks.7.data.cols.0.blocks.2.data.text' => [
                'required',
                'string',
                'max:2500',
                $allowedHtmlRule
            ],
            'article.blocks.7.data.cols.1' => [
                'required',
                'array'
            ],
            'article.blocks.7.data.cols.1.version' => [
                'required',
                'in:2.22.2'
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
                $allowedHtmlRule
            ],
            'additional_field' => 'required|integer|size:42',
            'article.blocks.7.data.cols.1.blocks.1.data.level' => 'required|integer|min:3|max:6',
            'article.blocks.7.data.cols.1.blocks.1.data.text' => 'required|string|max:255'
        ];
    }

    public function getExpectedPossibleMessages(): array
    {
        return [
            "article.blocks.0.data.text.max" => "Headers for this article may not exceed 255 characters.",
            "article.blocks.1.data.text.max" => "Paragraphs for this article may not exceed 2500 characters.",
            "article.blocks.2.data.items" => "lists in this article may not exceed 100 items.",
            "article.blocks.2.data.items.*" => "list items in this article may not exceed 500 characters.",
            "article.blocks.3.data.content" => "tables in this article may not exceed 200 rows.",
            "article.blocks.3.data.content.*" => "tables in this article may not exceed 20 columns.",
            "article.blocks.3.data.content.*.*" => "table cells in this article may not exceed 255 characters.",
            "article.blocks.4.data.caption" => "captions of images in this article may not exceed 255 characters.",
            "article.blocks.6.data.caption" => "captions of embedded content in this article may not exceed 255 characters.",
            "article.blocks.7.data.cols.0.blocks.0.data.text.max" => "Paragraphs for this article may not exceed 2500 characters.",
            "article.blocks.7.data.cols.0.blocks.1.data.items" => "lists in this article may not exceed 100 items.",
            "article.blocks.7.data.cols.0.blocks.1.data.items.*" => "list items in this article may not exceed 500 characters.",
            "article.blocks.7.data.cols.0.blocks.2.data.text.max" => "Paragraphs for this article may not exceed 2500 characters.",
            "article.blocks.7.data.cols.1.blocks.0.data.text.max" => "Paragraphs for this article may not exceed 2500 characters.",
            "article.blocks.7.data.cols.1.blocks.1.data.text.max" => "Headers for this article may not exceed 255 characters.",
            "additional_field.required" => "The additional field is required.",
            "additional_field.integer" => "The additional field must be an integer.",
            "additional_field.size" => "The additional field must be 42, the answer of the universe."
        ];
    }

    public function getExpectedOutput(): array
    {
        return [
            "article" => [
                'version' => '2.22.2',
                "blocks" => [
                    [
                        "data" => [
                            "level" => 2,
                            "text" => "Dit is een test heading"
                        ],
                        "type" => "header"
                    ],
                    [
                        "data" => [
                            "text" => "Dit is een test paragraaf met een <a href=\"https://google.nl\"><span style=\"color: #FF00FF;\">dikgedrukte</span></a> link"
                        ],
                        "type" => "paragraph"
                    ],
                    [
                        "data" => [
                            "style" => "ordered",
                            "items" => [
                                "Lijst item een",
                                "Lijst item twee",
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
                                    "<b><i><span style=\"color: #00FFFF;\">kolomn</span></i></b> 4"
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
                                    'version' => '2.22.2',
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
                                    'version' => '2.22.2',
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

    public function getExpectedJsonizedOutput(): array
    {
        return [
            "article" => [
                'version' => '2.22.2',
                "blocks" => [
                    [
                        "data" => [
                            "level" => 2,
                            "text" => "Dit is een test heading"
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
                                "Lijst item twee",
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
                                    [
                                        [
                                            "tag" => "i",
                                            "children" => [
                                                [
                                                    "tag" => "span",
                                                    "attributes" => [
                                                        "style" => [
                                                            "color" => "#0000FF"
                                                        ]
                                                    ],
                                                    "children" => [
                                                        [
                                                            "tag" => "span",
                                                            "attributes" => [
                                                                "style" => [
                                                                    "color" => "#FF0000"
                                                                ]
                                                            ],
                                                            "children" => [
                                                                "Kolomn"
                                                            ]
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ],
                                        " 1"
                                    ],
                                    [
                                        "Kolomn 2"
                                    ]
                                ],
                                [
                                    [
                                        "Kolomn 3"
                                    ],
                                    [
                                        [
                                            "tag" => "b",
                                            "children" => [
                                                [
                                                    "tag" => "i",
                                                    "children" => [
                                                        [
                                                            "tag" => "span",
                                                            "attributes" => [
                                                                "style" => [
                                                                    "color" => "#00FFFF"
                                                                ]
                                                            ],
                                                            "children" => [
                                                                "kolomn"
                                                            ]
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ],
                                        " 4"
                                    ]
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
                                    'version' => '2.22.2',
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
                                    'version' => '2.22.2',
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