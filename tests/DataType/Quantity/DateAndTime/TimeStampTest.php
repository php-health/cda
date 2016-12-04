<?php
/*
 * The MIT License
 *
 * Copyright 2016 julien.fastre@champs-libres.coop
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

namespace PHPHealth\CDA\Tests\DataType\Quantity\DateAndTime;

use PHPHealth\CDA\DataType\Quantity\DateAndTime\TimeStamp;
use PHPUnit\Framework\TestCase;

/**
 * tests for TimeStampTest
 *
 * @author julien
 */
class TimeStampTest extends TestCase
{
    public function testEffectiveTime()
    {
        $ts = new TimeStamp();
        $ts->setDate(\DateTime::createFromFormat(\DateTime::ISO8601, 
            "2009-12-12T17:21:51-0500"));
        
        $doc = new \DOMDocument('1.0', 'UTF-8');
        
        $el = $doc->createElement('effectiveTime');
        $doc->appendChild($el);
        
        $ts->setValueToElement($el);
        
        $expected = <<<'CDA'
<effectiveTime value="20091212172151"/>
CDA;
        
        $this->assertEquals($expected, $doc->saveXML($el));
    }
    
    public function testWithPrecision()
    {
        $ts = new TimeStamp();
        $ts->setDate(\DateTime::createFromFormat(\DateTime::ISO8601, 
            "2009-12-12T17:21:51-0500"))
            ->setPrecision(TimeStamp::PRECISION_DAY);
        
        $doc = new \DOMDocument('1.0', 'UTF-8');
        
        $el = $doc->createElement('effectiveTime');
        $doc->appendChild($el);
        
        $ts->setValueToElement($el);
        
        $expected = <<<'CDA'
<effectiveTime value="20091212"/>
CDA;
        
        $this->assertEquals($expected, $doc->saveXML($el));
    }
    
    public function testWithOffsetOnFalse()
    {
        $ts = new TimeStamp();
        $ts->setDate(\DateTime::createFromFormat(\DateTime::ISO8601, 
            "2009-12-12T17:21:51-0500"))
            ->setPrecision(TimeStamp::PRECISION_SECONDS);
        
        $doc = new \DOMDocument('1.0', 'UTF-8');
        
        $el = $doc->createElement('effectiveTime');
        $doc->appendChild($el);
        
        $ts->setValueToElement($el);
        
        $expected = <<<'CDA'
<effectiveTime value="20091212172151"/>
CDA;
        
        $this->assertEquals($expected, $doc->saveXML($el));
    }
    
    public function testWithOffsetOnTrue()
    {
        $ts = new TimeStamp();
        $ts->setDate(\DateTime::createFromFormat(\DateTime::ISO8601, 
            "2009-12-12T17:21:51-0500"))
            ->setPrecision(TimeStamp::PRECISION_SECONDS)
            ->setOffset(true);
        
        $doc = new \DOMDocument('1.0', 'UTF-8');
        
        $el = $doc->createElement('effectiveTime');
        $doc->appendChild($el);
        
        $ts->setValueToElement($el);
        
        $expected = <<<'CDA'
<effectiveTime value="20091212172151-0500"/>
CDA;
        
        $this->assertEquals($expected, $doc->saveXML($el));
    }
}
