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
namespace PHPHealth\CDA\Tests\RIM\Participation;

use PHPHealth\CDA\RIM\Entity\RepresentedCustodianOrganization;
use PHPHealth\CDA\RIM\Role\AssignedCustodian;
use PHPHealth\CDA\RIM\Participation\Custodian;
use PHPHealth\CDA\DataType\Name\EntityName;
use PHPHealth\CDA\DataType\Collection\Set;
use PHPHealth\CDA\DataType\Identifier\InstanceIdentifier;

/**
 * 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class CustodianTest extends \PHPUnit\Framework\TestCase
{
    public function testCustodian()
    {
        $names = (new Set(EntityName::class))
            ->add(new EntityName('ABRUMET asbl'));
        $ids = (new Set(InstanceIdentifier::class))
            ->add(new InstanceIdentifier('82112744-ea24-11e6-95be-17f96f76d55c'));
        
        $reprCustodian = new RepresentedCustodianOrganization($names, $ids);
        
        $assignedCustodian = new AssignedCustodian($reprCustodian);
        
        $custodian = new Custodian($assignedCustodian);
        
        $expected = <<<'CDA'
 	  <custodian typeCode="CST">
 	    <assignedCustodian classCode="ASSIGNED">
 	      <representedCustodianOrganization classCode="ORG">
	        <id root="82112744-ea24-11e6-95be-17f96f76d55c"/>
 	        <name>ABRUMET asbl</name>
 	      </representedCustodianOrganization>
 	    </assignedCustodian>
 	  </custodian>
CDA;
        $expectedDoc = new \DOMDocument('1.0');
        $expectedDoc->loadXML($expected); 
        $expectedCustodian = $expectedDoc
                ->getElementsByTagName('custodian')
                ->item(0);
        
        $this->assertEqualXMLStructure($expectedCustodian, 
            $custodian->toDOMElement(new \DOMDocument()), true);
            
    }
}
