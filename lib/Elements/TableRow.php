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
use PHPHealth\CDA\Elements\TableCell;
use PHPHealth\CDA\Elements\AbstractTableSection;
use PHPHealth\CDA\Elements\TableHead;
use PHPHealth\CDA\Elements\ReferenceType;

/**
 * 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class TableRow extends AbstractElement
{
    /**
     *
     * @var TableCell[]
     */
    private $cells;
    
    /**
     * a reference to the section this row is attached to
     *
     * @var AbstractTableSection
     */
    private $section;
    
    /**
     *
     * @var Reference 
     */
    private $reference;
    
    function __construct(AbstractTableSection $section = null)
    {
        $this->section = $section;
    }
    
    /**
     * 
     * @param string $content
     * @return TableCell
     */
    function createCell($content) 
    {
        $cell = new TableCell(
            $this->getSection() instanceof TableHead ? TableCell::TH : TableCell::TD, 
            $this, 
            $content);
        
        $this->addCell($cell);
        
        return $cell;
    }
    
    /**
     * 
     * @param TableCell $cell
     * @return $this
     */
    public function addCell(TableCell $cell)
    {
        $cell->setRow($this);
        
        $this->cells[] = $cell;
        
        return $this;
    }

    /**
     * 
     * @return TableCell[]
     */
    function getCells(): array
    {
        return $this->cells;
    }

    function getSection(): AbstractTableSection
    {
        return $this->section;
    }

    function setCells(array $cells)
    {
        $this->cells = $cells;
        return $this;
    }

    function setSection(AbstractTableSection $section)
    {
        $this->section = $section;
        return $this;
    }
    
    /**
     * 
     * @return Reference
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
        return count($this->getCells()) > 0;
    }

        
    protected function getElementTag(): string
    {
        return 'tr';
    }

    public function toDOMElement(\DOMDocument $doc): \DOMElement
    {
        $el = $this->createElement($doc);
        
        if ($this->getReference() !== null) {
            $this->getReference()->setValueToElement($el, $doc);
        }
        
        foreach ($this->getCells() as $cell) {
            $el->appendChild($cell->toDOMElement($doc));
        }
        
        return $el;
    }
}
