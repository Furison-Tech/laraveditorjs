<?php

namespace FurisonTech\LaraveditorJS\BlockRulesSuppliers;

class EmbedBlockRulesSupplier extends BlockRulesSupplier
{

    private int $maxCaptionLength;
    private array $allowedServices;

    public function __construct(array $allowedServices, int $maxCaptionLength, int|null $maxBlocks)
    {
        parent::__construct($maxBlocks);
        $this->maxCaptionLength = $maxCaptionLength;
        $this->allowedServices = $allowedServices;
    }

    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        return [
            'caption' => 'nullable|string|max:' . $this->maxCaptionLength,
            'service' => 'required|string|in:' . implode(',', array_keys($this->allowedServices)),
            'source' => ['required','url','regex:'.$this->collapseRegexes('source')],
            'embed' => ['required','url','regex:'.$this->collapseRegexes('embed')],
            'width' => 'sometimes|integer|min:1',
            'height' => 'sometimes|integer|min:1',
        ];
    }

    public function getRulesErrorMessages(): array
    {
        return [
            'caption' => 'captions of embedded content in this article may not exceed ' . $this->maxCaptionLength. ' characters.',
        ];
    }

    private function collapseRegexes($columnKey): string
    {
        return count($this->allowedServices) == 1 ? implode(array_column($this->allowedServices, $columnKey))
            : '('. implode('|', array_column($this->allowedServices, $columnKey)) .')';
    }
}
