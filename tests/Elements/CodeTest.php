<?php
/*
 * The MIT License
 *
 * Copyright 2016 julien.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace PHPHealth\CDA\Tests\TemplateCode;

use PHPHealth\CDA\Elements\Code;
use PHPHealth\CDA\DataType\Code\LoincCode;
use PHPUnit\Framework\TestCase;

/**
 * Test Loinc Code
 *
 * @author julien.fastre@champs-libres.coop
 */
class CodeTest extends TestCase
{
    public function testCode()
    {
        $code = new Code(LoincCode::create("57133-1", "REASON FOR REFERRAL"));
        
        $expected = <<<'XML'
<code code="57133-1" displayName="REASON FOR REFERRAL" codeSystem="2.16.840.1.113883.6.1" codeSystemName="LOINC" />
XML;
        $expectedDoc = new \DOMDocument('1.0');
        $expectedDoc->loadXML($expected);
        $expectedCode = $expectedDoc
                ->getElementsByTagName('code')
                ->item(0);
        
        $this->assertEqualXMLStructure($expectedCode, 
            $code->toDOMElement(new \DOMDocument()), true);
    }
}
