# LaraveditorJS
**Handle your EditorJS data the Laravel way!**

> [!NOTE]  
> LaraveditorJS is a robust, heavily flexible & customizable package that was made with the purpose to make validating and rendering 
> EditorJS data so secured, that you can let any user on your site write articles/posts with it.
> Another goal of this package was to follow Laravel's detailed validation error feedback. 
> If you are looking for a simpler package that does not take as much configuration, you might want to look at:
> [Laravel-EditorJS](https://github.com/alaminfirdows/laravel-editorjs). 

## Features
This package aims to provide a familiar way to handle your EditorJS data.
You can validate your Requests including EditorJS data using the EditorJSFormRequest, which is an extension of Laravel's FormRequest.
With proper configuration, the EditorJSFormRequest will build the necessary validation rules depending on the content of your EditorJS data.
Any errors will be returned in the format that Laravel uses for validation errors. 
HTML can also be validated, without relying on HTMLpurifier. and even the HTML can be converted to JSON, to make the EditorJS content completely HTML free.
Rendering is straight forward. For basic functionality, rendering can be done by including 1 blade directive.

**TLDR**:
- Validate EditorJS data using an extension of Laravel's FormRequest
- Custom validation rules & error messages per block type.
- Maximum block count validation.
- Validate HTML without HTMLpurifier.
- Convert HTML to JSON.
- Render EditorJS data using a blade directive.

## Installation

You can install the package via composer:

```bash
composer require furison-tech/laraveditorjs
```

## Usage

### Basic Validation

To validate your EditorJS data, you need to extend the EditorJSFormRequest class.
Fortunately, this package provides a command to create a new class that extends EditorJSFormRequest.
```bash
php artisan make:editorjs-request
```
The command will prompt you for a name for the class. The class will be put in the app/Http/Requests directory.

Next, we need to define how the EditorJS data should be structured for this request.
In our request class, we can configure which fields are EditorJs fields, 
what kind of blocks they can have and optionally the maximum of how many occurrences per block type. 
Let's make an example with 1 EditorJS field. Both can have Headers, Paragraphs and Lists.
For both fields we want a maximum of 6 headers and 3 lists. There is no restriction on the amount of paragraphs.
The constructor of the class should look like this:
```php
    public function __construct()
    {
        $blockTypeMaxOccurences = [
            "header" => 6, //max 6 headers
            "list" => 3 //max 3 lists
        ];

        $fieldsMap = [
            "editorJsArticle" => new EditorJSRequestFieldRuleBuilder(
                $blockTypeMaxOccurences,
                new ParagraphBlock(2500), //max length of 2500 characters
                new ListBlock(25, 255), //max 25 items, each with a max length of 255 characters
                new HeaderBlock(255, 2, 6), //max length of 255 characters, H2 to h6 allowed.
                // H1 is not allowed, imagine we want to set the title in another normal field.
            )
            // If you want to use multiple editorJs fields (f.e. using EditorJS for rich product descriptions and product specifications)
            // you can add another field here in the same way.
        ];

        $allowedVersions = "2.29.1";

        parent::__construct($allowedVersions, $fieldsMap);
    }
```

Notice that we only allow headers with *level 2 to 6*. 
This is because we want to use **H1** for the title of the article, which we will set with another field.
We can use the request class to validate normal fields as well, exactly like Laravel normally does, just in another function.

```php
    protected function additionalRules(): array
    {
        return [
            'title' => 'required|string|max:255',
        ];
    }
```

Likewise, there is an `additionalMessages()` function that can be used to configure custom error messages for normal fields.

### HTML validation

> [!IMPORTANT]  
> HTML validation does not come by default! 
> If you want a block type to be validated, 
> you need to extend it, add & implement the necessary interface or write a custom block with it.

To give an example, we are going to validate inline HTML of that a paragraph block can contain.
Let's extend the ParagraphBlock class, and implement the HTMLContainable interface to only allow bold, italic, anchor and font tags with a custom color.

```php
class HtmlParagraphBlock extends ParagraphBlock implements HTMLContainable
{

    public function __construct(int $maxLength)
    {
        parent::__construct($maxLength);
    }

    public function allowedHtmlTagsAndAttributes(): array
    {
        return [
            'font' => [ //allow font tags with custom colors
                'style' => [ //style is a special attribute that can contain multiple properties.
                    'color' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$' //regex for the color property value format
                ]
            ],
            'a' => [ //allow anchor tags. of course anchor tags should have a href attribute.
                'attributes' => [
                    'href'
                ]
            ],
            'b' => [], //allow bold tags, but no attributes or styles
            'i' => [], //allow italic tags, but no attributes or styles
        ];
    }

    public function htmlableBlockDataFields(): array
    {
        return [
            'text' //the field under the block data field that contains the HTML
        ];
    }
}
```

Of course, you should not forget to replace the ParagraphBlock with the HtmlParagraphBlock in your EditorJS Request class.

To get the validated data of the request, you can use laravel's `validated()` method on the request object.
```php
    $validatedData = $request->validated();
```
However, this will keep the HTML in the data. If you wish to have the validated data, but with any HTML inside EditorJS data converted to JSON, you can use the `getValidatedDataArticlesJsonized()` method.
```php
    $json = $request->getValidatedDataArticlesJsonized();
```

Here is an example of what this conversion will look like. 
Let's suppose we have a paragraph block with a link and a bold & italic piece of text:

```php
  [
    "id" => "AoIg38PAdY",
    "type" => "paragraph",
    "data" => [
      "text" => "This is a paragraph with a <a href='https://google.nl'>google</a> <b><i>link</i></b>"
    ]
  ]
```

This will turn in to:

```php
  [
    "id" => "AoIg38PAdY",
    "type" => "paragraph",
    "data" => [
      "text" => [
        "This is a paragraph with a ",
        [
          "type" => "a",
          "text" => "google",
          "attributes" => [
            "href" => "https://google.nl"
          ]
        ],
        [
          "type" => "b",
          "text" => "",
          "attributes" => [],
          "tags" => [
            [
              "type" => "i",
              "text" => "",
              "attributes" => []
            ]
          ]
        ],
        " link"
      ]
    ]
  ]
```

### Rendering

Rendering the EditorJS data is very simple. For basic purposes, this takes just one blade directive.
> [!WARNING]  
> The given blade directives are not suitable for EditorJS data that has its inline HTML converted to JSON.
> Using this directive on jsonized block data will result in a rendering error.
> If you want to render jsonized block data, you will have to publish the views and edit them.
> The same applies if you want to render custom blocks.

```php
    @include('laraveditorjs::editorjs-post', ['blocks' => $article['blocks']])
```

You can publish the views if needed for editing with the following command.

```bash
php artisan vendor:publish --tag="laraveditorjs-views"
```

### Custom Blocks
Right now, the package comes with the following blocks for these elements:
- Header
- Paragraph
- List
- Table
- Embed (iframe)
- Audio
- Image
- Columns

If you want to use a block that is not included in the package, you can extend the Block class.
Fortunately, the package provides a command to create a new block class.
```bash
php artisan make:editorjs-block
```

The command will prompt you for a class name. Let's say we make a ```NarratorBlock```.
The class will be put in the ```app/Services/LaraveditorJS/EditorJSBlocks``` directory.
We will turn it in to a block similar to a paragraph block, but with a custom field called ```narrator``` that indicates which AI narrator voice can read the text (this is a fictional scenario).

```php
class NarratorBlock extends Block
{

    private int $maxLength;
    
    public function __construct(int $maxLength)
    {
        //this is fictional, for as far as I know there is no (custom) EditorJs tool that has this type of block (yet).
        parent::__construct("narrator-paragraph"); 
        $this->maxLength = $maxLength;
    }

    public function rules(): array
    {
        //set up Laravel's validation rules for this block.
        return [
           'narrator' => 'required|in:John,Paul,George,Sally', 
           'text' => 'required|string|max:' . $this->maxLength
        ];
    }

    public function errorMessages(): array
    {
        //set up custom error messages for this block's rules.
        return [
            'narrator.in' => 'The available AI narrator voices are John, Paul, George and Sally',
            'text.max' => "The text can't be longer than $this->maxLength characters"
        ];
    }
}
```

You can then include this block in your request class. 
For rendering this block, you need to publish the views and make a new view
``` narrator-paragraph.blade.php``` in the ```resources/views/vendor/laraveditorjs/blocks``` directory.
From there you can access the block data, like for example:

```html
    <div class="narrator-paragraph">
        <p>{{ $block['data']['text'] }}</p>
        <p>Narrated by: {{ $block['data']['narrator'] }}</p>
        <audio>
            <source src="/article/{{$id}}/link/to/audio">
        </audio>
    </div>
```

## Versioning
This package was made for laravel 10 & 11 and requires a minimum of PHP 8.1.