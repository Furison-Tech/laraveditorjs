<?php

namespace FurisonTech\LaraveditorJS\BlockRulesSuppliers;

abstract class BlockRulesSupplier
{

    private int|null $maxBlocks;

    public function __construct($maxBlocks)
    {
        $this->maxBlocks = $maxBlocks;
    }

    /**
     * @return int|null
     */
    public function getMaxBlocks(): ?int
    {
        return $this->maxBlocks;
    }

    /**
     * Get validation rules for the block.
     *
     * @return array
     */
    abstract public function getRules(): array;

    /**
     * Get validation rules error messages for the block.
     *
     * @return array
     */
    abstract public function getRulesErrorMessages(): array;
}
