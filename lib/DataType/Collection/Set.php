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

namespace PHPHealth\CDA\DataType\Collection;

use PHPHealth\CDA\DataType\AnyType;

/**
 * Set of elements.
 * 
 * This class restrict the sub element to the same element name. This name
 * cannot be changed after the construction of this class.
 * 
 * Example of initializsation : 
 * 
 * ```
 * use PHPHealth\CDA\DataType\Name\PersonName;
 * 
 * new Set(PersonName::class);
 * ```
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class Set extends AnyType
{
    /**
     * The contained elements
     *
     * @var mixed[]
     */
    protected $elements = array();
    
    private $elementName;
    
    /**
     * 
     * @param string $elementName the class of the element to restrict
     */
    public function __construct($elementName)
    {
        $this->elementName = $elementName;
    }
    
    public function getElementName()
    {
        return $this->elementName;
    }
        
    public function add($el)
    {
        if (!$el instanceof $this->elementName) {
            throw new \InvalidArgumentException(sprintf("The given element should be "
                    . "an instance of %s, %s given", $this->elementName, get_class($el)));
        }
        
        $this->elements[] = $el;
        
        return $this;
    }
    
    /**
     * @return mixed[]
     */
    public function get()
    {
        return $this->elements;
    }
    
    public function setValueToElement(\DOMElement &$el, \DOMDocument $doc = null)
    {
        if (count($this->elements) === 0) {
            return;
        }
        
        if ($this->elements[0] instanceof AnyType) {
            foreach($this->elements as $sub) {
                $sub->setValueToElement($el, $doc);
            }
        } elseif ($this->elements[0] instanceof \PHPHealth\CDA\ElementInterface) {
            $el->appendChild($sub->toDOMElement($doc));
        } else {
            throw new \LogicException(sprintf("the elements added to set are "
                    . "not instance of %s nor %s", AnyType::class, 
                    \PHPHealth\CDA\ElementInterface::class));
        }
    }

}
