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
use Prototype\Compiler\Internal\Ir\Field;
use Prototype\Compiler\Internal\Ir\Message;
use Prototype\Compiler\Internal\Ir\Scalar;
use Prototype\Compiler\Internal\Ir\Trace\TypeStorage;
use Prototype\Compiler\Internal\Ir\Type\ScalarType;
use Prototype\Compiler\Internal\Ir\Validate\AllMessageFieldNamesAreUnique;
use Prototype\Compiler\Internal\Ir\Validate\ConstraintViolated;

#[CoversClass(AllMessageFieldNamesAreUnique::class)]
final class AllMessageFieldNamesAreUniqueTest extends TestCase
{
    public function testConstraintViolated(): void
    {
        self::expectException(ConstraintViolated::class);
        self::expectExceptionMessage('Message "Test" has fields with the same name "fieldA".');

        $validator = new AllMessageFieldNamesAreUnique();
        $validator->afterMessageVisited(
            new Message('Test', TypeStorage::root('Test'), [
                new Field('field_a', new ScalarType(Scalar::string), 1),
                new Field('field_a', new ScalarType(Scalar::fixed32), 2),
            ]),
        );
    }

    public function testValid(): void
    {
        $validator = new AllMessageFieldNamesAreUnique();
        $validator->afterMessageVisited(
            new Message('Test', TypeStorage::root('Test'), [
                new Field('field_a', new ScalarType(Scalar::string), 1),
                new Field('field_b', new ScalarType(Scalar::fixed32), 2),
            ]),
        );

        self::assertTrue(true);
    }
}
