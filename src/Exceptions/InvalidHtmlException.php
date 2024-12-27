<?php

namespace FurisonTech\LaraveditorJS\Exceptions;

use Exception;

class InvalidHtmlException extends Exception
{
    private string $inputHtmlString;
    private array $allowList;

    public function getAllowList(): array
    {
        return $this->allowList;
    }

    public function getInputHtmlString(): string
    {
        return $this->inputHtmlString;
    }


    public function __construct(string $message, string $inputHtmlString, array $allowList)
    {
        $this->inputHtmlString = $inputHtmlString;
        $this->allowList = $allowList;

        parent::__construct($message);
    }
}