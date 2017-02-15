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
namespace PHPHealth\CDA\DataType\Collection\Interval;

use PHPHealth\CDA\ClinicalDocument as CDA;

/**
 * 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class PeriodicIntervalOfTime extends AbstractInterval
{
    /**
     *
     * @var \DateInterval
     */
    protected $period;
    
    /**
     *
     * @var boolean
     */
    protected $institutionSpecified = null;
    
    public function __construct(\DateInterval $period)
    {
        $this->setPeriod($period);
    }
    
    public function getPeriod(): \DateInterval
    {
        return $this->period;
    }

    public function getInstitutionSpecified()
    {
        return $this->institutionSpecified;
    }

    public function setPeriod(\DateInterval $period)
    {
        $this->period = $period;
        
        return $this;
    }

    public function setInstitutionSpecified($institutionSpecified)
    {
        $this->institutionSpecified = $institutionSpecified;
        
        return $this;
    }

    /**
     * return an array where the first element is the unit, and the 
     * second the unit
     */
    protected function processPeriod()
    {
        $seconds = $this->getPeriod()->format('%s');
        $minutes = $this->getPeriod()->format('%i');
        $hours   = $this->getPeriod()->format('%h');
        $days    = $this->getPeriod()->format('%d');
        $months  = $this->getPeriod()->format('%m');
        
        if ($months != 0) {
            return ['mo', $months];
        }
        if ($days   != 0) {
            return ['d', $days];
        }
        if ($hours != 0) {
            return ['h', $hours];
        }
        if ($minutes != 0) {
            return ['min', $minutes];
        }
        if ($seconds != 0) {
            return ['s', $seconds];
        }
    }
        
    public function setValueToElement(\DOMElement &$el, \DOMDocument $doc = null)
    {
        if ($doc === null) {
            throw new \Exception("doc should not be null");
        }
        
        $el->setAttribute('xsi:type', 'PIVL_TS');
        
        if ($this->getInstitutionSpecified() !== null) {
            $el->setAttribute(CDA::NS_CDA.'institutionSpecified', 
                $this->getInstitutionSpecified() ? 'true' : 'false');
        }
        
        list($unit, $value) = $this->processPeriod();
        $period = $doc->createElement(CDA::NS_CDA.'period');
        $period->setAttribute(CDA::NS_CDA.'unit', $unit);
        $period->setAttribute(CDA::NS_CDA.'value', $value);
        
        $el->appendChild($period);
    }
}
