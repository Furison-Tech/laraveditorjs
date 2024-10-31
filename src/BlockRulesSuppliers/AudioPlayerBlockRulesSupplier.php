<?php

namespace FurisonTech\LaraveditorJS\BlockRulesSuppliers;

class AudioPlayerBlockRulesSupplier extends BlockRulesSupplier
{
    private string|null $urlStart;

    public function __construct(string|null $urlStart, int|null $maxBlocks)
    {
        parent::__construct($maxBlocks);
        $this->urlStart = $urlStart;
    }

    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        $startsWith = $this->urlStart ? '|starts_with:' . $this->urlStart : '';
        return [
            'src' => 'required|url'.$startsWith,
        ];
    }

    public function getRulesErrorMessages(): array
    {
        return [];
    }
}
