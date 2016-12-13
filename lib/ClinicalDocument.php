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

namespace PHPHealth\CDA;

use PHPHealth\CDA\DataType\Quantity\DateAndTime\TimeStamp;
use PHPHealth\CDA\DataType\Identifier\InstanceIdentifier;
use PHPHealth\CDA\DataType\Code\CodedValue;
use PHPHealth\CDA\Elements\Code;
use PHPHealth\CDA\Elements\Title;
use PHPHealth\CDA\Elements\EffectiveTime;
use PHPHealth\CDA\Elements\Id;
use PHPHealth\CDA\Elements\ConfidentialityCode;
use PHPHealth\CDA\Elements\TypeId;

/**
 * Root class for clinical document
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class ClinicalDocument
{
    const NS_CDA = '';
    
    /**
     * the templateId of the document. Will be inserted into doc, like
     * 
     * ```
     * <typeId>
     * ```
     * 
     * TODO : always equals to '2.16.840.1.113883.3.27.1776'
     *
     * @var TypeId
     */
    private $typeId;
    
    /**
     * the title of the document
     *
     * @var Title
     */
    private $title;
    
    /**
     * the root component
     *
     * @var Component\RootBodyComponent 
     */
    private $rootComponent;
    
    /**
     *
     * @var EffectiveTime
     */
    private $effectiveTime;
    
    /**
     *
     * @var Id
     */
    private $id;
    
    /**
     *
     * @var Code 
     */
    private $code;
    
    /**
     *
     * @var ConfidentialityCode
     */
    private $confidentialityCode;
    
    public function __construct()
    {
        $this->rootComponent = new Component\RootBodyComponent();
        
        $typeIdIdentifier = new InstanceIdentifier("2.16.840.1.113883.1.3", 
                "POCD_HD000040");
        $this->typeId = new TypeId($typeIdIdentifier);
    }
    
    /**
     * 
     * @return string
     */
    function getTitle()
    {
        return $this->title;
    }

    /**
     * 
     * @param \PHPHealth\CDA\Elements\Title $title
     * @return \PHPHealth\CDA2\ClinicalDocument
     */
    function setTitle(Title $title)
    {
        $this->title = $title;
        
        return $this;
    }
    
    /**
     * 
     * @return EffectiveTime
     */
    public function getEffectiveTime()
    {
        return $this->effectiveTime;
    }

    /**
     * 
     * @param EffectiveTime $effectiveTime
     * @return $this
     */
    public function setEffectiveTime(EffectiveTime $effectiveTime)
    {
        $this->effectiveTime = $effectiveTime;
        
        return $this;
    }
    
    /**
     * 
     * @return Id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 
     * @param Id $id
     * @return $this
     */
    public function setId(Id $id)
    {
        $this->id = $id;
        
        return $this;
    }

    /**
     * Get the code of the document
     * 
     * @return Code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the code of the document
     * 
     * @param Code $code
     * @return $this
     */
    public function setCode(Code $code)
    {
        $this->code = $code;
        
        return $this;
    }
    
    /**
     * 
     * @return ConfidentialityCode
     */
    public function getConfidentialityCode()
    {
        return $this->confidentialityCode;
    }

    /**
     * 
     * @param ConfidentialityCode $confidentialityCode
     * @return $this
     */
    public function setConfidentialityCode(ConfidentialityCode $confidentialityCode)
    {
        $this->confidentialityCode = $confidentialityCode;
        
        return $this;
    }

        
    /**
     * 
     * @return Component\RootBodyComponent;
     */
    public function getRootComponent()
    {
        return $this->rootComponent;
    }
    
    /**
     * 
     * @return \DOMDocument
     */
    public function toDOMDocument()
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        
        $doc = $dom->createElementNS('urn:hl7-org:v3', 'ClinicalDocument');
        $dom->appendChild($doc);
        // set the NS
        $doc->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 
            'xsi:schemaLocation','urn:hl7-org:v3 CDA.xsd');
        // add typeId
        $doc->appendChild($this->typeId->toDOMElement($dom));
        // add id
        if ($this->getId() !== null) {
            $doc->appendChild($this->getId()->toDOMElement($dom));
        }
        // add code
        if ($this->getCode() !== null) {
            $doc->appendChild($this->getCode()->toDOMElement($dom));
        }
        
        
        //add effective time
        if ($this->getEffectiveTime() !== null) {
            $doc->appendChild($this->getEffectiveTime()->toDOMElement($dom));
        }
        
        // add title
        if ($this->getTitle() !== null) {
            $doc->appendChild($this->getTitle()->toDOMElement($dom));
        }
        
        // add cofidentialityCode
        if ($this->getConfidentialityCode() !== null) {
            $doc->appendChild($this->confidentialityCode->toDOMElement($dom));
        }

        // add components
        if (!$this->getRootComponent()->isEmpty()) {
            $doc->appendChild($this->getRootComponent()->toDOMElement($dom));
        }
        
        return $dom;
    }
    
    
}
