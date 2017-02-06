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
namespace PHPHealth\CDA\RIM\Role;

use PHPHealth\CDA\RIM\Entity\Entity;
use PHPHealth\CDA\DataType\Code\CodedSimple;
use PHPHealth\CDA\RIM\Entity\RepresentedCustodianOrganization;

/**
 *
 *
 * @author julien
 */
class AssignedCustodian extends Entity
{
    /**
     *
     * @var CodedSimple
     */
    protected $classCode = 'ASSIGNED';
    
    /**
     *
     * @var RepresentedCustodianOrganization 
     */
    protected $custodianOrganization;
    
    public function __construct(RepresentedCustodianOrganization $custodianOrganization)
    {
        $this->setCustodianOrganization($custodianOrganization);
    }
    
    /**
     * 
     * @return RepresentedCustodianOrganization
     */
    public function getCustodianOrganization(): RepresentedCustodianOrganization
    {
        return $this->custodianOrganization;
    }

    /**
     * 
     * @param RepresentedCustodianOrganization $custodianOrganization
     * @return $this
     */
    public function setCustodianOrganization(RepresentedCustodianOrganization $custodianOrganization)
    {
        $this->custodianOrganization = $custodianOrganization;
        
        return $this;
    }
    
    protected function getElementTag(): string
    {
        return 'assignedCustodian';
    }

    public function getDefaultClassCode()
    {
        return $this->classCode;
    }

    public function toDOMElement(\DOMDocument $doc): \DOMElement
    {
        $el = $this->createElement($doc);
        
        $el->appendChild($this->getCustodianOrganization()->toDOMElement($doc));
        
        return $el;
    }
}
