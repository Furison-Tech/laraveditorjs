<?php

namespace FurisonTech\LaraveditorJS\EditorJSBlocks;

class ListBlock extends Block
{

    private int $maxItemLength;
    private int $maxItems;

    public function __construct(int $maxItems, int $maxItemLength)
    {
        parent::__construct("list");
        $this->maxItems = $maxItems;
        $this->maxItemLength = $maxItemLength;
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'style' => 'required|in:ordered,unordered',
            'items' => 'required|array|max:' . $this->maxItems,
            'items.*' => 'required|string|max:' . $this->maxItemLength
        ];
    }

    public function errorMessages(): array
    {
        return [
            'items' => 'lists in this article may not exceed ' . $this->maxItems . ' items.',
            'items.*' => 'list items in this article may not exceed ' . $this->maxItemLength . ' characters.',
        ];
    }
}
