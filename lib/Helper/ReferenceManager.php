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
namespace PHPHealth\CDA\Helper;

use PHPHealth\CDA\Elements\ReferenceElement;
use PHPHealth\CDA\Elements\ReferenceType;

/**
 * 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class ReferenceManager
{
    /**
     *
     * @var ReferenceType[]
     */
    private $typeReferences = array();
    
    /**
     *
     * @var ReferenceElement[] 
     */
    private $elementReferences = array();
    
    public function createReference($name = null)
    {
        $ref = $name === null ? \uniqid() : $name;
        
        $this->typeReferences[$ref] = new ReferenceType($ref);
        $this->elementReferences[$ref] = new ReferenceElement($ref);
    }
    
    /**
     * 
     * @param type $ref
     * @return ReferenceType
     */
    public function getReferenceType($ref)
    {
        if (! array_key_exists($ref, $this->typeReferences)) {
            $this->createReference($ref);
        }
        
        return $this->typeReferences[$ref];
    }
    
        /**
     * 
     * @param type $ref
     * @return ReferenceElement
     */
    public function getReferenceElement($ref)
    {
        if (! array_key_exists($ref, $this->elementReferences)) {
            $this->createReference($ref);
        }
        
        return $this->elementReferences[$ref];
    }
}
