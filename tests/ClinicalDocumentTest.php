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
use PHPHealth\CDA\RIM\Participation\Author;
use PHPHealth\CDA\RIM\Role\AssignedAuthor;
use PHPHealth\CDA\RIM\Entity\AssignedPerson;
use PHPHealth\CDA\RIM\Entity\RepresentedCustodianOrganization;
use PHPHealth\CDA\RIM\Role\AssignedCustodian;
use PHPHealth\CDA\RIM\Participation\Custodian;
use PHPHealth\CDA\DataType\Name\EntityName;
use PHPHealth\CDA\Component\SingleComponent\Section;
use PHPHealth\CDA\Component\SingleComponent;
use PHPHealth\CDA\Component\XMLBodyComponent;
use PHPHealth\CDA\RIM\Act\SubstanceAdministration;
use PHPHealth\CDA\DataType\Collection\Interval\PeriodicIntervalOfTime;
use PHPHealth\CDA\DataType\Code\SnomedCTCode;
use PHPHealth\CDA\DataType\Quantity\PhysicalQuantity\PhysicalQuantity;
use PHPHealth\CDA\RIM\Participation\Consumable;
use PHPHealth\CDA\RIM\Role\ManufacturedProduct;
use PHPHealth\CDA\RIM\Entity\ManufacturedLabeledDrug;
use PHPHealth\CDA\RIM\Act\Observation;
use PHPHealth\CDA\DataType\Code\CodedSimple;
use PHPHealth\CDA\DataType\TextAndMultimedia\NarrativeString;
use PHPHealth\CDA\DataType\Collection\Interval\IntervalOfTime;
use PHPHealth\CDA\DataType\Code\StatusCode;


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
        $doc->setAuthor(new Author(
            new TimeStamp(\DateTime::createFromFormat('Y-m-d-H:i', "2000-04-07-14:00")), 
            $this->getAssignedAuthor()
            ));
        $doc->setCustodian($this->getCustodian());
        
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
    <recordTarget typeCode="RCT">
        <patientRole classCode="PAT">
            <id extension="12345" root="2.16.840.1.113883.19.5"/>
            <patient classCode="PSN">
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
    <author typeCode="AUT">
        <time value="2000040714"/>
        <assignedAuthor classCode="ASSIGNED">
            <id extension="KP00017" root="2.16.840.1.113883.19.5"/>
            <assignedPerson classCode="PSN">
                <name>
                    <given>Robert</given>
                    <family>Dolin</family>
                    <suffix>MD</suffix>
                </name>
            </assignedPerson>
        </assignedAuthor>
    </author>
    <custodian typeCode="CST">
 	    <assignedCustodian classCode="ASSIGNED">
 	      <representedCustodianOrganization classCode="ORG">
	        <id root="82112744-ea24-11e6-95be-17f96f76d55c"/>
 	        <name>ABRUMET asbl</name>
 	      </representedCustodianOrganization>
 	    </assignedCustodian>
 	</custodian>
    <component>
        <nonXMLBody>
            <text><![CDATA[
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
    
    public function testDocumentWithXMLStructuredBody()
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
        $doc->setAuthor(new Author(
            new TimeStamp(\DateTime::createFromFormat('Y-m-d-H:i', "2000-04-07-14:00")), 
            $this->getAssignedAuthor()
            ));
        $doc->setCustodian($this->getCustodian());
        
        $body = Component\XMLBodyComponentTest::getBody();
        $doc->getRootComponent()->addComponent($body);
        
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
    <recordTarget typeCode="RCT">
        <patientRole classCode="PAT">
            <id extension="12345" root="2.16.840.1.113883.19.5"/>
            <patient classCode="PSN">
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
    <author typeCode="AUT">
        <time value="2000040714"/>
        <assignedAuthor classCode="ASSIGNED">
            <id extension="KP00017" root="2.16.840.1.113883.19.5"/>
            <assignedPerson classCode="PSN">
                <name>
                    <given>Robert</given>
                    <family>Dolin</family>
                    <suffix>MD</suffix>
                </name>
            </assignedPerson>
        </assignedAuthor>
    </author>
    <custodian typeCode="CST">
 	    <assignedCustodian classCode="ASSIGNED">
 	      <representedCustodianOrganization classCode="ORG">
	        <id root="82112744-ea24-11e6-95be-17f96f76d55c"/>
 	        <name>ABRUMET asbl</name>
 	      </representedCustodianOrganization>
 	    </assignedCustodian>
 	</custodian>
    <component>
    <structuredBody classCode="DOCBODY">
      <component typeCode="COMP">
        <section classCode="DOCSECT">
          <templateId root="1.3.6.1.4.1.19376.1.5.3.1.3.1"/>
          <id root="430ADCD7-4481-DC0F-181D-2398F930B220"/>
          <code code="42349-1" codeSystem="2.16.840.1.113883.6.1" codeSystemName="LOINC" displayName="REASON FOR REFERRAL"/>
          <title>Reason for referral</title>
          <text>Robert Hunter is a patient.</text>
        </section>
      </component>
    </structuredBody>
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
    
    public function testAbrumetReferralSummary()
    {
        $expected = <<<CDA
<?xml version="1.0" encoding="UTF-8"?>
<ClinicalDocument xmlns="urn:hl7-org:v3" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:hl7-org:v3 CDA.xsd">
    <typeId root="2.16.840.1.113883.1.3" extension="POCD_HD000040"/>
    <templateId root="1.3.6.1.4.1.19376.1.5.3.1.1.3" />
    <id root="1.2.3.4" extension="https://mass.chill.pro" />
    <code code='42349-1' displayName='REASON FOR REFERRAL' codeSystem='2.16.840.1.113883.6.1' codeSystemName='LOINC'/>	
    <title>Good Health Clinic Consultation Note</title>
    <effectiveTime value="201408270143"/>
    <confidentialityCode code="R" displayName="Restricted" codeSystem="2.16.840.1.113883.5.25" codeSystemName="Confidentiality"/>
    <languageCode code="fr-FR"/>
    <recordTarget typeCode="RCT">
        <patientRole classCode="PAT">
            <id extension="12345" root="2.16.840.1.113883.19.5"/>
            <patient classCode="PSN">
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
    <author typeCode="AUT">
        <time value="2000040714"/>
        <assignedAuthor classCode="ASSIGNED">
            <id extension="KP00017" root="2.16.840.1.113883.19.5"/>
            <assignedPerson classCode="PSN">
                <name>
                    <given>Robert</given>
                    <family>Dolin</family>
                    <suffix>MD</suffix>
                </name>
            </assignedPerson>
        </assignedAuthor>
    </author>
    <custodian typeCode="CST">
 	    <assignedCustodian classCode="ASSIGNED">
 	      <representedCustodianOrganization classCode="ORG">
	        <id root="1BEB8E5A-F43D-11E6-B63C-0FFBC9D791CC"/>
 	        <name>ABRUMET asbl</name>
 	      </representedCustodianOrganization>
 	    </assignedCustodian>
 	</custodian>
    <component>
    <structuredBody classCode="DOCBODY">
      <component typeCode="COMP">
        <section classCode="DOCSECT">
          <templateId root="1.3.6.1.4.1.19376.1.5.3.1.3.1"/>
          <id root="BF2FA954-F43B-11E6-9397-EBC69C88DB61"/>
          <code code="42349-1" codeSystem="2.16.840.1.113883.6.1" codeSystemName="LOINC" displayName="REASON FOR REFERRAL"/>
          <title>Reason for referral</title>
          <text>Robert Hunter is a patient.</text>
        </section>
      </component>
      <component typeCode="COMP">
        <section classCode="DOCSECT">
          <templateId root="1.3.6.1.4.1.19376.1.5.3.1.3.4"/>
          <id root="BF303018-F43B-11E6-BDFA-97E99F59C825"/>
          <code code="10164-2" codeSystem="2.16.840.1.113883.6.1" codeSystemName="LOINC" displayName="History of Present Illness Section"/>
          <title>History of Present Illness Section</title>
          <text>No statement.</text>
        </section>
      </component>
        <component typeCode="COMP">
        <section classCode="DOCSECT">
          <templateId root="1.3.6.1.4.1.19376.1.5.3.1.3.6"/>
          <id root="BF3085A4-F43B-11E6-A16D-2FE01C50B8F"/>
          <code code="11450-4" codeSystem="2.16.840.1.113883.6.1" codeSystemName="LOINC" displayName="Active Problems Section"/>
          <title>Active Problems Section</title>
          <text>No statement.</text>
          <entry typeCode="COMP">
            <observation classCode="OBS" moodCode="DEF">
            <code nullFlavor="NI" />
            </observation>
          </entry>
        </section>
      </component>
      <component typeCode="COMP">
        <section classCode="DOCSECT">
          <templateId root="1.3.6.1.4.1.19376.1.5.3.1.3.19"/>
          <id root="BF30FAA2-F43B-11E6-83D6-8F2828CBBC0A"/>
          <code code="10160-0" codeSystem="2.16.840.1.113883.6.1" codeSystemName="LOINC" displayName="Medication Sections"/>
          <title>Medication Sections</title>
          <text>
            <table>
                <thead>
                    <tr>
                        <th>Medication</th>
                        <th>Instructions</th>
                        <th>Dosage</th>
                        <th>Effective Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ID="Medication_1">
                        <td ID="MedicationName_1">Theophylline</td>
                        <td ID="MedicationSig_1">deux fois par jour</td>
                        <td ID="MedicationDosage_1">200 mg</td>
                        <td>9 février 2017</td>
                        <td>Completed</td>
                    </tr>
                </tbody>
            </table>
          </text>
          <entry typeCode="DRIV">
            <substanceAdministration classCode="SBADM" moodCode="EVN">
                <templateId root='2.16.840.1.113883.10.20.1.24'/>
                <templateId root='1.3.6.1.4.1.19376.1.5.3.1.4.7'/>
                <id root="1BEE9D0C-F43D-11E6-A1A8-23558EA5AEFC" />
                <text><reference value="#Medication_1" /></text>
                <statusCode code="completed" />
                <effectiveTime xsi:type="IVL_TS">
                    <low value="20170209" />
                    <high value="20170228" />
                </effectiveTime>
                <effectiveTime xsi:type="PIVL_TS" institutionSpecified="true" operator="A">
                    <period value="12" unit="h"/>
                </effectiveTime>
                <routeCode code='20053000' codeSystem='2.16.840.1.113883.6.96' displayName='Oral Use' codeSystemName='SNOMED CT'/>
                <doseQuantity value="200" unit="mg"/>
                <consumable typeCode="CSM">
                    <manufacturedProduct classCode="MANU">
                        <manufacturedLabeledDrug classCode="MMAT">
                            <code code="66493003" 
                                codeSystem="2.16.840.1.113883.6.96" 
                                codeSystemName="SNOMED CT" 
                                displayName="Theophylline"/>
                        </manufacturedLabeledDrug>
                    </manufacturedProduct>
                </consumable>
                <!-- 
                    ici, les instructions pour le patient - à déterminer
                    
                <entryRelationship typeCode="SUBJ" inversionInd="true">
                    <act classCode="ACT" moodCode="INT">
                        
                        <templateId root="2.16.840.1.113883.10.20.22.4.20" extension="2014-06-09"/>
                        <templateId root="2.16.840.1.113883.10.20.22.4.20"/>
                        <text>
                            <reference value="#MedicationSig_1"/>
                        </text>
                        <statusCode code="completed"/>
                    </act>
                
                </entryRelationship>
                
                -->
            </substanceAdministration>
          </entry>
          </section>
      </component>
      <component typeCode="COMP">
        <section classCode="DOCSECT">
          <templateId root="1.3.6.1.4.1.19376.1.5.3.1.3.13"/>
          <id root="BF31B4D8-F43B-11E6-B02B-B711B1E5184B"/>
          <code code="48765-2" codeSystem="2.16.840.1.113883.6.1" codeSystemName="LOINC" displayName="Allergies and Other Adverse Reactions Section "/>
          <title>Allergies and Other Adverse Reactions Section </title>
          <text>no statement.</text>
          <entry typeCode="COMP">
            <observation classCode="OBS" moodCode="DEF">
            <code nullFlavor="NI" />
            </observation>
          </entry>
        </section>
      </component>
    </structuredBody>
    </component>
</ClinicalDocument>
CDA
            ;
        
        // create the initial document
        $doc = new ClinicalDocument();
        $doc->setTitle(new Title(new CharacterString("Good Health Clinic Consultation Note")));
        $doc->setEffectiveTime(new EffectiveTime(
                new TimeStamp(\DateTime::createFromFormat(\DateTime::ISO8601, 
                        "2014-08-27T01:43:12+0200"))));
        $doc->setId(new Id(new InstanceIdentifier("1.2.3.4", "https://mass.chill.pro")));
        // add templateId
        $doc->addTtemplateId(new InstanceIdentifier('1.3.6.1.4.1.19376.1.5.3.1.1.3'));
        $doc->setCode(new Code(LoincCode::create('42349-1', 'REASON FOR REFERRAL')));
        $doc->setConfidentialityCode(
            new ConfidentialityCode(
                ConfidentialityCodeType::create(
                    ConfidentialityCodeType::RESTRICTED_KEY, 
                    ConfidentialityCodeType::RESTRICTED
                    )
                )
            );
        $doc->setLanguageCode(new CodedSimple("fr-FR"));
        $doc->setRecordTarget($this->getRecordTarget());
        $doc->setAuthor(new Author(
            new TimeStamp(\DateTime::createFromFormat('Y-m-d-H:i', "2000-04-07-14:00")), 
            $this->getAssignedAuthor()
            ));
        $doc->setCustodian($this->getCustodian());
        
        // create components 
        $components = array(
            [
                '1.3.6.1.4.1.19376.1.5.3.1.3.1', // templateId of section
                "BF2FA954-F43B-11E6-9397-EBC69C88DB61", //id of section
                new LoincCode('42349-1', 'Reason for referral'), // code for section
                "Rober Hunter is a patient.", // text for section
                array() // no acts
            ],
            [
                '1.3.6.1.4.1.19376.1.5.3.1.3.4', // templateId of section
                "BF303018-F43B-11E6-BDFA-97E99F59C825", //id of section
                new LoincCode('10164-2', 'History of Present Illness Section'), // code for section
                "No statement.", // text for section
                array() // no acts
            ],
            [
                '1.3.6.1.4.1.19376.1.5.3.1.3.4', // templateId of section
                "BF3085A4-F43B-11E6-A16D-2FE01C50B8F", //id of section
                new LoincCode('11450-4', 'Active Problems Section'), // code for section
                "No statement.", // text for section
                array(Observation::createNullObservation())
            ],
            [
                '1.3.6.1.4.1.19376.1.5.3.1.3.19', // templateId of section
                "BF30FAA2-F43B-11E6-83D6-8F2828CBBC0A", //id of section
                new LoincCode('10160-0', 'Medication Sections'), // code for section
                (new NarrativeString())
                    ->createTable()
                    ->getThead()
                        ->createRow()
                            ->createCell('Medication')->getRow()
                            ->createCell('Instructions')->getRow()
                            ->createCell('Dosage')->getRow()
                            ->createCell('Effective Date')->getRow()
                            ->createCell('Status')->getRow()
                        ->getSection()
                    ->getTable()
                    ->getTbody()
                        ->createRow()
                            ->setReference(
                                    $doc
                                        ->getReferenceManager()
                                        ->getReferenceType('Medication_1')
                                )
                            ->createCell('Theophylline')
                                ->setReference(
                                    $doc
                                        ->getReferenceManager()
                                        ->getReferenceType('MedicationName_1'))
                                ->getRow()
                            ->createCell('deux fois par jour')
                                ->setReference(
                                    $doc
                                        ->getReferenceManager()
                                        ->getReferenceType('MedicationSig_1'))
                                ->getRow()
                            ->createCell('200 mg')
                                ->setReference(
                                    $doc
                                        ->getReferenceManager()
                                        ->getReferenceType('MedicationDosage_1'))
                                ->getRow()
                            ->createCell('9 février 2017')->getRow()
                            ->createCell('Completed')->getRow()
                        ->getSection()
                    ->getTable()
                ->getNarrative(), // text for section
                array(
                    (new SubstanceAdministration)
                        ->setTemplateIds(array(
                            new InstanceIdentifier('2.16.840.1.113883.10.20.1.24'),
                            new InstanceIdentifier('1.3.6.1.4.1.19376.1.5.3.1.4.7')
                        ))
                        ->setIds(
                            (new Set(InstanceIdentifier::class))
                                ->add(new InstanceIdentifier("1BEE9D0C-F43D-11E6-A1A8-23558EA5AEFC"))
                            )
                        ->setText($doc->getReferenceManager()->getReferenceElement('Medication_1'))
                        ->setStatusCode(
                            new StatusCode(StatusCode::COMPLETED))
                        ->setEffectiveTime(
                            new IntervalOfTime(
                                new TimeStamp(
                                    \DateTime::createFromFormat('Y-m-d', '2017-02-09')
                                    ), 
                                new TimeStamp(
                                    \DateTime::createFromFormat('Y-m-d', '2017-02-28')
                                    )
                                )
                            )
                        ->setEffectiveTime(
                            (new PeriodicIntervalOfTime(new \DateInterval('PT12H')))
                                ->setInstitutionSpecified(true)
                            )
                        ->setRouteCode(new SnomedCTCode('20053000', 'Oral Use'))
                        ->setDoseQuantity(new PhysicalQuantity("mg", 200))
                        ->setConsumable(
                            new Consumable(
                                new ManufacturedProduct(
                                    new ManufacturedLabeledDrug(
                                        new SnomedCTCode("66493003", "Theophylline")
                                        )
                                    )
                                )
                            )
                )
            ],
            [
                '1.3.6.1.4.1.19376.1.5.3.1.3.13', // templateId of section
                "BF31B4D8-F43B-11E6-B02B-B711B1E5184B", //id of section
                new LoincCode('48765-2', 'allergies and Other Adverse Reactions Section'), // code for section
                "No statement.", // text for section
                array(Observation::createNullObservation()) 
            ],
        );
        
        /* @var $xmlBody XMLBodyComponent the root xml body */
        $xmlBody = new XMLBodyComponent();
        
        /* @var $loinc LoincCode */
        foreach ($components as list($templateId, $id, $loinc, $text, $acts)) {
            $section = (new Section())
                ->addTemplateId(new InstanceIdentifier($templateId))
                ->setTitle(new CharacterString($loinc->getDisplayName()))
                ->setId(new InstanceIdentifier($id))
                ->setCode($loinc)
                ->setText(
                    // if is a string, create a character string,
                    // else, assume that `$text` implements AnyType
                    is_string($text) ? new CharacterString($text) : $text
                    )
                ;
            
            if (count($acts) > 0) {
                $entry = $section->createEntry();
            }
            
            foreach ($acts as $act) {
                $entry->addAct($act);
            }

            $component = (new SingleComponent())
                ->addSection($section);
            $xmlBody->addComponent($component);
        }
        
        $doc->getRootComponent()->addComponent($xmlBody);
        
        $expectedDoc = new \DOMDocument;
        $expectedDoc->loadXML($expected);
        
        $fake = $doc->toDOMDocument();
        
        $fake->save(\sys_get_temp_dir().'/'.__METHOD__.'.xml');
        
        $this->assertEqualXMLStructure($expectedDoc->firstChild, 
            $fake->firstChild, true);
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
    
        /**
     * 
     * @return AssignedAuthor
     */
    protected function getAssignedAuthor()
    {
        $names = new Set(PersonName::class);
        $names->add((new PersonName())
            ->addPart(PersonName::FIRST_NAME, 'Robert')
            ->addPart(PersonName::LAST_NAME, 'Dolin')
            ->addPart('suffix', 'MD')
            );
        
        $assignedAuthor = new AssignedAuthor(
            new AssignedPerson($names),
            (new Set(InstanceIdentifier::class))
                ->add(new InstanceIdentifier("2.16.840.1.113883.19.5", "KP00017"))
            );
        
        return $assignedAuthor;
    }
    
    /**
     * 
     * @return Custodian
     */
    protected function getCustodian()
    {
        $names = (new Set(EntityName::class))
            ->add(new EntityName('ABRUMET asbl'));
        $ids = (new Set(InstanceIdentifier::class))
            ->add(new InstanceIdentifier('82112744-ea24-11e6-95be-17f96f76d55c'));
        
        $reprCustodian = new RepresentedCustodianOrganization($names, $ids);
        
        $assignedCustodian = new AssignedCustodian($reprCustodian);
        
        return new Custodian($assignedCustodian);
    }
}
