<?php

namespace FurisonTech\LaraveditorJS\Rules;

use Closure;
use FurisonTech\LaraveditorJS\ContentFormatConverter;
use FurisonTech\LaraveditorJS\Exceptions\InvalidHtmlException;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedHtmlRule implements ValidationRule
{
    private array $allowList;
    private array $convertedJson = [];
    private InvalidHtmlException|null $error;

    public function __construct($allowList)
    {
        $this->allowList = $allowList;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $this->convertedJson[$attribute] = ContentFormatConverter::htmlToJson($value, $this->allowList);
        } catch (InvalidHtmlException $e) {
            $this->error = $e;
            $fail($attribute . ' contains invalid HTML');
        }
    }

    public function getError(): InvalidHtmlException|null
    {
        return $this->error;
    }

    public function getConvertedJson(): array
    {
        return $this->convertedJson;
    }
}