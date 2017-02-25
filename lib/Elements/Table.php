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
use PHPHealth\CDA\DataType\TextAndMultimedia\NarrativeString;

/**
 * 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class Table extends AbstractElement
{
    /**
     *
     * @var TableHead
     */
    private $thead;
    
    /**
     *
     * @var TableBody
     */
    private $tbody;
    
    /**
     *
     * @var NarrativeString
     */
    private $narrative;
    
    function __construct(NarrativeString $narrative)
    {
        $this->tbody = new TableBody($this);
        $this->thead = new TableHead($this);
        $this->narrative = $narrative;
    }
    
    function getNarrative(): NarrativeString
    {
        return $this->narrative;
    }

    /**
     * 
     * @return \PHPHealth\CDA\Elements\TableHead
     */
    function getThead(): TableHead
    {
        return $this->thead;
    }

    /**
     * 
     * @return \PHPHealth\CDA\Elements\TableBody
     */
    function getTbody(): TableBody
    {
        return $this->tbody;
    }  
    
    protected function getElementTag(): string
    {
        return 'table';
    }

    public function toDOMElement(\DOMDocument $doc): \DOMElement
    {
        $el = $this->createElement($doc);
        
        if (! $this->getThead()->isEmpty()) {
            $el->appendChild($this->getThead()->toDOMElement($doc));
        }
        
        if (! $this->getTbody()->isEmpty()) {
            $el->appendChild($this->getTbody()->toDOMElement($doc));
        }
        
        return $el;
        
    }
}
