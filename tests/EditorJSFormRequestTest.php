<?php

require_once __DIR__ . '/TestScenarioData/RequestTestScenarioDataHelper.php';
require_once __DIR__ . '/TestScenarioData/RequestTestScenarioHappyFlowHelper.php';
require_once __DIR__ . '/TestScenarioData/RequestTestScenarioCustomErrorMessageBagHelper.php';
require_once __DIR__ . '/TestScenarioData/RequestTestScenarioMaxBlocksErrorHelper.php';
require_once __DIR__ . '/TestObjects/MockEditorJSFormRequest.php';

use Illuminate\Support\Facades\Validator;
use Orchestra\Testbench\TestCase;
use TestScenarioData\RequestTestScenarioCustomErrorMessageBagHelper;
use TestScenarioData\RequestTestScenarioDataHelper;
use TestScenarioData\RequestTestScenarioHappyFlowHelper;
use TestScenarioData\RequestTestScenarioMaxBlocksErrorHelper;

class EditorJSFormRequestTest extends TestCase
{
    //todo: error flow voor html error

    public function testArticleRequestValidationHappyFlow()
    {
        $testScenarioDataHelper = new RequestTestScenarioHappyFlowHelper();
        $hydratedFormRequest = $testScenarioDataHelper->getHydratedFormRequest();

        $rules = $hydratedFormRequest->rules();
        $this->assertEquals($testScenarioDataHelper->getExpectedRules(), $rules);

        $messages = $hydratedFormRequest->messages();
        $this->assertEquals($testScenarioDataHelper->getExpectedPossibleMessages(), $messages);

        //todo: add validation rules for id type and id value.
        $validator = Validator::make($testScenarioDataHelper->getRequestData(), $rules);
        $this->assertTrue($validator->passes());

        $testScenarioDataHelper->getHydratedFormRequest()->validateResolved();

        $this->assertEquals(
            $testScenarioDataHelper->getExpectedOutput(),
            $hydratedFormRequest->validated()
        );

        $this->assertEquals(
            $testScenarioDataHelper->getExpectedJsonizedOutput(),
            $hydratedFormRequest->getJsonizedData()
        );
    }

    /**
     * @dataProvider errorTestScenarioProvider
     */
    public function testArticleRequestValidationErrorFlow(RequestTestScenarioDataHelper $testScenarioDataHelper)
    {
        $hydratedFormRequest = $testScenarioDataHelper->getHydratedFormRequest();

        $rules = $hydratedFormRequest->rules();
        $this->assertEquals($testScenarioDataHelper->getExpectedRules(), $rules);

        $messages = $hydratedFormRequest->messages();
        $this->assertEquals($testScenarioDataHelper->getExpectedPossibleMessages(), $messages);

        $validator = Validator::make($testScenarioDataHelper->getRequestData(), $rules);
        $this->assertFalse($validator->passes());

        try {
            $hydratedFormRequest->validateResolved();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertEquals(json_encode($testScenarioDataHelper->getExpectedOutput()), json_encode($e->validator->errors()->toArray()));
        }
    }

    public static function errorTestScenarioProvider(): array
    {
        return array(
            array(new RequestTestScenarioCustomErrorMessageBagHelper()),
            array(new RequestTestScenarioMaxBlocksErrorHelper())
        );
    }
}