<?php

require_once __DIR__ . '/TestScenarioData/RequestTestScenarioDataHelper.php';
require_once __DIR__ . '/TestScenarioData/RequestTestScenarioHappyFlowHelper.php';
require_once __DIR__ . '/TestScenarioData/RequestTestScenarioCustomErrorMessageBagHelper.php';
require_once __DIR__ . '/TestScenarioData/RequestTestScenarioMaxBlocksErrorHelper.php';
require_once __DIR__ . '/TestScenarioData/RequestTestScenarioHtmlValidationErrorHelper.php';
require_once __DIR__ . '/TestScenarioData/RequestTestScenarioInvalidBlockTypeErrorHelper.php';
require_once __DIR__ . '/TestObjects/MockEditorJSFormRequest.php';

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Orchestra\Testbench\TestCase;
use TestScenarioData\RequestTestScenarioCustomErrorMessageBagHelper;
use TestScenarioData\RequestTestScenarioDataHelper;
use TestScenarioData\RequestTestScenarioHappyFlowHelper;
use TestScenarioData\RequestTestScenarioHtmlValidationErrorHelper;
use TestScenarioData\RequestTestScenarioInvalidBlockTypeErrorHelper;
use TestScenarioData\RequestTestScenarioMaxBlocksErrorHelper;

class EditorJSFormRequestTest extends TestCase
{

    public function testArticleRequestValidationHappyFlow()
    {
        $testScenarioDataHelper = new RequestTestScenarioHappyFlowHelper();
        $hydratedFormRequest = $testScenarioDataHelper->getHydratedFormRequest();

        $rules = $hydratedFormRequest->rules();
        $this->assertEquals($testScenarioDataHelper->getExpectedRules(), $rules);

        $messages = $hydratedFormRequest->messages();
        $this->assertEquals($testScenarioDataHelper->getExpectedPossibleMessages(), $messages);

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

        $this->assertEquals($testScenarioDataHelper->getExpectedHtmlValidationErrors(), $hydratedFormRequest->getAllHtmlValidationErrors());
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
        } catch (ValidationException $e) {
            $this->assertEquals($testScenarioDataHelper->getExpectedOutput(), $e->validator->errors()->toArray());
        }

        $this->assertEquals($testScenarioDataHelper->getExpectedHtmlValidationErrors(), $hydratedFormRequest->getAllHtmlValidationErrors());
    }

    public static function errorTestScenarioProvider(): array
    {
        return array(
            array(new RequestTestScenarioCustomErrorMessageBagHelper()),
            array(new RequestTestScenarioMaxBlocksErrorHelper()),
            array(new RequestTestScenarioHtmlValidationErrorHelper()),
            array(new RequestTestScenarioInvalidBlockTypeErrorHelper())
        );
    }
}