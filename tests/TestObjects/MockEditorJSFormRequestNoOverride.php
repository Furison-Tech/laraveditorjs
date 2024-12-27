<?php

namespace TestObjects;

use FurisonTech\LaraveditorJS\EditorJSFormRequest;
use FurisonTech\LaraveditorJS\EditorJSRequestFieldRuleBuilder;

class MockEditorJSFormRequestNoOverride extends EditorJSFormRequest
{
    public function __construct()
    {
        $fieldRulesSuppliersMap = [
            "article" => new EditorJSRequestFieldRuleBuilder(
                [],
                new MockConvertableParagraphBlockRulesSupplier(2500),
            )
        ];

        $allowedVersions = "2.22.2";
        parent::__construct($allowedVersions, $fieldRulesSuppliersMap);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function passesAuthorization(): bool
    {
        return true;
    }
}