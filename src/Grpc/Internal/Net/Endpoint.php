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

namespace Prototype\Grpc\Internal\Net;

/**
 * @internal
 * @psalm-internal Prototype\Grpc
 */
final class Endpoint implements \Stringable
{
    /** @var non-empty-string */
    public readonly string $path;

    /** @var non-empty-string */
    public readonly string $serviceName;

    /** @var non-empty-string */
    public readonly string $rpc;

    /**
     * @param non-empty-string $serviceName
     * @param non-empty-string $rpc
     */
    public function __construct(
        string $serviceName,
        string $rpc,
    ) {
        $serviceName = trim($serviceName, '/');

        if ('' === $serviceName) {
            throw new \InvalidArgumentException('Service name cannot be empty.');
        }

        $rpc = trim($rpc, '/');

        if ('' === $rpc) {
            throw new \InvalidArgumentException('Rpc name cannot be empty.');
        }

        $this->serviceName = $serviceName;
        $this->rpc = $rpc;
        $this->path = \sprintf('/%s/%s', $this->serviceName, $this->rpc);
    }

    public static function parse(string $path): self
    {
        /** @var array{0?: non-empty-string, 1?: non-empty-string} $parts */
        $parts = explode('/', ltrim($path, '/'));

        return new self(
            $parts[0] ?? '/',
            $parts[1] ?? '/',
        );
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return $this->path;
    }
}
