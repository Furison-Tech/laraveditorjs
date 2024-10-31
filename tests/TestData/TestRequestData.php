<?php

namespace TestData;

class TestRequestData
{
    public static function happyFlow_exampleRequest(): array
    {
        return [
            'article' => [
                'version' => '2.22.2',
                'blocks' => [
                    [
                        'id' => '8dDU550iMy',
                        'type' => 'header',
                        'data' => [
                            'text' => 'Dit is een <font style="color: rgb(236, 120, 120);">test</font> heading',
                            'level' => 2
                        ]
                    ],
                    [
                        'id' => 'giYxDG3DZx',
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Dit is een test paragraaf met een <a href=\"https://google.nl\"><font style=\"color: rgb(236, 120, 120);\">dikgedrukte</font></a> link&nbsp;',
                        ]
                    ],
                    [
                        'id' => 'lWBwJX0Q8j',
                        'type' => 'list',
                        'data' => [
                            'style' => 'ordered',
                            'items' => [
                                'Lijst item een',
                                'Lijst <a href=\"https://youtube.com\">item</a><b><i></i></b> twee',
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
                                    '<i><font style=\"color => rgb(156, 39, 176);\"><font style=\"color => rgb(236, 120, 120);\">Kolomn</font></font></i> 1',
                                    'Kolomn 2'
                                ],
                                [
                                    'Kolomn 3',
                                    '<b><i><font style=\"color => rgb(236, 120, 120);\">kolomn</font></i></b> 4'
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
                        'type' => 'audioPlayer',
                        'data' => [
                            'src' => 'https://file-examples.com/wp-content/storage/2017/11/file_example_MP3_700KB.mp3'
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
                    ]
                ],
                'time' => 1213123123123123
            ],
            'additional_field' => 42
        ];
    }

    public static function happyFlow_exampleRequest_htmlFree(): array
    {
        return [
            'article' => [
                'version' => '2.22.2',
                'blocks' => [
                    [
                        'id' => '8dDU550iMy',
                        'type' => 'header',
                        'data' => [
                            'text' => 'Dit is een <font style="color: rgb(236, 120, 120);">test</font> heading',
                            'level' => 2
                        ]
                    ],
                    [
                        'id' => 'giYxDG3DZx',
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Dit is een test paragraaf met een <a href=\"https://google.nl\"><font style=\"color: rgb(236, 120, 120);\">dikgedrukte</font></a> link&nbsp;',
                        ]
                    ],
                    [
                        'id' => 'lWBwJX0Q8j',
                        'type' => 'list',
                        'data' => [
                            'style' => 'ordered',
                            'items' => [
                                'Lijst item een',
                                'Lijst <a href=\"https://youtube.com\">item</a><b><i></i></b> twee',
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
                                    '<i><font style=\"color => rgb(156, 39, 176);\"><font style=\"color => rgb(236, 120, 120);\">Kolomn</font></font></i> 1',
                                    'Kolomn 2'
                                ],
                                [
                                    'Kolomn 3',
                                    '<b><i><font style=\"color => rgb(236, 120, 120);\">kolomn</font></i></b> 4'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => 'GHRiO1tLCD',
                        'type' => 'table',
                        'data' => [
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
                        ]
                    ],
                    [
                        'id' => 'GHRiO1tLCE',
                        'type' => 'audioPlayer',
                        'data' => [
                            'src' => 'https://file-examples.com/wp-content/storage/2017/11/file_example_MP3_700KB.mp3'
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
                    ]
                ],
                'time' => 1213123123123123
            ],
            'additional_field' => 42
        ];
    }

    public static function errorFlow_exampleRequest_maxBlocks(): array
    {
        return [
            'additional_field' => 42
        ];
    }

    public static function errorFlow_exampleRequest_unknownBlockType()
    {

    }

    public static function errorFlow_exampleRequest_customErrorMessages()
    {

    }
}