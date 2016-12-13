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

namespace PHPHealth\CDA\DataType\Name;

use PHPHealth\CDA\ClinicalDocument as CDA;

/**
 *
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class PersonName extends EntityName
{
    
    protected $parts = array();
    
    protected $qualifiers = array();
    
    const HONORIFIC = 'prefix';
    const FIRST_NAME = 'given';
    const LAST_NAME = 'family';
    
    
    public function __construct()
    {
        parent::__construct(null);
    }
    
    public function addPart($part, $value, $qualifier = null)
    {
        $this->parts[$part] = $value;
        
        if ($qualifier !== null) {
            $this->qualifiers[$part] = $qualifier;
        }
        
        return $this;
    }
    
    
    public function setValueToElement(\DOMElement &$el, \DOMDocument $doc = null)
    {
        if (count($this->parts) > 0) {
            $name = $doc->createElement(CDA::NS_CDA.'name');
            $el->appendChild($name);
            
            foreach ($this->parts as $part => $value) {
                $partElement = $doc->createElement(CDA::NS_CDA.$part, $value);
                $name->appendChild($partElement);
            }
        } elseif ($this->string !== null) {
            parent::setValueToElement($el, $doc);
        } else {
            throw new \InvalidArgumentException("the element does not contains any "
                    . "parts nor string");
        }
    }
}
