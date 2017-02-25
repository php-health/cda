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
use PHPHealth\CDA\RIM\Participation\RecordTarget;
use PHPHealth\CDA\RIM\Participation\Author;
use PHPHealth\CDA\RIM\Participation\Custodian;
use PHPHealth\CDA\DataType\Code\CodedSimple;
use PHPHealth\CDA\Helper\ReferenceManager;

/**
 * Root class for clinical document
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class ClinicalDocument
{
    const NS_CDA = '';
    
    /**
     * Reference manager assigned to this document
     *
     * @var ReferenceManager
     */
    private $referenceManager;
    
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
     *
     * @var InstanceIdentifier[]
     */
    private $templateId = array();
    
    /**
     *
     * @var CodedSimple
     */
    private $languageCode;
    
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
     * @var RecordTarget
     */
    private $recordTarget;
    
    /**
     *
     * @var ConfidentialityCode
     */
    private $confidentialityCode;
    
    /**
     *
     * @var Custodian
     */
    private $custodian;
    
    /**
     *
     * @var Author
     */
    private $author;
    
    public function __construct()
    {
        $this->rootComponent = new Component\RootBodyComponent();
        $this->referenceManager = new ReferenceManager();
        
        $typeIdIdentifier = new InstanceIdentifier(
            "2.16.840.1.113883.1.3",
            "POCD_HD000040"
        );
        $this->typeId = new TypeId($typeIdIdentifier);
    }
    
    /**
     * 
     * @return ReferenceManager
     */
    function getReferenceManager(): ReferenceManager
    {
        return $this->referenceManager;
    }
    
    /**
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     * @param \PHPHealth\CDA\Elements\Title $title
     * @return \PHPHealth\CDA2\ClinicalDocument
     */
    public function setTitle(Title $title)
    {
        $this->title = $title;
        
        return $this;
    }
    
    function getTemplateId(): array
    {
        return $this->templateId;
    }

    function setTemplateId(array $templateId)
    {
        $validate = \array_reduce($templateId, function($carry, $item) {
            if ($carry === false) {
                return false;
            }
            
            return $item instanceof InstanceIdentifier;
        });
        
        assert($validate, new \UnexpectedValueException());
        
        $this->templateId = $templateId;
        
        return $this;
    }
    
    function addTtemplateId(InstanceIdentifier $identifier)
    {
        $this->templateId[] = $identifier;
    }

    function getLanguageCode()
    {
        return $this->languageCode;
    }

    function setLanguageCode(CodedSimple $languageCode)
    {
        $this->languageCode = $languageCode;
        
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
     * @return Component\RootBodyComponent
     */
    public function getRootComponent()
    {
        return $this->rootComponent;
    }
    
    public function getRecordTarget()
    {
        return $this->recordTarget;
    }

    public function setRecordTarget(RecordTarget $recordTarget)
    {
        $this->recordTarget = $recordTarget;
        
        return $this;
    }
    
    public function getAuthor(): Author
    {
        return $this->author;
    }
    
    public function hasAuthor()
    {
        return $this->author !== null;
    }

    public function setAuthor(Author $author)
    {
        $this->author = $author;
        return $this;
    }
    
    /**
     * 
     * @return Custodian
     */
    public function getCustodian()
    {
        return $this->custodian;
    }

    /**
     * 
     * @param Custodian $custodian
     * @return $this
     */
    public function setCustodian(Custodian $custodian)
    {
        $this->custodian = $custodian;
        return $this;
    }

        

    
        
    /**
     *
     * @return \DOMDocument
     */
    public function toDOMDocument(\DOMDocument $dom = null)
    {
        $dom = $dom === null ? new \DOMDocument('1.0', 'UTF-8') : $dom;
        
        $doc = $dom->createElementNS('urn:hl7-org:v3', 'ClinicalDocument');
        $dom->appendChild($doc);
        // set the NS
        $doc->setAttributeNS(
            'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation',
            'urn:hl7-org:v3 CDA.xsd'
        );
        // add typeId
        $doc->appendChild($this->typeId->toDOMElement($dom));
        
        // add templateIds
        foreach ($this->getTemplateId() as $templateId) {
            $doc->appendChild((new Elements\TemplateId($templateId))
                ->toDOMElement($dom));
        }
        
        // add id
        if ($this->getId() !== null) {
            $doc->appendChild($this->getId()->toDOMElement($dom));
        }
        // add code
        if ($this->getCode() !== null) {
            $doc->appendChild($this->getCode()->toDOMElement($dom));
        }
     
        // add title
        if ($this->getTitle() !== null) {
            $doc->appendChild($this->getTitle()->toDOMElement($dom));
        }
        
        //add effective time
        if ($this->getEffectiveTime() !== null) {
            $doc->appendChild($this->getEffectiveTime()->toDOMElement($dom));
        }

        // add confidentialityCode
        if ($this->getConfidentialityCode() !== null) {
            $doc->appendChild($this->confidentialityCode->toDOMElement($dom));
        }
        
        // add language code
        if ($this->getLanguageCode() !== null) {
            $doc->appendChild(
                (new Elements\LanguageCode($this->getLanguageCode()))
                ->toDOMElement($dom));
        }
        
        // add recordTarget
        if ($this->getRecordTarget() !== null) {
            $doc->appendChild($this->recordTarget->toDOMElement($dom));
        }
        
        // add author
        if ($this->hasAuthor()) {
            $doc->appendChild($this->getAuthor()->toDOMElement($dom));
        }
        
        if ($this->getCustodian()) {
            $doc->appendChild($this->getCustodian()->toDOMElement($dom));
        }

        // add components
        if (!$this->getRootComponent()->isEmpty()) {
            $doc->appendChild($this->getRootComponent()->toDOMElement($dom));
        }
        
        return $dom;
    }
}
