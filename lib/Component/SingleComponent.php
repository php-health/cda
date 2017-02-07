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
namespace PHPHealth\CDA\Component;

use PHPHealth\CDA\HasTypeCode;
use PHPHealth\CDA\ClinicalDocument as CDA;

/**
 * Single component. Must be included in a XMLBodyComponent.
 * 
 * Will return a `<component>` element node.
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class SingleComponent extends AbstractComponent implements HasTypeCode
{
    /**
     *
     * @var SingleComponent\Section[]
     */
    private $sections = array();
    
    /**
     * 
     * @return SingleComponent\Section[]
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * 
     * @param SingleComponent\Section[] $sections
     * @return $this
     */
    public function setSections($sections)
    {
        $this->sections = array();
        
        foreach ($sections as $section) {
            $this->addSection($section);
        }
        
        return $this;
    }

    /**
     * 
     * @param \PHPHealth\CDA\Component\SingleComponent\Section $section
     * @return $this
     */
    public function addSection(SingleComponent\Section $section)
    {
        $this->sections[] = $section;
        
        return $this;
    }
    
    public function getTypeCode(): string
    {
        return 'COMP';
    }

    
    public function toDOMElement(\DOMDocument $doc): \DOMElement
    {
        $component = $doc->createElement(CDA::NS_CDA.'component');
        $component->setAttribute(CDA::NS_CDA.'typeCode', $this->getTypeCode());
        
        foreach ($this->getSections() as $section) {
            $component->appendChild($section->toDOMElement($doc));
        }
        
        return $component;
    }
}
