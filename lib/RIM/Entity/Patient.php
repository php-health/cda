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

namespace PHPHealth\CDA\RIM\Entity;

use PHPHealth\CDA\ElementInterface;
use PHPHealth\CDA\ClinicalDocument as CDA;
use PHPHealth\CDA\DataType\Quantity\DateAndTime\TimeStamp;
use PHPHealth\CDA\DataType\Collection\Set;
use PHPHealth\CDA\Elements\AdministrativeGenderCode;
use PHPHealth\CDA\DataType\Code\CodedValue;
use PHPHealth\CDA\Elements\BirthTime;

/**
 * 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class Patient implements ElementInterface
{
    /**
     *
     * @var Set|\PHPHealth\CDA\DataType\Name\PersonName[] 
     */
    protected $names;
    
    /**
     *
     * @var TimeStamp
     */
    protected $birthtime;
    
    /**
     *
     * @var CodedValue
     */
    protected $administrativeGenderCode;
    
    public function __construct(
            Set $names = null,
            TimeStamp $birthtime = null,
            CodedValue $administrativeGenderCode = null
    ) {
        if ($names !== null) {
            $this->setNames($names);
        }
        if ($birthtime !== null) {
            $this->setBirthtime($birthtime);
        }
        if ($administrativeGenderCode !== null) {
            $this->setAdministrativeGenderCode($administrativeGenderCode);
        }
    }
    
    public function getNames()
    {
        return $this->names;
    }

    public function getBirthtime()
    {
        return $this->birthtime;
    }

    public function setNames(Set $names)
    {
        $this->names = $names;
        
        return $this;
    }

    public function setBirthtime(TimeStamp $birthtime)
    {
        $this->birthtime = $birthtime;
        
        return $this;
    }

    public function getAdministrativeGenderCode()
    {
        return $this->administrativeGenderCode;
    }

    public function setAdministrativeGenderCode(CodedValue $administrativeGenderCode)
    {
        $this->administrativeGenderCode = $administrativeGenderCode;
        
        return $this;
    }

        
    public function toDOMElement(\DOMDocument $doc)
    {
        $el = $doc->createElement(CDA::NS_CDA.'patient');
        //add names
        if ($this->getNames() !== null) {
            $this->getNames()->setValueToElement($el, $doc);
        }
        // add administrative gender code
        if ($this->getAdministrativeGenderCode() !== null) {
            $adm = new AdministrativeGenderCode($this->administrativeGenderCode);
            $el->appendChild($adm->toDOMElement($doc));
        }
        // add birthtime
        if ($this->getBirthtime() !== null) {
            $bir = new BirthTime($this->birthtime);
            $el->appendChild($bir->toDOMElement($doc));
        }
        
        return $el;
    }

}
