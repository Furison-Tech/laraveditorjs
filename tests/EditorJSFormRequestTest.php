<?php

require_once __DIR__ . '/TestScenarioData/RequestTestScenarioDataHelper.php';
require_once __DIR__ . '/TestScenarioData/RequestTestScenarioHappyFlowHelper.php';
require_once __DIR__ . '/TestScenarioData/RequestTestScenarioCustomErrorMessageBagHelper.php';
require_once __DIR__ . '/TestScenarioData/RequestTestScenarioMaxBlocksErrorHelper.php';
require_once __DIR__ . '/TestScenarioData/RequestTestScenarioHtmlValidationErrorHelper.php';
require_once __DIR__ . '/TestScenarioData/RequestTestScenarioInvalidBlockTypeErrorHelper.php';
require_once __DIR__ . '/TestScenarioData/RequestTestScenarioHappyFlowNoAdditonalFieldHelper.php';
require_once __DIR__ . '/TestScenarioData/RequestTestScenarioJsonizableDataHelper.php';
require_once __DIR__ . '/TestObjects/MockEditorJSFormRequest.php';
require_once __DIR__ . '/TestObjects/MockEditorJSFormRequestNoOverride.php';

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Orchestra\Testbench\TestCase;
use TestObjects\MockEditorJSFormRequestNoOverride;
use TestScenarioData\RequestTestScenarioCustomErrorMessageBagHelper;
use TestScenarioData\RequestTestScenarioDataHelper;
use TestScenarioData\RequestTestScenarioHappyFlowHelper;
use TestScenarioData\RequestTestScenarioHappyFlowNoAdditonalFieldHelper;
use TestScenarioData\RequestTestScenarioHtmlValidationErrorHelper;
use TestScenarioData\RequestTestScenarioInvalidBlockTypeErrorHelper;
use TestScenarioData\RequestTestScenarioJsonizableDataHelper;
use TestScenarioData\RequestTestScenarioMaxBlocksErrorHelper;

class EditorJSFormRequestTest extends TestCase
{

    /**
     * @dataProvider happyFlowTestScenarioProvider
     */
    public function testArticleRequestValidationHappyFlow(RequestTestScenarioJsonizableDataHelper $testScenarioDataHelper)
    {
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
            $hydratedFormRequest->getValidatedDataArticlesJsonized()
        );

        $this->assertEquals($testScenarioDataHelper->getExpectedHtmlValidationErrors(), $hydratedFormRequest->getAllHtmlValidationErrors());
    }

    public static function happyFlowTestScenarioProvider(): array
    {
        return array(
            array(new RequestTestScenarioHappyFlowHelper()),
            array(new RequestTestScenarioHappyFlowNoAdditonalFieldHelper()),
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

    public function testEditorJSFormRequestConstructor()
    {
        //this test to be honest is kinda lame, it just makes sure the EditorJSFormRequest class is instantiable
        $this->assertIsObject(new MockEditorJSFormRequestNoOverride());
    }
}