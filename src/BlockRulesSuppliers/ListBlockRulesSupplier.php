<?php

namespace FurisonTech\LaraveditorJS\BlockRulesSuppliers;

class ListBlockRulesSupplier extends BlockRulesSupplier
{

    private int $maxItemLength;
    private int $maxItems;

    public function __construct(int $maxItems, int $maxItemLength, int|null $maxBlocks)
    {
        parent::__construct($maxBlocks);
        $this->maxItems = $maxItems;
        $this->maxItemLength = $maxItemLength;
    }

    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        return [
            'style' => 'required|in:ordered,unordered',
            'items' => 'required|array|max:' . $this->maxItems,
            'items.*' => 'required|string|max:' . $this->maxItemLength
        ];
    }

    public function getRulesErrorMessages(): array
    {
        return [
            'items' => 'lists in this article may not exceed ' . $this->maxItems . ' items.',
            'items.*' => 'list items in this article may not exceed ' . $this->maxItemLength . ' characters.',
        ];
    }
}
