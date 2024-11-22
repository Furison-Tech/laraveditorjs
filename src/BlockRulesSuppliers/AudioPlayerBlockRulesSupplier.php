<?php

namespace FurisonTech\LaraveditorJS\BlockRulesSuppliers;

class AudioPlayerBlockRulesSupplier extends BlockRulesSupplier
{
    private string|null $urlStart;

    public function __construct(string|null $urlStart)
    {
        parent::__construct("audioPlayer");
        $this->urlStart = $urlStart;
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        $startsWith = $this->urlStart ? '|starts_with:' . $this->urlStart : '';
        return [
            'src' => 'required|url' . $startsWith,
        ];
    }

    public function errorMessages(): array
    {
        return [];
    }
}
