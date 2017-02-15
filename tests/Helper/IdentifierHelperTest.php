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
namespace PHPHealth\CDA\Tests\Helper;

use PHPHealth\CDA\Helper\IdentifierHelper;
use PHPHealth\CDA\DataType\Identifier\InstanceIdentifier;

/**
 * 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class IdentifierHelperTest extends \PHPUnit\Framework\TestCase
{
    public function testGenerateUUID()
    {
        $uuid = IdentifierHelper::generateUUID();
        
        $this->markTestSkipped("some elements does not work in the UUID generator");
        
        $this->assertRegExp("/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-4[0-9A-Fa-f]{3}"
            . "-[89AB][0-9A-Fa-f]{3}-[0-9A-Fa-f]{12}$/", $uuid);
    }
    
    public function testGenerateRandomIdentifier()
    {
        $ii = IdentifierHelper::generateRandomIdentifier();
        
        $this->assertInstanceOf(InstanceIdentifier::class, $ii);
        
        $this->markTestIncomplete("this test must be implemented");
    }
}
