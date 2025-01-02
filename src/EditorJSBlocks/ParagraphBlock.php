<?php

namespace FurisonTech\LaraveditorJS\EditorJSBlocks;

class ParagraphBlock extends Block
{

    private int $maxLength;

    public function __construct(int $maxLength)
    {
        parent::__construct("paragraph");
        $this->maxLength = $maxLength;
    }

    public function rules(): array
    {
        return [
            'text' => 'required|string|max:' . $this->maxLength
        ];
    }

    public function errorMessages(): array
    {
        return [
            "text.max" => "Paragraphs for this article may not exceed {$this->maxLength} characters."
        ];
    }
}
