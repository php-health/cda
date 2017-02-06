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
namespace PHPHealth\CDA\RIM\Participation;

use PHPHealth\CDA\DataType\Quantity\DateAndTime\TimeStamp;
use PHPHealth\CDA\RIM\Role\AssignedAuthor;
use PHPHealth\CDA\Elements\Time;

/**
 * 
 *
 * @author julien.fastre@champs-libres.coop
 */
class Author extends Participation
{
    /**
     *
     * @var TimeStamp
     */
    private $time;
    
    /**
     *
     * @var AssignedAuthor[]
     */
    private $assignedAuthors = array();
    
    public function __construct(
        TimeStamp $time,
        $assignedAuthors
    ) {
        $this->setTime($time);
        $this->setAssignedAuthors($assignedAuthors);
    }
    
    public function getTime(): TimeStamp
    {
        return $this->time;
    }

    public function getAssignedAuthors(): array
    {
        return $this->assignedAuthors;
    }

    public function setTime(TimeStamp $time)
    {
        $this->time = $time;
        
        return $this;
    }

    public function setAssignedAuthors($assignedAuthors)
    {
        $this->assignedAuthors = is_array($assignedAuthors) ? $assignedAuthors 
            : array($assignedAuthors);
        
        return $this;
    }

    protected function getElementTag(): string
    {
        return 'author';
    }
    
    public function getTypeCode()
    {
        return 'AUT';
    }

    public function toDOMElement(\DOMDocument $doc): \DOMElement
    {
        $el = $this->createElement($doc);
        
        $el->appendChild((new Time($this->time))->toDOMElement($doc));
        
        foreach ($this->assignedAuthors as $assignedAuthor) {
            $el->appendChild($assignedAuthor->toDOMElement($doc));
        }
        
        return $el;
    }
}
