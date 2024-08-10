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

namespace Prototype\Compiler\Internal\Code;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 */
final class WellKnown
{
    /**
     * @return iterable<non-empty-string, PhpType>
     */
    public static function typeToPhpType(): iterable
    {
        yield 'google.protobuf.Timestamp' => PhpType::class(
            \DateTimeInterface::class,
        );

        yield 'google.protobuf.Duration' => PhpType::class(
            \DateInterval::class,
        );

        yield 'google.protobuf.Struct' => PhpType::array(
            PhpType::scalar('string'),
            PhpType::scalar('mixed'),
        );

        yield 'google.type.Color' => PhpType::class(
            'Color',
            'Prototype\WellKnown\Color',
        );

        yield 'google.type.CalendarPeriod' => PhpType::enum(
            'CalendarPeriod',
            'Prototype\WellKnown\CalendarPeriod',
        );

        yield 'google.type.DayOfWeek' => PhpType::enum(
            'DayOfWeek',
            'Prototype\WellKnown\DayOfWeek',
        );

        yield 'google.type.Interval' => PhpType::class(
            'Interval',
            'Prototype\WellKnown\Interval',
        );

        yield 'google.type.LatLng' => PhpType::class(
            'LatLng',
            'Prototype\WellKnown\LatLng',
        );

        yield 'google.type.Money' => PhpType::class(
            'Money',
            'Prototype\WellKnown\Money',
        );

        yield 'google.type.Month' => PhpType::enum(
            'Month',
            'Prototype\WellKnown\Month',
        );

        yield 'google.type.PhoneNumber' => PhpType::class(
            'PhoneNumber',
            'Prototype\WellKnown\PhoneNumber',
        );

        yield 'google.type.PostalAddress' => PhpType::class(
            'PostalAddress',
            'Prototype\WellKnown\PostalAddress',
        );

        yield 'google.type.TimeOfDay' => PhpType::class(
            'TimeOfDay',
            'Prototype\WellKnown\TimeOfDay',
        );
    }

    /**
     * @return iterable<non-empty-string, non-empty-string>
     */
    public static function pathToType(): iterable
    {
        yield 'google/protobuf/timestamp.proto'   => 'google.protobuf.Timestamp';
        yield 'google/protobuf/duration.proto'    => 'google.protobuf.Duration';
        yield 'google/protobuf/struct.proto'      => 'google.protobuf.Struct';
        yield 'google/type/color.proto'           => 'google.type.Color';
        yield 'google/type/calendar_period.proto' => 'google.type.CalendarPeriod';
        yield 'google/type/dayofweek.proto'       => 'google.type.DayOfWeek';
        yield 'google/type/interval.proto'        => 'google.type.Interval';
        yield 'google/type/latlng.proto'          => 'google.type.LatLng';
        yield 'google/type/money.proto'           => 'google.type.Money';
        yield 'google/type/month.proto'           => 'google.type.Month';
        yield 'google/type/phone_number.proto'    => 'google.type.PhoneNumber';
        yield 'google/type/postal_address.proto'  => 'google.type.PostalAddress';
        yield 'google/type/timeofday.proto'       => 'google.type.TimeOfDay';
    }
}
