<?php
/*
 * The MIT License
 *
 * Copyright 2017 Julien Fastré <julien.fastre@champs-libres.coop>.
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
namespace PHPHealth\CDA\Tests\RIM\Act;

use PHPHealth\CDA\RIM\Act\SubstanceAdministration;
use PHPHealth\CDA\DataType\Identifier\InstanceIdentifier;
use PHPHealth\CDA\DataType\TextAndMultimedia\CharacterString;
use PHPHealth\CDA\DataType\Collection\Interval\PeriodicIntervalOfTime;
use PHPHealth\CDA\DataType\Quantity\PhysicalQuantity\PhysicalQuantity;
use PHPHealth\CDA\DataType\Code\SnomedCTCode;
use PHPHealth\CDA\RIM\Participation\Consumable;
use PHPHealth\CDA\RIM\Role\ManufacturedProduct;
use PHPHealth\CDA\RIM\Entity\ManufacturedLabeledDrug;

/**
 * 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class SubstanceAdministrationTest extends \PHPUnit\Framework\TestCase
{
    public function testGetElement()
    {
        $expected = <<<XML
<container xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
<substanceAdministration classCode="SBADM" moodCode="EVN">
    <templateId root='2.16.840.1.113883.10.20.1.24'/>
    <templateId root='1.3.6.1.4.1.19376.1.5.3.1.4.7'/>
    <text>Theodur 200mg BID</text>
    <effectiveTime xsi:type="PIVL_TS" institutionSpecified="true">
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
</substanceAdministration>
</container>
XML
            ;
        
        $substanceAdministration = new SubstanceAdministration();
        $substanceAdministration
            ->setTemplateIds(array(
                new InstanceIdentifier('2.16.840.1.113883.10.20.1.24'),
                new InstanceIdentifier('1.3.6.1.4.1.19376.1.5.3.1.4.7')
            ))
            ->setText(new CharacterString("Theodur 200mg BID"))
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
                );
        
        $expectedDoc = new \DOMDocument('1.0');
        $expectedDoc->loadXML($expected);
        
        // append the substanceAdministration within an container xml
        // with xmlns declaration
        $fake = new \DOMDocument;
        $fake->loadXML('<container '
            . 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'
            . '</container>');
        $fake->firstChild->appendChild($substanceAdministration
            ->toDOMElement($fake));
        
        $this->assertEqualXMLStructure($expectedDoc->firstChild, 
            $fake->firstChild, false);
    }
    
    /**
     * test the construction of a whole section.
     * 
     * From https://github.com/jddamore/HL7-Task-Force-Examples/blob/master/MED_SC_Basal_Insulin.xml
     * , example referenced by [
     */
    public function testInsulineMedication()
    {
        $this->markTestIncomplete("some elements are stil missing");
        
        $xml = <<<XML
<section>
  <templateId root="2.16.840.1.113883.10.20.22.2.1.1"/>
  <!-- Medication Section (entries required) -->
  <code code="10160-0" codeSystem="2.16.840.1.113883.6.1" codeSystemName="LOINC" displayName="History of Medication Use"/>
  <title>MEDICATIONS</title>
  <text>
    <table border="1" width="100%">
      <thead>
        <tr>
          <th>Medication</th>
          <th>Instructions</th>
          <th>Dosage</th>
          <th>Effective Dates (start - stop)</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr ID="Medication_6">
          <td>
            <content ID="MedicationName_6">3 ML Insulin Glargine 100 UNT/ML Pen Injector [Lantus]</content>
          </td>
          <td>
            <content ID="MedicationSig_6">Administer 40 units at bedtime</content>
          </td>
          <td>
            <content>40 units</content>
          </td>
          <td>Jan-09-2009 - </td>
          <td>Active</td>
        </tr>
      </tbody>
    </table>
  </text>
  <entry typeCode="DRIV">
    <substanceAdministration classCode="SBADM" moodCode="EVN">
      <templateId root="2.16.840.1.113883.10.20.22.4.16"/>
      <id root="1310a2d3-f888-4722-b4c4-a3c5911ac7f9"/>
      <text>
        <!-- This reference refers to medication information in unstructured portion of section-->
        <reference value="#Medication_6"/>
      </text>
      <statusCode code="active"/>
      <!-- This first effectiveTime shows that medication was added on January 9, 2009 (not known to have stopped)-->
      <effectiveTime xsi:type="IVL_TS">
        <low value="20090109"/>
        <high nullFlavor="NI"/>
      </effectiveTime>
      <!-- The second effectiveTime specifies dose frequency, which can be either a period (PIVL_TS) or event (EIVL_TS). -->
      <!-- This long-lasting insulin is administered once per day before bedtime (code = "HS"), which is an event-->
      <effectiveTime xsi:type="EIVL_TS" operator="A">
        <event code="HS"/>
      </effectiveTime>
      <!-- This route uses the NCI (National Cancer Institute) Thesauraus code system, which is constrained to the value set of 2.16.840.1.113883.3.88.12.3221.8.7 (FDA Medication Route) -->
      <routeCode code="C38299" codeSystem="2.16.840.1.113883.3.26.1.1" displayName="SUBCUTANEOUS" codeSystemName="NCI Thesaurus" />
      <!-- Since this dose is not pre-coordinated, specify both the amount with units in UCUM. [IU] is international units -->
      <!-- Note that this basal insulin is not administered on a sliding scale and a specific dose is administered-->
      <doseQuantity value="40" unit="[IU]"/>
      <consumable typeCode="CSM">
        <manufacturedProduct classCode="MANU">
          <!-- ** Medication information ** -->
          <templateId root="2.16.840.1.113883.10.20.22.4.23"/>
          <manufacturedMaterial classCode="MMAT" determinerCode="KIND">
            <!-- Medications should be specified at a level corresponding to prescription when possible (branded medication below)-->
            <!-- Note the medication code specified in the test data is 261551, but that is not used since it's not an administered product (just brand name)-->
            <code code="847232" codeSystem="2.16.840.1.113883.6.88" displayName="3 ML Insulin Glargine 100 UNT/ML Pen Injector [Lantus]" codeSystemName="RxNorm">
              <originalText>
                <reference value="#MedicationName_6"/>
              </originalText>
            </code>
          </manufacturedMaterial>
          <manufacturerOrganization classCode="ORG" determinerCode="INSTANCE">
            <name>SANOFI-AVENTIS</name>
          </manufacturerOrganization>
        </manufacturedProduct>
      </consumable>
    </substanceAdministration>
  </entry>
</section>
XML
            ;
    }
}
