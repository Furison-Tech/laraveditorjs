<?php

namespace FurisonTech\LaraveditorJS;

use DOMDocument;
use Exception;
use FurisonTech\LaraveditorJS\Exceptions\InvalidHtmlException;

class ContentFormatConverter {
    /**
     * @throws InvalidHtmlException
     */
    public static function htmlToJson($htmlString, $allowList): array {
        $domDocument = new DOMDocument();
        libxml_use_internal_errors(true); // Suppress warnings for malformed HTML

        // Load the HTML string into the DOMDocument with a <template> wrapper
        $domDocument->loadHTML('<template>' . $htmlString . '</template>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        // The root node <template> wraps the provided HTML content
        $templateNode = $domDocument->getElementsByTagName('template')->item(0);

        if (!$templateNode) {
            throw new InvalidHtmlException("Failed to parse the HTML string.", $htmlString, $allowList);
        }

        // Process the children of the <template> node, but exclude the template tag itself from the output
        $jsonOutput = [];
        foreach ($templateNode->childNodes as $childNode) {
            $jsonOutput[] = self::domNodeToJson($htmlString, $childNode, $allowList);
        }

        // Flatten the result, remove nulls, and return
        return array_values(array_filter($jsonOutput));
    }

    /**
     * @throws InvalidHtmlException
     */
    private static function domNodeToJson($htmlString, $node, $allowList) {
        // Handle text nodes
        if ($node->nodeType == XML_TEXT_NODE) {
            $text = $node->nodeValue;
            return !empty($text) ? $text : null;
        }

        if ($node->nodeType == XML_ELEMENT_NODE) {
            $tagName = $node->nodeName;

            // Check if the tag is allowed in the allowList
            if (!isset($allowList[$tagName])) {
                throw new InvalidHtmlException("Tag <$tagName> is not allowed.", $htmlString, $allowList);
            }

            // Start building the element JSON structure
            $jsonArray = [
                'tag' => $tagName,
            ];

            $allowedAttributes = $allowList[$tagName]['attributes'] ?? [];
            $allowedStyles = $allowList[$tagName]['style'] ?? [];

            // Handle attributes
            $attributesArray = [];
            if ($node->hasAttributes()) {
                foreach ($node->attributes as $attribute) {
                    $attrName = $attribute->nodeName;

                    // Special handling for style attribute
                    if ($attrName === 'style' && !empty($allowedStyles)) {
                        $styleValue = $attribute->nodeValue;
                        $styleArray = self::validateAndExtractCssStyles($htmlString, $allowList, $styleValue, $allowedStyles);
                        if (!empty($styleArray)) {
                            $attributesArray['style'] = $styleArray;
                        }
                    } else {
                        // Check if the attribute is allowed
                        if (!in_array($attrName, $allowedAttributes)) {
                            throw new InvalidHtmlException("Attribute '$attrName' is not allowed in tag <$tagName>.", $htmlString, $allowList);
                        }
                        // Add the attribute to the JSON structure
                        $attributesArray[$attrName] = $attribute->nodeValue;
                    }
                }
            }

            if (!empty($attributesArray)) {
                $jsonArray['attributes'] = $attributesArray;
            }

            // Process child nodes (text, nested elements)
            $childrenArray = [];
            foreach ($node->childNodes as $childNode) {
                $childJson = self::domNodeToJson($htmlString, $childNode, $allowList);
                if ($childJson !== null) {
                    $childrenArray[] = $childJson;
                }
            }

            if (!empty($childrenArray)) {
                $jsonArray['children'] = $childrenArray;
            }

            return $jsonArray;
        }

        return null; // Ignore other node types (e.g., comments)
    }

    /**
     * Validate the styles in the 'style' attribute and return only the allowed ones.
     * @throws InvalidHtmlException
     */
    private static function validateAndExtractCssStyles($htmlString, $allowList, $styleString, $allowedStyles): array
    {
        $styles = [];
        $styleRules = explode(';', $styleString);

        foreach ($styleRules as $rule) {
            $rule = trim($rule);
            if (empty($rule) || !str_contains($rule, ':')) {
                continue;
            }

            list($property, $value) = explode(':', $rule, 2);
            $property = trim($property);
            $value = trim($value);

            // Check if the property is allowed
            if (isset($allowedStyles[$property])) {
                // Validate the value against the regex pattern
                $pattern = $allowedStyles[$property];
                if (preg_match('/' . $pattern . '/', $value)) {
                    $styles[$property] = $value;
                } else {
                    throw new InvalidHtmlException("Style property '$property' has an invalid value: '$value'.", $htmlString, $allowList);
                }
            } else {
                throw new InvalidHtmlException("Style property '$property' is not allowed.", $htmlString, $allowList);
            }
        }

        return $styles;
    }
}