<?php

require_once __DIR__ . '/TestData/TestRequestData.php';
require_once __DIR__ . '/TestDummies/DummyEditorJSFormRequest.php';

use Illuminate\Support\Facades\Validator;
use Orchestra\Testbench\TestCase;
use TestData\TestRequestData;
use TestDummies\DummyEditorJSFormRequest;

class EditorJSFormRequestTest extends TestCase
{

    //done: requestData voor: happyFlow (alle blocks 1x + additional field)
    //todo: error flow (1 voor maxBlocks, 1 voor custom error messages).

    //todo: gebruik aparte test case voor de closure van maxblocks.

    public function testRulesFunction()
    {
        // Mock request data
        $requestData = TestRequestData::happyFlow_exampleRequest();

        $formRequest = new DummyEditorJSFormRequest();
        $formRequest->setMethod('POST');
        $formRequest->request->add($requestData);

        $rules = $formRequest->rules();

        $expected = [
            'article' => ['required', 'array'],
            'article.blocks' => ['required', 'array'],
            'article.blocks.*' => ['required', 'array'],
            'article.blocks.*.type' => [
                'required',
                'string',
                'in:table,header,paragraph,image,audioPlayer,embed,list',
            ],
            'article.blocks.*.data' => ['required', 'array'],
            'article.blocks.0.data.level' => 'required|integer|min:2|max:6',
            'article.blocks.0.data.text' => 'required|string|max:255',
            'article.blocks.1.data.text' => 'required|string|max:2500',
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
            'article.blocks.4.data.file.url' => 'required|url',
            'article.blocks.4.data.caption' => 'nullable|string|max:255',
            'article.blocks.5.data.src' => 'required|url',
            'article.blocks.6.data.caption' => 'nullable|string|max:255',
            'article.blocks.6.data.service' => 'required|string|in:coub',
            'article.blocks.6.data.source' => ['required', 'url', 'regex:/https?:\/\/coub\.com\/view\/([^\/\?\&]+)/'],
            'article.blocks.6.data.embed' => ['required', 'url', 'regex:/https:\/\/coub\.com\/embed\/([^\/\?\&]+)/'],
            'article.blocks.6.data.width' => 'sometimes|integer|min:1',
            'article.blocks.6.data.height' => 'sometimes|integer|min:1',
            'additional_field' => 'required|integer|size:42',
        ];

        $this->assertEquals($expected, $rules);

        $validator = Validator::make($requestData, $rules);
        $this->assertTrue($validator->passes());
    }
}