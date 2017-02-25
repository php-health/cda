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
namespace PHPHealth\CDA\Elements;

use PHPHealth\CDA\Elements\AbstractElement;
use PHPHealth\CDA\Elements\TableRow;
use PHPHealth\CDA\Elements\ReferenceType;

/**
 * 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class TableCell extends AbstractElement
{
    /**
     *
     * @var string
     */
    private $content;
    
    /**
     *
     * @var ReferenceType
     */
    private $reference;
    
    /**
     *
     * @var TableRow
     */
    private $row;
    
    /**
     * a string determining if the row is th or td
     *
     * @var string
     */
    private $level;
    
    const TH = 'th';
    const TD = 'td';
    
    
    function __construct($level, TableRow $row = null, $content = '')
    {
        $this->setContent($content);
        $this->level = $level;
    }
    
    /**
     * 
     * @return string
     */
    function getContent()
    {
        return $this->content;
    }

    /**
     * 
     * @param string $content
     * @return $this
     */
    function setContent($content)
    {
        $this->content = $content;
        
        return $this;
    }
    
    /**
     * 
     * @return TableRow
     */
    function getRow(): TableRow
    {
        return $this->row;
    }

    /**
     * 
     * @param TableRow $row
     * @return $this
     */
    function setRow(TableRow $row)
    {
        $this->row = $row;
        
        return $this;
    }
    
    /**
     * 
     * @return ReferenceType
     */
    function getReference()
    {
        return $this->reference;
    }

    /**
     * 
     * @param ReferenceType $reference
     * @return $this
     */
    function setReference(ReferenceType $reference)
    {
        $this->reference = $reference;
        
        return $this;
    }

    
    /**
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->getContent());
    }
        
    protected function getElementTag(): string
    {
        return $this->level;
    }

    public function toDOMElement(\DOMDocument $doc): \DOMElement
    {
        $el = $this->createElement($doc);
        
        if ($this->getReference() !== null) {
            $this->getReference()->setValueToElement($el, $doc);
        }
        
        $el->appendChild($doc->createTextNode($this->getContent()));
        
        return $el;
    }
}
