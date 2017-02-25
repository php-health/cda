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
namespace PHPHealth\CDA\DataType\TextAndMultimedia;

use PHPHealth\CDA\DataType\TextAndMultimedia\CharacterString;
use PHPHealth\CDA\Elements\Paragraph;
use PHPHealth\CDA\Elements\Table;

/**
 * A special character string which may contains narrative elements
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class NarrativeString extends CharacterString
{
    /**
     * All the elements collected with the differents `add*` functions.
     * This array is ordered.
     * 
     * @var \DOMElement[]
     */
    private $elements = array();
    
    public function __construct()
    {
        parent::__construct('');
    }
    
    
    private function addElement($type, $content)
    {
        $this->elements[] = [$type, $content];
    }
    
    /**
     * 
     * @param string $content
     * @return $this
     */
    public function addParagraph($content)
    {
        $this->addElement('paragraph', $content);
        
        return $this;
    }
    
    /**
     * 
     * @return Table
     */
    public function createTable()
    {
        $table = new Table($this);
        
        $this->addElement('table', $table);
        
        return $table;
    }
    
    public function setValueToElement(\DOMElement &$el, \DOMDocument $doc = null)
    {
        foreach ($this->elements as list($type, $element)) {
            switch ($type)
            {
                case 'table': 
                    $el->appendChild($element->toDOMElement($doc));
                    break;
                case 'paragraph':
                    $el->appendChild((new Paragraph($element))
                            ->toDOMElement($doc));
                    break;
                default:
                    // this should not happen
                    throw new \LogicException();
            }
            
        }
    }

}
