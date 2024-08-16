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

namespace Prototype\Compiler\Internal\Ir\Validate;

use Prototype\Compiler\Internal\Ir\Hook\AfterProtoResolvedHook;
use Prototype\Compiler\Internal\Ir\Message;
use Prototype\Compiler\Internal\Ir\Proto;
use Prototype\Compiler\Internal\Ir\RpcType;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 */
final class AllServicesUseValidTypes implements AfterProtoResolvedHook
{
    /**
     * {@inheritdoc}
     */
    public function afterProtoResolved(iterable $files): void
    {
        $fileIdx = -1;
        foreach ($files as $file) {
            ++$fileIdx;

            foreach ($file->services as $service) {
                foreach ($service->rpc as $rpc) {
                    $imports = [
                        ...\array_slice([...$files], 0, $fileIdx),
                        ...\array_slice([...$files], $fileIdx + 1),
                    ];

                    if (!self::validateType($rpc->inType, $file, $imports)) {
                        throw new ConstraintViolated(\sprintf('"%s" is not defined in "%s.%s".', $rpc->inType->name, $file->packageName, $service->name));
                    }

                    if (!self::validateType($rpc->outType, $file, $imports)) {
                        throw new ConstraintViolated(\sprintf('"%s" is not defined in "%s.%s".', $rpc->outType->name, $file->packageName, $service->name));
                    }
                }
            }
        }
    }

    /**
     * @param array<non-empty-string, Proto> $files
     */
    private static function validateType(RpcType $type, Proto $file, array $files): bool
    {
        $validate = static function (RpcType $type, Proto $file): bool {
            $typeName = $type->name;

            if (str_starts_with($typeName, $file->packageName)) {
                /** @var non-empty-string $typeName */
                $typeName = substr($typeName, \strlen($file->packageName) + 1);
            }

            if (null !== ($definition = $file->resolveDefinition($typeName))) {
                if (!$definition instanceof Message) {
                    throw new ConstraintViolated(\sprintf('"%s" is not a message type.', $typeName));
                }

                return true;
            }

            return false;
        };

        if ($validate($type, $file)) {
            return true;
        }

        foreach ($file->imports as $import) {
            if ($validate($type, $files[$import])) {
                return true;
            }
        }

        return false;
    }
}
