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
     * @var DataType\Identifier\InstanceIdentifier
     */
    private $typeId;
    
    /**
     * the title of the document
     *
     * @var string
     */
    private $title;
    
    private $rootComponent;
    
    /**
     *
     * @var TimeStamp
     */
    private $effectiveTime;
    
    /**
     *
     * @var InstanceIdentifier
     */
    private $id;
    
    public function __construct()
    {
        $this->rootComponent = new Component\RootBodyComponent();
        
        $this->typeId = new InstanceIdentifier("2.16.840.1.113883.1.3", "POCD_HD000040");
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
     * @param string $title
     * @return \PHPHealth\CDA2\ClinicalDocument
     */
    function setTitle($title)
    {
        $this->title = $title;
        
        return $this;
    }
    
    /**
     * 
     * @return TimeStamp
     */
    public function getEffectiveTime()
    {
        return $this->effectiveTime;
    }

    /**
     * 
     * @param \DateTime|TimeStamp $effectiveTime
     * @return $this
     */
    public function setEffectiveTime($effectiveTime)
    {
        if ($effectiveTime instanceof \DateTime) {
            $this->effectiveTime = new TimeStamp($effectiveTime); 
        } elseif ($effectiveTime instanceof TimeStamp) {
            $this->effectiveTime = $effectiveTime;
        } else {
            throw new \InvalidArgumentException(sprintf("the effective time should be "
                . "a %s or %s.", \DateTime::class, TimeStamp::class));
        }
        
        return $this;
    }
    
    /**
     * 
     * @return InstanceIdentifier
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 
     * @param InstanceIdentifier $id
     * @return $this
     */
    public function setId(InstanceIdentifier $id)
    {
        $this->id = $id;
        
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
        $typeId = $dom->createElement(self::NS_CDA.'typeId');
        $this->typeId->setValueToElement($typeId);
        $doc->appendChild($typeId);
        // add id
        if ($this->getId() !== null) {
            $id = $dom->createElement('id');
            $this->id->setValueToElement($id);
            $doc->appendChild($id);
        }
        // add title
        $doc->appendchild($dom->createElement('title', $this->title));
        //add effective time
        if ($this->getEffectiveTime() !== null) {
            $et = $dom->createElement('effectiveTime');
            $this->effectiveTime->setValueToElement($et);
            $doc->appendChild($et);
        }
        // add components
        if (!$this->getRootComponent()->isEmpty()) {
            $doc->appendChild($this->getRootComponent()->toDOMElement($dom));
        }
        
        $this->isInitialized = true;
        
        return $dom;
    }
    
    
}
