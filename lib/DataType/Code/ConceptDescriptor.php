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

namespace PHPHealth\CDA\DataType\Code;

use PHPHealth\CDA\ClinicalDocument as CDA;

/**
 * A CD represents any kind of concept usually by giving a code defined in a 
 * code system. A CD can contain the original text or phrase that served as the 
 * basis of the coding and one or more translations into different coding systems. 
 * A CD can also contain qualifiers to describe, e.g., the concept of a 
 * "left foot" as a postcoordinated term built from the primary code "FOOT" 
 * and the qualifier "LEFT". In cases of an exceptional value, the CD need 
 * not contain a code but only the original text describing that concept. 
 * 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class ConceptDescriptor extends \PHPHealth\CDA\DataType\AnyType
{
    
    private $qualifier;
        
    private $translation;
    
    private $codeSystem;
    
    private $codeSystemName;
    
    private $displayName;
    
    private $originalText;
    
    /**
     *
     * @var string
     */
    private $code;
    
    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        
        return $this;
    }   
    
    public function getCodeSystem()
    {
        return $this->codeSystem;
    }
    
    public function hasCodeSystem()
    {
        return !empty($this->getCodeSystem());
    }

    public function getCodeSystemName()
    {
        return $this->codeSystemName;
    }
    
    public function hasCodeSystemName()
    {
        return !empty($this->getCodeSystemName());
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }
    
    public function hasDisplayName()
    {
        return !empty($this->getDisplayName());
    }

    public function getOriginalText()
    {
        return $this->originalText;
    }
    
    public function hasOriginalText()
    {
        return !empty($this->getOriginalText());
    }

    public function setCodeSystem($codeSystem)
    {
        $this->codeSystem = $codeSystem;
        return $this;
    }

    public function setCodeSystemName($codeSystemName)
    {
        $this->codeSystemName = $codeSystemName;
        return $this;
    }

    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }

    public function setOriginalText($originalText)
    {
        $this->originalText = $originalText;
        return $this;
    }

    public function setValueToElement(\DOMElement &$el, \DOMDocument $doc = null)
    {
        $el->setAttribute(CDA::NS_CDA."code", $this->getCode());
        
        if ($this->hasDisplayName()) {
            $el->setAttribute("displayName", $this->getDisplayName());
        }
        
        if ($this->hasCodeSystem()) {
            $el->setAttribute("codeSystem", $this->getCodeSystem());
        }
        
        if ($this->hasCodeSystemName()) {
            $el->setAttribute("codeSystemName", $this->getCodeSystemName());
        }
    }
}
