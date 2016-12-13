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

namespace PHPHealth\CDA\Elements;

use PHPHealth\CDA\DataType\Identifier\InstanceIdentifier;

/**
 *
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class Id extends AbstractElement
{
    /**
     *
     * @var InstanceIdentifier
     */
    protected $identifier;
    
    
    public function __construct(InstanceIdentifier $identifier)
    {
        $this->setIdentifier($identifier);
    }
    
    
    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function setIdentifier(InstanceIdentifier $identifier)
    {
        $this->identifier = $identifier;
        
        return $this;
    }

    protected function getElementTag()
    {
        return 'id';
    }

    public function toDOMElement(\DOMDocument $doc)
    {
        return $this->createElement($doc, array('identifier'));
    }
}
