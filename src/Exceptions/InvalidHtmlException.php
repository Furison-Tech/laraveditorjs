<?php

namespace FurisonTech\LaraveditorJS\Exceptions;

use Exception;

class InvalidHtmlException extends Exception
{
    public function __construct(string $message, string $inputHtmlString, array $allowList)
    {
        parent::__construct(json_encode([
            'message' => $message,
            'inputHtmlString' => $inputHtmlString,
            'allowList' => $allowList
        ]));
    }
}