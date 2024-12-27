<?php

use FurisonTech\LaraveditorJS\Exceptions\InvalidHtmlException;
use PHPUnit\Framework\TestCase;

class InvalidHtmlExceptionTest extends TestCase
{
    public function testInvalidHtmlExceptionGettersReturnExpectedResults(): void
    {
        $dummyInvalidHtmlException = new InvalidHtmlException("dummy exception", "<p>test</p>", []);

        $this->assertEquals("dummy exception", $dummyInvalidHtmlException->getMessage());
        $this->assertEquals("<p>test</p>", $dummyInvalidHtmlException->getInputHtmlString());
        $this->assertEquals([], $dummyInvalidHtmlException->getAllowList());
    }
}