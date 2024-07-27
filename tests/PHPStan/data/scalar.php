<?php

/**
 * MIT License
 * Copyright (c) 2024 kafkiansky.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

declare(strict_types=1);

namespace PrototypePHPStanExtensionTest;

use function PHPStan\Testing\assertType;

/** @var int32 $int32 */
$int32 = null;

assertType('int<0, 4294967295>', $int32);

/** @var uint32 $uint32 */
$uint32 = null;

assertType('int<0, 4294967295>', $uint32);

/** @var fixed32 $fixed32 */
$fixed32 = null;

assertType('int<0, 4294967295>', $fixed32);

/** @var sint32 $sint32 */
$sint32 = null;

assertType('int<-2147483648, 2147483647>', $sint32);

/** @var sfixed32 $sfixed32 */
$sfixed32 = null;

assertType('int<-2147483648, 2147483647>', $sfixed32);

/** @var int64 $int64 */
$int64 = null;

assertType('int<0, max>', $int64);

/** @var uint64 $uint64 */
$uint64 = null;

assertType('int<0, max>', $uint64);

/** @var fixed64 $fixed64 */
$fixed64 = null;

assertType('int<0, max>', $fixed64);

/** @var sint64 $sint64 */
$sint64 = null;

assertType('int', $sint64);

/** @var sfixed64 $sfixed64 */
$sfixed64 = null;

assertType('int', $sfixed64);

/** @var bytes $bytes */
$bytes = null;

assertType('string', $bytes);
