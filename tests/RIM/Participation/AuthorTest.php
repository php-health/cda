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
namespace PHPHealth\CDA\Tests\RIM\Participation;

use PHPHealth\CDA\DataType\Quantity\DateAndTime\TimeStamp;
use PHPHealth\CDA\RIM\Participation\Author;
use PHPHealth\CDA\RIM\Role\AssignedAuthor;
use PHPHealth\CDA\DataType\Identifier\InstanceIdentifier;
use PHPHealth\CDA\DataType\Collection\Set;
use PHPHealth\CDA\DataType\Name\PersonName;
use PHPHealth\CDA\RIM\Entity\AssignedPerson;

/**
 * 
 *
 * @author julien
 */
class AuthorTest extends \PHPUnit\Framework\TestCase
{
    public function testAuthor()
    {
        $author = new Author(
            new TimeStamp(\DateTime::createFromFormat('Y-m-d-H:i', "2000-04-07-14:00")), 
            $this->getAssignedAuthor()
            );
        
        
        $expected = <<<'CDA'
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
CDA;
        
        $expectedDoc = new \DOMDocument('1.0');
        $expectedDoc->loadXML($expected); 
        $expectedAuthor = $expectedDoc
                ->getElementsByTagName('author')
                ->item(0);
        
        $this->assertEqualXMLStructure($expectedAuthor, 
            $author->toDOMElement(new \DOMDocument()), true);
    }
    
    /**
     * 
     * @return AssignedAuthor
     */
    private function getAssignedAuthor()
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
}
