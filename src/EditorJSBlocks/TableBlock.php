<?php

namespace FurisonTech\LaraveditorJS\EditorJSBlocks;

class TableBlock extends Block
{
    private int $maxRows;
    private int $maxColumns;
    private int $maxTextLength;

    public function __construct(int $maxRows, int $maxColumns, int $maxTextLength)
    {
        parent::__construct("table");
        $this->maxRows = $maxRows;
        $this->maxColumns = $maxColumns;
        $this->maxTextLength = $maxTextLength;
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'withHeadings' => 'required|boolean',
            'content' => 'required|array|max:' . $this->maxRows,
            'content.*' => 'required|array|max:' . $this->maxColumns,
            'content.*.*' => 'required|string|max:' . $this->maxTextLength,
        ];
    }

    public function errorMessages(): array
    {
        return [
            'content' => 'tables in this article may not exceed ' . $this->maxRows . ' rows.',
            'content.*' => 'tables in this article may not exceed ' . $this->maxColumns . ' columns.',
            'content.*.*' => 'table cells in this article may not exceed ' . $this->maxTextLength . ' characters.',
        ];
    }
}
