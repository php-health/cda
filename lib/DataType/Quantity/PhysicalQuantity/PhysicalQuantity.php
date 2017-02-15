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
namespace PHPHealth\CDA\DataType\Quantity\PhysicalQuantity;

use PHPHealth\CDA\DataType\Quantity\AbstractQuantity;
use PHPHealth\CDA\ClinicalDocument as CDA;

/**
 * A dimensioned quantity expressing the result of measuring. 
 * 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class PhysicalQuantity extends AbstractQuantity
{
    protected $ucumUnit;
    
    protected $value;
    
    public function __construct($ucumUnit, $value)
    {
        $this->setUcumUnit($ucumUnit);
        $this->setValue($value);
    }

    
    public function getUcumUnit()
    {
        return $this->ucumUnit;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setUcumUnit($ucumUnit)
    {
        $this->ucumUnit = $ucumUnit;
        return $this;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

        
    public function setValueToElement(\DOMElement &$el, \DOMDocument $doc = null)
    {
        if ($this->getValue() !== NULL) {
            $el->setAttributeNS(CDA::NS_CDA, 'value', $this->getValue());
        }
        
        if ($this->getUcumUnit() !== NULL) {
            $el->setAttributeNS(CDA::NS_CDA, 'unit', $this->getUcumUnit());
        }
    }
}
