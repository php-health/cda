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
namespace PHPHealth\CDA\RIM\Entity;

use PHPHealth\CDA\Elements\Id;
use PHPHealth\CDA\DataType\Collection\Set;
use PHPHealth\CDA\DataType\Code\CodedSimple;

/**
 * 
 *
 * @author julien
 */
abstract class Organization extends Entity
{
    /**
     *
     * @var CodedSimple
     */
    protected $classCode = 'ORG';
    
    public function __construct(Set $names, Set $ids)
    {
        $this->setNames($names);
        $this->setId($ids);
    }

    public function getDefaultClassCode()
    {
        return $this->classCode;
    }

    public function toDOMElement(\DOMDocument $doc): \DOMElement
    {
        $el = $this->createElement($doc);
        
        foreach ($this->getId() as $idValue) {
            $idElement = new Id($idValue);
            $el->appendChild($idElement->toDomElement($doc));
        }
        
        foreach ($this->getNames()->get() as $name) {
            /* @var $name \PHPHealth\CDA\DataType\Name\EntityName */
            $name->setValueToElement($el, $doc);
        }
        
        return $el;
    }
}
