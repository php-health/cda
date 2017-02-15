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
use PHPHealth\CDA\DataType\Collection\Interval\AbstractInterval;
use PHPHealth\CDA\DataType\Quantity\PhysicalQuantity\PhysicalQuantity;

/**
 * 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class DoseQuantity extends AbstractElement
{
    /**
     *
     * @var AbstractInterval|PhysicalQuantity
     */
    protected $quantity;
    
    public function __construct($quantity)
    {
        $this->setQuantity($quantity);
    }
    
    /**
     * 
     * @return AbstractInterval|PhysicalQuantity
     */
    function getQuantity()
    {
        return $this->quantity;
    }

    function setQuantity($quantity)
    {
        if (!
            ( 
            $quantity instanceof PhysicalQuantity 
            || 
            $quantity instanceof AbstractInterval
            )
            ) {
            throw new \UnexpectedValueException(sprintf("The value of quantity"
                . " should be an instance of %s or %s", PhysicalQuantity::class,
                AbstractInterval::class));
        }
        
        $this->quantity = $quantity;
        
        return $this;
    }

        
    protected function getElementTag(): string
    {
        return 'doseQuantity';
    }

    public function toDOMElement(\DOMDocument $doc): \DOMElement
    {
        $el = $this->createElement($doc, ['quantity']);
        
        return $el;
    }
}
