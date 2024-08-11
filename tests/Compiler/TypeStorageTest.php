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

namespace Prototype\Tests\Compiler;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Prototype\Compiler\Internal\Ir\Trace\TypeStorage;

#[CoversClass(TypeStorage::class)]
final class TypeStorageTest extends TestCase
{
    /**
     * @return iterable<array-key, array{non-empty-string, ?non-empty-string}>
     */
    public static function typeMapFromTypePerspective(): iterable
    {
        yield 'api.v1.test.Request -> Request' => [
            'api.v1.test.Request',
            'Request',
        ];

        yield 'Request -> Request' => [
            'Request',
            'Request',
        ];

        yield 'Task-> RequestTask' => [
            'Task',
            'RequestTask',
        ];

        yield 'Task.Request -> null' => [
            'Task.Request',
            null,
        ];

        yield 'Type.Request -> null' => [
            'Type.Request',
            null,
        ];

        yield 'api.v1.test.Request.Task -> RequestTask' => [
            'api.v1.test.Request.Task',
            'RequestTask',
        ];

        yield 'Request.Task -> RequestTask' => [
            'Request.Task',
            'RequestTask',
        ];

        yield 'Task -> RequestTask' => [
            'Task',
            'RequestTask',
        ];

        yield 'api.v1.test.Task -> null' => [
            'api.v1.test.Task',
            null,
        ];

        yield 'api.v1.test.Request.Task.Type -> RequestTaskType' => [
            'api.v1.test.Request.Task.Type',
            'RequestTaskType',
        ];

        yield 'Request.Task.Type -> RequestTaskType' => [
            'Request.Task.Type',
            'RequestTaskType',
        ];

        yield 'Task.Type -> RequestTaskType' => [
            'Task.Type',
            'RequestTaskType',
        ];

        yield 'Type -> RequestTaskType' => [
            'Type',
            'RequestTaskType',
        ];

        yield 'Request.Type -> null' => [
            'Request.Type',
            null,
        ];

        yield 'api.v1.test.Type -> null' => [
            'api.v1.test.Type',
            null,
        ];
    }

    /**
     * @param non-empty-string $typeName
     * @param ?non-empty-string $name
     */
    #[DataProvider('typeMapFromTypePerspective')]
    public function testTypeResolveFromTypePerspective(string $typeName, ?string $name): void
    {
        $storage = TypeStorage::root('api.v1.test');
        $request = $storage->fork('Request');
        $task = $request->fork('Task');
        $type = $task->fork('Type');

        self::assertSame($name, $type->resolveType($typeName));
    }

    /**
     * @return iterable<array-key, array{non-empty-string, ?non-empty-string}>
     */
    public static function typeMapFromTaskPerspective(): iterable
    {
        yield 'api.v1.test.Request -> Request' => [
            'api.v1.test.Request',
            'Request',
        ];

        yield 'Request -> Request' => [
            'Request',
            'Request',
        ];

        yield 'Task.Request -> null' => [
            'Task.Request',
            null,
        ];

        yield 'Type.Request -> null' => [
            'Type.Request',
            null,
        ];

        yield 'api.v1.test.Request.Task -> RequestTask' => [
            'api.v1.test.Request.Task',
            'RequestTask',
        ];

        yield 'Request.Task -> RequestTask' => [
            'Request.Task',
            'RequestTask',
        ];

        yield 'Task -> RequestTask' => [
            'Task',
            'RequestTask',
        ];

        yield 'api.v1.test.Task -> null' => [
            'api.v1.test.Task',
            null,
        ];

        yield 'api.v1.test.Request.Task.Type -> RequestTaskType' => [
            'api.v1.test.Request.Task.Type',
            'RequestTaskType',
        ];

        yield 'Request.Task.Type -> RequestTaskType' => [
            'Request.Task.Type',
            'RequestTaskType',
        ];

        yield 'Task.Type -> RequestTaskType' => [
            'Task.Type',
            'RequestTaskType',
        ];

        yield 'Type -> RequestTaskType' => [
            'Type',
            'RequestTaskType',
        ];

        yield 'Request.Type -> null' => [
            'Request.Type',
            null,
        ];
    }

    /**
     * @param non-empty-string $typeName
     * @param ?non-empty-string $name
     */
    #[DataProvider('typeMapFromTaskPerspective')]
    public function testTypeResolveFromTaskPerspective(string $typeName, ?string $name): void
    {
        $storage = TypeStorage::root('api.v1.test');
        $request = $storage->fork('Request');
        $task = $request->fork('Task');
        $task->fork('Type');

        self::assertSame($name, $task->resolveType($typeName));
    }

    /**
     * @return iterable<array-key, array{non-empty-string, ?non-empty-string}>
     */
    public static function typeMapFromRequestPerspective(): iterable
    {
        yield 'api.v1.test.Request -> Request' => [
            'api.v1.test.Request',
            'Request',
        ];

        yield 'Request -> Request' => [
            'Request',
            'Request',
        ];

        yield 'Task.Request -> null' => [
            'Task.Request',
            null,
        ];

        yield 'Type.Request -> null' => [
            'Type.Request',
            null,
        ];

        yield 'api.v1.test.Request.Task -> RequestTask' => [
            'api.v1.test.Request.Task',
            'RequestTask',
        ];

        yield 'Request.Task -> RequestTask' => [
            'Request.Task',
            'RequestTask',
        ];

        yield 'Task -> RequestTask' => [
            'Task',
            'RequestTask',
        ];

        yield 'api.v1.test.Task -> null' => [
            'api.v1.test.Task',
            null,
        ];

        yield 'api.v1.test.Request.Task.Type -> RequestTaskType' => [
            'api.v1.test.Request.Task.Type',
            'RequestTaskType',
        ];

        yield 'Request.Task.Type -> RequestTaskType' => [
            'Request.Task.Type',
            'RequestTaskType',
        ];

        yield 'Task.Type -> RequestTaskType' => [
            'Task.Type',
            'RequestTaskType',
        ];

        yield 'Type -> null' => [
            'Type',
            null,
        ];

        yield 'Request.Type -> null' => [
            'Request.Type',
            null,
        ];
    }

    /**
     * @param non-empty-string $typeName
     * @param ?non-empty-string $name
     */
    #[DataProvider('typeMapFromRequestPerspective')]
    public function testTypeResolveFromRequestPerspective(string $typeName, ?string $name): void
    {
        $storage = TypeStorage::root('api.v1.test');
        $request = $storage->fork('Request');
        $task = $request->fork('Task');
        $task->fork('Type');

        self::assertSame($name, $request->resolveType($typeName));
    }

    /**
     * @return iterable<array-key, array{non-empty-string, ?non-empty-string}>
     */
    public static function typeMapFromGlobalPerspective(): iterable
    {
        yield 'api.v1.test.Request -> Request' => [
            'api.v1.test.Request',
            'Request',
        ];

        yield 'Request -> Request' => [
            'Request',
            'Request',
        ];

        yield 'Task.Request -> null' => [
            'Task.Request',
            null,
        ];

        yield 'Type.Request -> null' => [
            'Type.Request',
            null,
        ];

        yield 'api.v1.test.Request.Task -> RequestTask' => [
            'api.v1.test.Request.Task',
            'RequestTask',
        ];

        yield 'Request.Task -> RequestTask' => [
            'Request.Task',
            'RequestTask',
        ];

        yield 'Task -> null' => [
            'Task',
            null,
        ];

        yield 'api.v1.test.Task -> null' => [
            'api.v1.test.Task',
            null,
        ];

        yield 'api.v1.test.Request.Task.Type -> RequestTaskType' => [
            'api.v1.test.Request.Task.Type',
            'RequestTaskType',
        ];

        yield 'Request.Task.Type -> RequestTaskType' => [
            'Request.Task.Type',
            'RequestTaskType',
        ];

        yield 'Task.Type -> null' => [
            'Task.Type',
            null,
        ];

        yield 'Type -> null' => [
            'Type',
            null,
        ];

        yield 'Request.Type -> null' => [
            'Request.Type',
            null,
        ];
    }

    /**
     * @param non-empty-string $typeName
     * @param ?non-empty-string $name
     */
    #[DataProvider('typeMapFromGlobalPerspective')]
    public function testTypeResolveFromGlobalPerspective(string $typeName, ?string $name): void
    {
        $storage = TypeStorage::root('api.v1.test');
        $request = $storage->fork('Request');
        $task = $request->fork('Task');
        $task->fork('Type');

        self::assertSame($name, $storage->resolveType($typeName));
    }
}
