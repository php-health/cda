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
use PHPHealth\CDA\DataType\Identifier\InstanceIdentifier;
use PHPHealth\CDA\DataType\Code\LoincCode;
use PHPHealth\CDA\Elements\ConfidentialityCode;
use PHPHealth\CDA\DataType\Code\ConfidentialityCode as ConfidentialityCodeType;
use PHPHealth\CDA\Elements\Title;
use PHPHealth\CDA\Elements\EffectiveTime;
use PHPHealth\CDA\DataType\Quantity\DateAndTime\TimeStamp;
use PHPHealth\CDA\Elements\Id;
use PHPHealth\CDA\Elements\Code;
use PHPHealth\CDA\DataType\Name\PersonName;
use PHPHealth\CDA\DataType\Code\CodedValue;
use PHPHealth\CDA\RIM\Role\PatientRole;
use PHPHealth\CDA\DataType\Collection\Set;
use PHPHealth\CDA\RIM\Entity\Patient;
use PHPHealth\CDA\RIM\Participation\RecordTarget;

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
   
    public function testSimplifiedDocument()
    {
        // create the initial document
        $doc = new ClinicalDocument();
        $doc->setTitle(
                new Title(new CharacterString("Good Health Clinic Consultation Note"))
                );
        
        $clinicalElements = $doc->toDOMDocument()
                ->getElementsByTagName('ClinicalDocument');
        
        $clinicalElement = $clinicalElements->item(0);
        
        // create the expected document from XML string
        $expected = <<<'CDA'
<?xml version="1.0" encoding="UTF-8"?>
<ClinicalDocument xmlns="urn:hl7-org:v3" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:hl7-org:v3 CDA.xsd">
    <typeId root="2.16.840.1.113883.1.3" extension="POCD_HD000040"/>
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
        $doc->setTitle(new Title(new CharacterString("Good Health Clinic Consultation Note")));
        $doc->setEffectiveTime(new EffectiveTime(
                new TimeStamp(\DateTime::createFromFormat(\DateTime::ISO8601, 
                        "2014-08-27T01:43:12+0200"))));
        $doc->setId(new Id(new InstanceIdentifier("1.2.3.4", "https://mass.chill.pro")));
        $doc->setCode(new Code(LoincCode::create('42349-1', 'REASON FOR REFERRAL')));
        $doc->setConfidentialityCode(new ConfidentialityCode(ConfidentialityCodeType::create(ConfidentialityCodeType::RESTRICTED_KEY, ConfidentialityCodeType::RESTRICTED)));
        $doc->setRecordTarget($this->getRecordTarget());
        
        $nonXMLBody = new NonXMLBodyComponent();
        $nonXMLBody->setContent(new CharacterString("This is a narrative text"));
        $doc->getRootComponent()->addComponent($nonXMLBody);
        
        $clinicalElements = $doc->toDOMDocument()
                ->getElementsByTagName('ClinicalDocument');
        
        $clinicalElement = $clinicalElements->item(0);
        
        // create the expected document from XML string
        $expected = <<<'CDA'
<?xml version="1.0" encoding="UTF-8"?>
<ClinicalDocument xmlns="urn:hl7-org:v3" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:hl7-org:v3 CDA.xsd">
    <typeId root="2.16.840.1.113883.1.3" extension="POCD_HD000040"/>
    <id root="1.2.3.4" extension="https://mass.chill.pro" />
    <code code='42349-1' displayName='REASON FOR REFERRAL' codeSystem='2.16.840.1.113883.6.1' codeSystemName='LOINC'/>	
    <title>Good Health Clinic Consultation Note</title>
    <effectiveTime value="201408270143"/>
    <confidentialityCode code="R" displayName="Restricted" codeSystem="2.16.840.1.113883.5.25" codeSystemName="Confidentiality"/>
    <recordTarget>
        <patientRole>
            <id extension="12345" root="2.16.840.1.113883.19.5"/>
            <patient>
                <name>
                    <given>Henry</given>
                    <family>Levin</family>
                    <suffix>the 7th</suffix>
                </name>
                <administrativeGenderCode code="M" codeSystem="2.16.840.1.113883.5.1"/>
                <birthTime value="19320924"/>
            </patient>
        </patientRole>  
    </recordTarget>
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
        
        // save the doc for a further reading
        $doc->toDOMDocument()->save(sys_get_temp_dir().'/'.__METHOD__.'.xml');
            ;
        // tests
        $this->assertEquals(1, $clinicalElements->length, 
                "test that there is only one clinical document");
        $this->assertEqualXMLStructure(
                $expectedClinicalElement, $clinicalElement, true,
                "test the document is equal to expected");
    }
    
    
    
    
    protected function getRecordTarget()
    {
        $pr = new PatientRole($this->getPatientsIds(), $this->getPatient());
        
        return new RecordTarget($pr);
    }
    
    protected function getPatientsIds()
    {
        $set = new Set(InstanceIdentifier::class);
        $set->add(new InstanceIdentifier('2.16.840.1.113883.19.5', '12345'));
        
        return $set;
    }
    
    protected function getPatient()
    {
        $names = new Set(PersonName::class);
        $names->add((new PersonName())
                ->addPart(PersonName::FIRST_NAME, 'Henry')
                ->addPart(PersonName::LAST_NAME, 'Levin')
                ->addPart('suffix', 'the 7th'));
        $patient = new Patient(
                $names, 
                new TimeStamp(\DateTime::createFromFormat('Y-m-d', '1932-09-24')),
                new CodedValue('M', '', "2.16.840.1.113883.5.1", '')
                );
        
        return $patient;
    }
}
