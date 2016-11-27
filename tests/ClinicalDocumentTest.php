<?php

/*
 * The MIT License
 *
 * Copyright 2016 Julien Fastré <julien.fastre@champs-libres.coop>.
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

namespace PHPHealth\CDA\Tests;

use PHPUnit\Framework\TestCase;
use PHPHealth\CDA\ClinicalDocument;
use PHPHealth\CDA\Component\NonXMLBodyComponent;
use PHPHealth\CDA\DataType\TextAndMultimedia\CharacterString;

/**
 * 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class ClinicalDocumentTest extends TestCase
{
    /*
     * Test that the ClinicalDocument class return a DOMDocument
     */
    public function testToDocument()
    {
        $doc = new ClinicalDocument();
        
        $dom = $doc->toDOMDocument();
        
        $this->assertInstanceOf(\DOMDocument::class, $dom);
    }
    
    public function testSimpleDocument()
    {
        // create the initial document
        $doc = new ClinicalDocument();
        $doc->setTitle("Good Health Clinic Consultation Note");
        
        $clinicalElements = $doc->toDOMDocument()
                ->getElementsByTagName('ClinicalDocument');
        
        $clinicalElement = $clinicalElements->item(0);
        
        // create the expected document from XML string
        $expected = <<<'CDA'
<?xml version="1.0" encoding="UTF-8"?>
<ClinicalDocument xmlns="urn:hl7-org:v3" templateId="2.16.840.1.113883.3.27.1776">
	<title>Good Health Clinic Consultation Note</title>
</ClinicalDocument>
CDA;
        $expectedDoc = new \DOMDocument('1.0');
        $expectedDoc->loadXML($expected);
        $expectedClinicalElement = $expectedDoc
                ->getElementsByTagName('ClinicalDocument')
                ->item(0);

        // tests
        $this->assertEquals(1, $clinicalElements->length, 
                "test that there is only one clinical document");
        $this->assertEqualXMLStructure(
                $expectedClinicalElement, $clinicalElement, true,
                "test the document is equal to expected");
        
        
        
        
    }
    
    public function testDocumentWithNonXMLBody()
    {
        // create the initial document
        $doc = new ClinicalDocument();
        $doc->setTitle("Good Health Clinic Consultation Note");
        
        $nonXMLBody = new NonXMLBodyComponent();
        $string = new CharacterString();
        $string->setContent("This is a narrative text");
        $nonXMLBody->setContent($string);
        $doc->getRootComponent()->addComponent($nonXMLBody);
        
        
        $clinicalElements = $doc->toDOMDocument()
                ->getElementsByTagName('ClinicalDocument');
        
        $clinicalElement = $clinicalElements->item(0);
        
        // create the expected document from XML string
        $expected = <<<'CDA'
<?xml version="1.0" encoding="UTF-8"?>
<ClinicalDocument xmlns="urn:hl7-org:v3" templateId="2.16.840.1.113883.3.27.1776">
	<title>Good Health Clinic Consultation Note</title>
        <component>
            <nonXMLBody>
                <text mediaType="text/plain"><![CDATA[
This is a narrative text
]]></text>
            </nonXMLBody>
        </component>
</ClinicalDocument>
CDA;
        $expectedDoc = new \DOMDocument('1.0');
        $expectedDoc->loadXML($expected);
        $expectedClinicalElement = $expectedDoc
                ->getElementsByTagName('ClinicalDocument')
                ->item(0);
        
        // tests
        $this->assertEquals(1, $clinicalElements->length, 
                "test that there is only one clinical document");
        $this->assertEqualXMLStructure(
                $expectedClinicalElement, $clinicalElement, true,
                "test the document is equal to expected");
        
        
        
        
    }
    
}
