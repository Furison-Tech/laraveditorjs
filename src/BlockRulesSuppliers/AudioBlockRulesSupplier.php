<?php

namespace FurisonTech\LaraveditorJS\BlockRulesSuppliers;

class AudioBlockRulesSupplier extends BlockRulesSupplier
{
    private string|null $urlRegex;

    public function __construct(string|null $urlRegex)
    {
        parent::__construct("audio");
        $this->urlRegex = $urlRegex;
    }


    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        $fileUrlRules = ['required', 'url'];
        if ($this->urlRegex) {
            $fileUrlRules[] = 'regex:' . $this->urlRegex;
        }

        return [
            'canDownload' => 'sometimes|boolean',
            'file' => 'required|array',
            'file.url' => $fileUrlRules
        ];
    }

    public function errorMessages(): array
    {
        return [];
    }
}