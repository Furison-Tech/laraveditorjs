<?php

namespace FurisonTech\LaraveditorJS\BlockRulesSuppliers;

abstract class BlockRulesSupplier
{
    private string $blockType;

    public function __construct(string $blockType)
    {
        $this->blockType = $blockType;
    }

    public function getBlockType(): string
    {
        return $this->blockType;
    }

    abstract public function rules(): array;

    abstract public function errorMessages(): array;

}
