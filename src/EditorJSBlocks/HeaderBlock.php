<?php

namespace FurisonTech\LaraveditorJS\EditorJSBlocks;



class HeaderBlock extends Block
{

    private int $maxLength;
    private int $minLevel;
    private int $maxLevel;

    public function __construct(int $maxLength, int $minLevel, int $maxLevel)
    {
        parent::__construct("header");
        $this->maxLength = $maxLength;
        $this->minLevel = $minLevel;
        $this->maxLevel = $maxLevel;
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'level' => 'required|integer|min:'.$this->minLevel.'|max:'.$this->maxLevel,
            'text' => 'required|string|max:' . $this->maxLength
        ];
    }

    public function errorMessages(): array
    {
        return [
            "text.max" => "Headers for this article may not exceed {$this->maxLength} characters.",
        ];
    }
}
