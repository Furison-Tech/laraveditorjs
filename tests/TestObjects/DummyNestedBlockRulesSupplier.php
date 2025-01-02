<?php

namespace TestObjects;

use FurisonTech\LaraveditorJS\EditorJSBlocks\NestedBlock;

class DummyNestedBlockRulesSupplier extends NestedBlock
{
    public function __construct()
    {
        parent::__construct([], "dummy", "nested");
    }
}