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
namespace PHPHealth\CDA\DataType\Boolean;

use PHPHealth\CDA\DataType\AnyType;

/**
 * Boolean element
 * 
 * As the boolean element may be applyed with different tags, 
 * the tag on wich the element apply may be set by the Element which will
 * use the data
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class Boolean extends AnyType
{
    protected $value;
    
    protected $tag;
    
    public function __construct($value, $tag = null)
    {
        $this->value = $value;
        $this->tag = $tag;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getTag()
    {
        return $this->tag;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function setTag($tag)
    {
        $this->tag = $tag;
        
        return $this;
    }

        
    public function setValueToElement(\DOMElement &$el, \DOMDocument $doc = null)
    {
        assert ($this->getTag() === null, new \RuntimeException("The tag "
            . "on boolean must be defined"));
        
        $el->setAttributeNS(CD::NS_CDA, $this->getTag(), $value);
    }
}
