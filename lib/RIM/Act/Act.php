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
namespace PHPHealth\CDA\RIM\Act;

use PHPHealth\CDA\Elements\AbstractElement;
use PHPHealth\CDA\HasClassCode;
use PHPHealth\CDA\HasMoodCodeInterface;
use PHPHealth\CDA\DataType\Collection\Set;
use PHPHealth\CDA\DataType\Code\CodedWithEquivalents;
use PHPHealth\CDA\DataType\Boolean\Boolean;
use PHPHealth\CDA\DataType\TextAndMultimedia\EncapsuledData;
use PHPHealth\CDA\DataType\Code\StatusCode;
use PHPHealth\CDA\DataType\Identifier\InstanceIdentifier;
use PHPHealth\CDA\Elements\Code;
use PHPHealth\CDA\DataType\Code\CodedValue;

/**
 * A record of something that is being done, has been done, can be done, 
 * or is intended or requested to be done.
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class Act extends AbstractElement implements HasClassCode, HasMoodCodeInterface
{
    /**
     * A unique identifier for the Act.
     *
     * @var Set
     */
    protected $ids;
    
    /**
     *
     * @var CodedValue
     */
    protected $code;
    
    /**
     *
     * @var Boolean
     */
    protected $negationInd;
    
    /**
     *
     * @var EncapsuledData
     */
    protected $text;
    
    /**
     *
     * @var StatusCode
     */
    protected $statusCode;
    
    protected $effectiveTime = array();
    
    /**
     *
     * @var array 
     */
    protected $templateIds;
    
    protected $moodCode = 'EVN';
    
    
    public function getIds()
    {
        return $this->ids;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getNegationInd(): Boolean
    {
        return $this->negationInd;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getEffectiveTime()
    {
        return $this->effectiveTime;
    }
    
    public function getTemplateIds()
    {
        return $this->templateIds;
    }

    
    public function setIds(Set $ids)
    {
        $this->ids = $ids;
        return $this;
    }

    public function setCode(CodedValue $code)
    {
        $this->code = $code;
        
        return $this;
    }

    public function setNegationInd(Boolean $negationInd)
    {
        $this->negationInd = $negationInd;
        return $this;
    }

    public function setText(EncapsuledData $text)
    {
        $this->text = $text;
        return $this;
    }

    public function setStatusCode(StatusCode $statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setEffectiveTime($effectiveTime, $append = true)
    {
        if ($append) {
            $this->effectiveTime[] = $effectiveTime;
        } else {
            $this->effectiveTime = array($effectiveTime);
        }
        
        return $this;
    }

    /**
     * 
     * @param InstanceIdentifier[] $templateIds
     * @return $this
     */
    public function setTemplateIds(array $templateIds)
    {
        // check that each element is an instance of InstanceIdentifier
        $result = \array_reduce($templateIds, function ($carry, $current) {
            if ($carry === false) {
                return false;
            }
            
            return $current instanceof InstanceIdentifier;
        });
        
        if ($result === false) {
            throw new \RuntimeException(sprintf("the templateIds must be "
                . "instance of %s", InstanceIdentifier::class));
        }
        
        $this->templateIds = $templateIds;
        
        
        return $this;
    }

    public function addTemplateId(InstanceIdentifier $id)
    {
        $this->templateIds[] = $id;
        
        return $this;
    }
            
    public function getClassCode(): string
    {
        return 'ACT';
    }
    
    public function getMoodCode()
    {
        return $this->moodCode;
    }

    protected function getElementTag(): string
    {
        return 'act';
    }

    public function toDOMElement(\DOMDocument $doc): \DOMElement
    {
        $el = $this->createElement($doc);
        
        if ($this->getTemplateIds() !== null) {
            foreach ($this->templateIds as $id) {
                $el->appendChild((new TemplateId($id))->toDOMElement($doc));
            }
        }
        
        if ($this->getText() !== null) {
            $el->appendChild((new Text($this->getText()))->toDOMElement($doc));
        }
        
        if ($this->getCode() !== null) {
            $el->appendChild((new Code($this->getCode()))->toDOMElement($doc));
        }
        
        return $el;
    }

}
