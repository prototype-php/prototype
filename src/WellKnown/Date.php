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

namespace Prototype\WellKnown;

/**
 * Represents a whole or partial calendar date, such as a birthday. The time of
 * day and time zone are either specified elsewhere or are insignificant. The
 * date is relative to the Gregorian Calendar. This can represent one of the
 * following:
 *
 * * A full date, with non-zero year, month, and day values
 * * A month and day value, with a zero year, such as an anniversary
 * * A year on its own, with zero month and day values
 * * A year and month value, with a zero day, such as a credit card expiration
 * date
 * Related types are [google.type.TimeOfDay][google.type.TimeOfDay] and
 * `google.protobuf.Timestamp`.
 *
 * @api
 */
final class Date
{
    /**
     * @param ?int32 $year
     * @param ?int32 $month
     * @param ?int32 $day
     */
    public function __construct(
        public readonly ?int $year = null,
        public readonly ?int $month = null,
        public readonly ?int $day = null,
    ) {}

    /**
     * @throws \InvalidArgumentException
     */
    public function asDateTime(): \DateTimeInterface
    {
        return \DateTimeImmutable::createFromFormat('Y-m-d', $date = \sprintf('%d-%d-%d', $this->year ?: 0, $this->month ?: 0, $this->day ?: 0)) ?: throw new \InvalidArgumentException(
            \sprintf('Unable to parse date "%s".', $date),
        );
    }
}
