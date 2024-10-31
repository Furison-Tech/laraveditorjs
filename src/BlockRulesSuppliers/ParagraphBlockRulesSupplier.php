<?php

namespace FurisonTech\LaraveditorJS\BlockRulesSuppliers;



use FurisonTech\LaraveditorJS\ContentFormatConvertable;

class ParagraphBlockRulesSupplier extends BlockRulesSupplier implements ContentFormatConvertable
{

    private int $maxLength;

    public function __construct(int $maxLength, int|null $maxBlocks)
    {
        parent::__construct($maxBlocks);
        $this->maxLength = $maxLength;
    }

    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        return [
            'text' => 'required|string|max:' . $this->maxLength
        ];
    }

    public function getRulesErrorMessages(): array
    {
        return [
            "text.max" => "Paragraphs for this article may not exceed {$this->maxLength} characters."
        ];
    }

    public function allowedHtmlTagsAndAttributes(): array
    {
        return [
            'span' => [
                'style' => [
                    'color' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$'
                ]
            ],
            'a' => [
                'attributes' => [
                    'href'
                ]
            ],
            'b' => [],
            'i' => [],
        ];
    }

    public function htmlableBlockDataFields(): array
    {
        return [
            'text'
        ];
    }
}