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

namespace Prototype\Serializer\Internal\Type;

/**
 * @internal
 * @psalm-internal Prototype\Serializer
 */
final class DurationType
{
    /**
     * @param int64 $seconds
     * @param int32 $nanos
     */
    private function __construct(
        public readonly int $seconds = 0,
        public readonly int $nanos = 0,
    ) {}

    public static function fromDateInterval(\DateInterval $interval): self
    {
        /** @var int64 $seconds */
        $seconds = $interval->days * 24 * 60 * 60 +
            $interval->h * 60 * 60 +
            $interval->i * 60 +
            $interval->s
        ;

        /** @var int32 $nanos */
        $nanos = (int) ($interval->f * 1_000_000_000);

        return new self($seconds, $nanos);
    }

    /**
     * @throws \Exception
     */
    public function toDateInterval(): \DateInterval
    {
        return new \DateInterval(\sprintf('PT%dS', $this->seconds + $this->nanos / 1e9));
    }
}
