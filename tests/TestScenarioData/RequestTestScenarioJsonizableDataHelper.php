<?php

namespace TestScenarioData;

use TestObjects\MockEditorJSFormRequest;

abstract class RequestTestScenarioJsonizableDataHelper extends RequestTestScenarioDataHelper
{
    public function __construct($formRequest = new MockEditorJSFormRequest())
    {
        parent::__construct($formRequest);
    }

    abstract public function getExpectedJsonizedOutput(): array;
}