<?php

namespace TestScenarioData;

use FurisonTech\LaraveditorJS\EditorJSFormRequest;
use TestObjects\MockEditorJSFormRequest;

abstract class RequestTestScenarioDataHelper
{
    private EditorJSFormRequest $formRequest;

    public function __construct($formRequest = new MockEditorJSFormRequest())
    {
        $this->formRequest = $formRequest;
    }

    public abstract function getRequestData(): array;

    public abstract function getExpectedRules(): array;

    public abstract function getExpectedPossibleMessages(): array;

    public abstract function getExpectedOutput(): array;

    public abstract function getExpectedHtmlValidationErrors(): array;

    public final function getHydratedFormRequest(): EditorJSFormRequest
    {
        $formRequest = $this->formRequest;
        $formRequest->setContainer(app());
        $formRequest->setMethod('POST');
        $formRequest->replace($this->getRequestData());
        return $formRequest;
    }

}