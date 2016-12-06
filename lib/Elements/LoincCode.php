<?php
/*
 * The MIT License
 *
 * Copyright 2016 julien.fastre@champs-libres.coop
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

use PHPHealth\CDA\DataType\Code\CodedValue;

/**
 * Template for Loinc Code
 *
 * @author julien
 */
class LoincCode extends Code
{
    const CODE_SYSTEM = '2.16.840.1.113883.6.1';
    const CODE_SYSTEM_NAME = 'LOINC';
    
    public function __construct($code, $displayName)
    {
        $this->setCodedValue(self::createCodedValue($code, $displayName));
    }
    
    public static function createCodedValue($code, $displayName)
    {
        $codedValue = new CodedValue();
        
        return $codedValue->setCode($code)
            ->setDisplayName($displayName)
            ->setCodeSystem(self::CODE_SYSTEM)
            ->setCodeSystemName(self::CODE_SYSTEM_NAME);
    }

}
