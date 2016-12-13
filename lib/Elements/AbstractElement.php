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

namespace PHPHealth\CDA\Elements;

use PHPHealth\CDA\ClinicalDocument as CDA;

/**
 * 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
abstract class AbstractElement implements \PHPHealth\CDA\ElementInterface
{
    /**
     * get the element tag name
     * 
     * @return string
     */
    abstract protected function getElementTag();
    
    /**
     * create an element with the tag given by self::getElementTag and
     * apply this element to datatype given by $properties
     * 
     * @param \DOMDocument $doc
     * @param string[] $properties the name of the properties to apply on element
     * @return \DOMElement
     */
    protected function createElement(\DOMDocument $doc, array $properties = array())
    {
        /* @var $el DOMElement */
        $el = $doc->createElement(CDA::NS_CDA.$this->getElementTag());
        
        if (count($properties) > 0) {
            foreach($properties as $property) {
                $this->{$property}->setValueToElement($el);
            }
        }
        
        return $el;
    }
    
}
