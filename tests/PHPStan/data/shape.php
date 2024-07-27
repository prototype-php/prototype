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

/** @var array{i32: int32, u32: uint32, f32: fixed32, si32: sint32, sif32: sfixed32, i64: int64, u64: uint64, f64: fixed64, si64: sint64, sf64: sfixed64, uuid: bytes} $shape */
$shape = [];

assertType('array{i32: int<0, 4294967295>, u32: int<0, 4294967295>, f32: int<0, 4294967295>, si32: int<-2147483648, 2147483647>, sif32: int<-2147483648, 2147483647>, i64: int<0, max>, u64: int<0, max>, f64: int<0, max>, si64: int, sf64: int, uuid: string}', $shape);

/** @var array{i32: list<int32>} $shape */
$shape = [];

assertType('array{i32: array<int, int<0, 4294967295>>}', $shape);

/** @var array{nested: array{i32: list<bytes>}} $shape */
$shape = [];

assertType('array{nested: array{i32: array<int, string>}}', $shape);
