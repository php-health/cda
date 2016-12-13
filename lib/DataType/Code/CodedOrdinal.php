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

namespace PHPHealth\CDA\DataType\Code;

/**
 * Coded data, where the coding system from which the code comes is ordered.
 * CO adds semantics related to ordering so that models that make use of such
 * domains may introduce model elements that involve statements about the
 * order of the terms in a domain.
 *
 * ```
 * type CodedOrdinal alias CO specializes CV {
 * BL  lessOrEqual(CO o);
 * BL  lessThan(CO o);
 * BL  greaterThan(CO o);
 * BL  greaterOrEqual(CO o);
 * };
 * ```
 *
 * The relative order of CO values need not be independently obvious in their
 * literal representation. It is expected that an application will look up the
 * ordering of these values from some table.
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class CodedOrdinal extends CodedValue
{
    
}
