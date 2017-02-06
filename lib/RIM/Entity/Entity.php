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
namespace PHPHealth\CDA\RIM\Entity;

use PHPHealth\CDA\DataType\Collection\Set;
use PHPHealth\CDA\DataType\Identifier\InstanceIdentifier;
use PHPHealth\CDA\ClinicalDocument as CDA;
use PHPHealth\CDA\Elements\AbstractElement;
use PHPHealth\CDA\HasClassCode;

/**
 * Description of Entity
 *
 * @author julien
 */
abstract class Entity extends AbstractElement implements HasClassCode
{    
    /**
     *
     * @var Set|InstanceIdentifier[]
     */
    protected $id;
    
    /**
     *
     * @var Set|\PHPHealth\CDA\DataType\Name\PersonName[]
     */
    protected $names;
    
    /**
     * return the default class code
     * 
     * @return string
     */
    abstract public function getDefaultClassCode();
    
    /**
     * Return the default class code
     * 
     * @return string
     */
    public function getClassCode()
    {
        return $this->getDefaultClassCode();
    }
    
    public function getId(): Set
    {
        return $this->id;
    }

    public function setId(Set $id)
    {
        $id->checkContainsOrThrow(InstanceIdentifier::class);
        
        $this->id = $id;
        
        return $this;
    }

        
    public function getNames()
    {
        return $this->names;
    }
    
    public function setNames(Set $names)
    {
        $this->names = $names;
        
        return $this;
    }
    
    /**
     * 
     * @param \DOMDocument $doc
     * @param string[] $properties the name of the properties to apply on element
     * @return \DOMElement
     */
    /*protected function createElement(\DOMDocument $doc, array $properties = array())
    {
        $el = parent::createElement($doc, $properties);
        
        if (! empty($this->getClassCode())) {
            $el->setAttribute(CDA::NS_CDA.'classCode', $this->getClassCode());
        }
        
        return $el;
    }*/

}
