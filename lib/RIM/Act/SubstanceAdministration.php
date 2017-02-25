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

use PHPHealth\CDA\DataType\Code\CodedWithEquivalents;
use PHPHealth\CDA\DataType\Collection\Set;
use PHPHealth\CDA\DataType\Collection\Interval;
use PHPHealth\CDA\DataType\Quantity\PhysicalQuantity\PhysicalQuantity;
use PHPHealth\CDA\Elements\TemplateId;
use PHPHealth\CDA\Elements\Text;
use PHPHealth\CDA\Elements\EffectiveTime;
use PHPHealth\CDA\Elements\RouteCode;
use PHPHealth\CDA\RIM\Participation\Consumable;
use PHPHealth\CDA\Elements\DoseQuantity;
use PHPHealth\CDA\DataType\Identifier\InstanceIdentifier;
use PHPHealth\CDA\DataType\TextAndMultimedia\CharacterString;

/**
 * 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class SubstanceAdministration extends Act
{
    /**
     *
     * @var CodedWithEquivalents
     */
    private $routeCode;
    
    /**
     *
     * @var Set|CodedWithEquivalents
     */
    private $approachSiteCode;
    
    /**
     *
     * @var Interval|PhysicalQuantity
     */
    private $doseQuantity;
    
    /**
     *
     * @var Interval|PhysicalQuantity
     */
    private $rateQuantity;
    
    /**
     *
     * @var Consumable 
     */
    private $consumable;
    
    
    public function getClassCode(): string
    {
        return 'SBADM';
    }

    protected function getElementTag(): string
    {
        return 'substanceAdministration';
    }
    
    /**
     * 
     * @return CodedWithEquivalents
     */
    public function getRouteCode(): CodedWithEquivalents
    {
        return $this->routeCode;
    }

    /**
     * 
     * @return Set|CodedWithEquivalents
     */
    public function getApproachSiteCode()
    {
        return $this->approachSiteCode;
    }

    /**
     * 
     * @return Interval|PhysicalQuantity
     */
    public function getDoseQuantity()
    {
        return $this->doseQuantity;
    }

    /**
     * 
     * @return Interval|PhysicalQuantity
     */
    public function getRateQuantity()
    {
        return $this->rateQuantity;
    }
    
    /**
     * 
     * @return Consumable
     */
    public function getConsumable()
    {
        return $this->consumable;
    }

    /**
     * 
     * @param CodedWithEquivalents $routeCode
     * @return $this
     */
    public function setRouteCode(CodedWithEquivalents $routeCode)
    {
        $this->routeCode = $routeCode;
        return $this;
    }

    /**
     * 
     * @param Set|CodedWithEquivalents $approachSiteCode
     * @return $this
     */
    public function setApproachSiteCode($approachSiteCode)
    {
        $this->approachSiteCode = $approachSiteCode;
        return $this;
    }

    /**
     * 
     * @param Interval|PhysicalQuantity $doseQuantity
     * @return $this
     */
    public function setDoseQuantity($doseQuantity)
    {
        $this->doseQuantity = $doseQuantity;
        return $this;
    }

    /**
     * 
     * @param Interval|PhysicalQuantity $rateQuantity
     * @return $this
     */
    public function setRateQuantity($rateQuantity)
    {
        $this->rateQuantity = $rateQuantity;
        return $this;
    }
    
    

    /**
     * 
     * @param Consumable $consumable
     * @return $this
     */
    function setConsumable(Consumable $consumable)
    {
        $this->consumable = $consumable;
        return $this;
    }
        
    public function toDOMElement(\DOMDocument $doc): \DOMElement
    {
        $el = $this->createElement($doc);
        
        if ($this->getTemplateIds() !== null) {
            foreach ($this->templateIds as $id) {
                $el->appendChild((new TemplateId($id))->toDOMElement($doc));
            }
        }
        
        if ($this->getIds() !== null) {
            foreach ($this->getIds()->getIterator() as $id) {
                /* @var $id InstanceIdentifier */
                $el->appendChild((new \PHPHealth\CDA\Elements\Id($id))
                    ->toDOMElement($doc));
            }
        }
        
        if ($this->getText() !== null) {
            $el->appendChild((new Text($this->getText()))->toDOMElement($doc));
        }
        
        if ($this->getStatusCode() !== null) {
            $el->appendChild(
                (new \PHPHealth\CDA\Elements\StatusCode($this->getStatusCode()))
                    ->toDOMElement($doc)
                );
        }
        
        $first = true;
        foreach ($this->getEffectiveTime() as $time) {
            $effectiveTime = new EffectiveTime($time);
            
            if (! $first) {
                $effectiveTime->setOperatorAppend();
            }
            
            $el->appendChild($effectiveTime
                ->toDOMElement($doc));
            
            $first = false;
        }
        
        if ($this->getRouteCode() !== null) {
            $el->appendChild((new RouteCode($this->getRouteCode()))
                ->toDOMElement($doc));
        }
        
        if ($this->getDoseQuantity() !== null) {
            $el->appendChild((new DoseQuantity($this->getDoseQuantity()))
                ->toDOMElement($doc));
        }
        
        if ($this->getConsumable() !== null) {
            $el->appendChild($this->getConsumable()->toDOMElement($doc));
        }
        
        return $el;
    }

}
