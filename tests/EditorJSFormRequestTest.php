<?php

require_once __DIR__ . '/TestData/RequestTestScenarioDataHelper.php';
require_once __DIR__ . '/TestData/RequestTestScenarioHappyFlowHtmlToJsonDataHelper.php';
require_once __DIR__ . '/TestDummies/DummyEditorJSFormRequest.php';

use Illuminate\Support\Facades\Validator;
use Orchestra\Testbench\TestCase;
use TestData\RequestTestScenarioHappyFlowHtmlToJsonDataHelper;

class EditorJSFormRequestTest extends TestCase
{

    //done: requestData voor: happyFlow (alle blocks 1x + additional field)
    //todo: error flow (1 voor maxBlocks, 1 voor custom error messages).

    //todo: gebruik aparte test case voor de closure van maxblocks.

    public function testRulesFunction()
    {
        $testScenarioDataHelper = new RequestTestScenarioHappyFlowHtmlToJsonDataHelper();
        $hydratedFormRequest = $testScenarioDataHelper->getHydratedFormRequest();

        $rules = $hydratedFormRequest->rules();
        $this->assertEquals($testScenarioDataHelper->getExpectedRules(), $rules);

        $messages = $hydratedFormRequest->messages();
        //$this->assertEquals($testScenarioDataHelper->getExpectedMessages(), $messages);

        $validator = Validator::make($testScenarioDataHelper->getRequestData(), $rules);
        $this->assertTrue($validator->passes());

        $testScenarioDataHelper->getHydratedFormRequest()->validateResolved();
        //todo: json validator/converter doet niet wat verwacht. Bij table columns blijft html html? (of is dit nog niet html convertable).

        $this->assertEquals(
            $testScenarioDataHelper->getExpectedOutput(),
            $hydratedFormRequest->getJsonizedData()
        );
    }
}