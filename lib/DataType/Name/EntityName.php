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

/**
 * A name for a person, organization, place or thing. A sequence of name parts, 
 * such as given name or family name, prefix, suffix, etc. Examples for entity 
 * name values are "Jim Bob Walton, Jr.", "Health Level Seven, Inc.", 
 * "Lake Tahoe", etc. An entity name may be as simple as a character string or 
 * may consist of several entity name parts, such as, "Jim", "Bob", "Walton", 
 * and "Jr.", "Health Level Seven" and "Inc.", "Lake" and "Tahoe".
 * 
 * Structurally, the entity name data type is a sequence of entity name part 
 * values with an added "use" code and a valid time range for information about 
 * if and when the name can be used for a given purpose. 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class EntityName extends \PHPHealth\CDA\DataType\AnyType
{
    /**
     *
     * @var string
     */
    protected $string;
    
    public function __construct($string = null)
    {
        $this->setString($string);
    }
    
    public function getString()
    {
        return $this->string;
    }

    public function setString($string)
    {
        $this->string = $string;
        return $this;
    }
        
    public function setValueToElement(\DOMElement &$el, \DOMDocument $doc = null)
    {
        $name = $doc->createElement('name');
        $name->appendChild($doc->createTextNode($this->getString()));
        
        $el->appendChild($name);
    }

}
