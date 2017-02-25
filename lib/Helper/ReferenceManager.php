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
 * Manages references inside a document.
 * 
 * Each `ClinicalDocument` has its own `ReferenceManager`, which help to manage references across documents.
 * 
 * `ReferenceType` may be added on some elements to create a reference :
 * ```
 * $doc = new ClinicalDocument();
 * 
 * $refManager = $doc->getReferenceManager();
 * 
 * // create an element 'element' which may have a reference
 * 
 * $element->setReference($refManager->getReferenceType('my_reference'));
 * 
 * // will create <element ID="my_reference">blabla</element>
 * 
 * // add the reference in a text
 * 
 * $text->setText($refManager->getReferenceElement('my_reference'));
 * // will create <text><reference value="my_reference" /></text>
 * 
 * ```
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
    
    /**
     * Will create a reference inside the manager.
     * 
     * 
     * @param string $name will be replaced by an unique id if not set
     */
    public function createReference($name = null)
    {
        $ref = $name === null ? \uniqid() : $name;
        
        $this->typeReferences[$ref] = new ReferenceType($ref);
        $this->elementReferences[$ref] = new ReferenceElement($ref);
    }
    
    /**
     * Get the Reference type for the given $ref
     * 
     * If $ref does not exist as reference, it will be created.
     * 
     * @param string $ref
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
     * Get the ReferenceElement for the given $ref
     * 
     * If $ref does not exists as reference, it will be created.
     * 
     * @param string $ref
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
