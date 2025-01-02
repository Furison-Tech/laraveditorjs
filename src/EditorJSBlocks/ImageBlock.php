<?php

namespace FurisonTech\LaraveditorJS\EditorJSBlocks;



class ImageBlock extends Block
{

    private int $maxCaptionLength;
    private string|null $urlRegex;

    public function __construct(int $maxCaptionLength, string|null $urlRegex)
    {
        parent::__construct("image");
        $this->maxCaptionLength = $maxCaptionLength;
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
            'withBorder' => 'sometimes|boolean',
            'withBackground' => 'sometimes|boolean',
            'stretched' => 'sometimes|boolean',
            'file' => 'required|array',
            'file.url' => $fileUrlRules,
            'caption' => 'nullable|string|max:' . $this->maxCaptionLength
        ];
    }

    public function errorMessages(): array
    {
        return [
            'caption' => 'captions of images in this article may not exceed ' . $this->maxCaptionLength. ' characters.',
        ];
    }
}
