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

use PHPHealth\CDA\HasClassCode;

use PHPHealth\CDA\ClinicalDocument as CDA;

/**
 * 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class XMLBodyComponent extends AbstractComponent implements HasClassCode
{
    /**
     *
     * @var AbstractComponent[]
     */
    private $components = array();
    
    public function getComponents(): array
    {
        return $this->components;
    }

    public function setComponents(array $components)
    {
        $this->components = $components;
        
        return $this;
    }
    
    public function addComponent(SingleComponent $component)
    {
        $this->components[] = $component;
        
        return $this;
    }
    
    public function getClassCode(): string
    {
        return 'DOCBODY';
    }

            
    public function toDOMElement(\DOMDocument $doc): \DOMElement
    {
        $structuredBody = $doc->createElement(CDA::NS_CDA.'structuredBody');
        $structuredBody->setAttribute(CDA::NS_CDA.'classCode',
            $this->getClassCode());
        
        
        foreach ($this->getComponents() as $component) {
            $structuredBody->appendChild($component->toDOMElement($doc));
        }
        
        return $structuredBody;
    }
}
