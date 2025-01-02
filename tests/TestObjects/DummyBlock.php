<?php

namespace TestObjects;

use FurisonTech\LaraveditorJS\EditorJSBlocks\Block;

class DummyBlock extends Block
{
    public function __construct()
    {
        parent::__construct("block");
    }

    public function rules(): array
    {
        return [];
    }

    public function errorMessages(): array
    {
        return [];
    }
}