<?php

namespace TestData;

use FurisonTech\LaraveditorJS\EditorJSFormRequest;

abstract class RequestTestScenarioDataHelper
{
    private EditorJSFormRequest $formRequest;

    public function __construct(EditorJSFormRequest $formRequest)
    {
        $this->formRequest = $formRequest;
    }

    public abstract function getRequestData(): array;

    public abstract function getExpectedRules(): array;

    public abstract function getExpectedMessages(): array;

    public abstract function getExpectedOutput(): array;

    public final function getHydratedFormRequest(): EditorJSFormRequest
    {
        $formRequest = $this->formRequest;
        $formRequest->setContainer(app());
        $formRequest->setMethod('POST');
        $formRequest->replace($this->getRequestData());
        return $formRequest;
    }

}