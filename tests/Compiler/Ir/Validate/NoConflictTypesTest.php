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

namespace Prototype\Tests\Compiler\Ir\Validate;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Prototype\Compiler\Internal\Ir\Enum;
use Prototype\Compiler\Internal\Ir\EnumCase;
use Prototype\Compiler\Internal\Ir\Message;
use Prototype\Compiler\Internal\Ir\Proto;
use Prototype\Compiler\Internal\Ir\Trace\TypeStorage;
use Prototype\Compiler\Internal\Ir\Validate\ConstraintViolated;
use Prototype\Compiler\Internal\Ir\Validate\EnumContainsZeroVariant;
use Prototype\Compiler\Internal\Ir\Validate\NoConflictTypes;

#[CoversClass(NoConflictTypes::class)]
final class NoConflictTypesTest extends TestCase
{
    public function testConstraintViolated(): void
    {
        self::expectException(ConstraintViolated::class);
        self::expectExceptionMessage('"test.api.v1.Request" is already defined in file "test.api.v1".');

        $validator = new NoConflictTypes();
        $validator->afterProtoResolved(
            [
                'test.api.v1' => new Proto('test.api.v1', [
                    'Request.Message' => new Message('Request', TypeStorage::root('Request')),
                    'Request.Enum' => new Enum('Request', []),
                ]),
            ],
        );
    }

    public function testValid(): void
    {
        $validator = new NoConflictTypes();
        $validator->afterProtoResolved(
            [
                'test.api.v1' => new Proto('test.api.v1', [
                    'Request.Message' => new Message('Request', TypeStorage::root('Request')),
                    'Request.Enum' => new Enum('RequestType', []),
                ]),
            ],
        );

        self::assertTrue(true);
    }
}
