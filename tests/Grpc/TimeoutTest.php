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

namespace Prototype\Tests\Grpc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Prototype\Grpc\Timeout;

#[CoversClass(Timeout::class)]
final class TimeoutTest extends TestCase
{
    /**
     * @return iterable<array-key, array{Timeout, non-empty-string, float}>
     */
    public static function timeoutFixtures(): iterable
    {
        yield 'hours' => [
            Timeout::hours(1),
            '1H',
            3600.0,
        ];

        yield 'minutes' => [
            Timeout::minutes(2),
            '2M',
            120.0,
        ];

        yield 'seconds' => [
            Timeout::seconds(123),
            '123S',
            123,
        ];

        yield 'milliseconds' => [
            Timeout::milliseconds(2347),
            '2347m',
            2.347,
        ];

        yield 'microseconds' => [
            Timeout::microseconds(1_000_243),
            '1000243u',
            1.000243,
        ];

        yield 'nanoseconds' => [
            Timeout::nanoseconds(1_000_000_900),
            '1000000900n',
            1.0000009,
        ];
    }

    /**
     * @param non-empty-string $headerValue
     */
    #[DataProvider('timeoutFixtures')]
    public function testTimeout(Timeout $timeout, string $headerValue, float $seconds): void
    {
        self::assertSame($headerValue, $timeout->toHeaderValue());
        self::assertSame($seconds, $timeout->toSeconds());
    }

    /**
     * @return iterable<array-key, array{non-empty-string, Timeout}>
     */
    public static function stringTimeoutFixtures(): iterable
    {
        yield 'hours' => [
            '2H',
            Timeout::hours(2),
        ];

        yield 'minutes' => [
            '12M',
            Timeout::minutes(12),
        ];

        yield 'seconds' => [
            '60S',
            Timeout::seconds(60),
        ];

        yield 'milliseconds' => [
            '600m',
            Timeout::milliseconds(600),
        ];

        yield 'microseconds' => [
            '6000u',
            Timeout::microseconds(6000),
        ];

        yield 'nanoseconds' => [
            '60000n',
            Timeout::nanoseconds(60_000),
        ];
    }

    /**
     * @param non-empty-string $value
     */
    #[DataProvider('stringTimeoutFixtures')]
    public function testFromString(string $value, Timeout $timeout): void
    {
        self::assertEquals($timeout, Timeout::fromString($value));
    }

    public function testHeaderValueIsTooShort(): void
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Timeout string "1" is too short.');

        Timeout::fromString('1');
    }

    public function testHeaderValueIsTooLong(): void
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Timeout string "123456789S" is too long.');

        Timeout::fromString('123456789S');
    }

    public function testTimeoutUnitIsUnknown(): void
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Timeout unit "D" is not recognized.');

        Timeout::fromString('1D');
    }

    public function testTimeoutValueIsNotANumber(): void
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Timeout value "i" is not a valid number.');

        Timeout::fromString('iS');
    }

    public function testFromInterval(): void
    {
        $time = new \DateTimeImmutable('NOW');

        $timeout = Timeout::fromInterval(
            new \DateInterval('PT2S'),
            $time,
        );

        self::assertSame('2S', $timeout->toHeaderValue());
        self::assertSame(2.0, $timeout->toSeconds());
    }

    public function testFromDateTime(): void
    {
        $time = new \DateTimeImmutable('NOW');

        $timeout = Timeout::fromDateTime(
            $time->add(new \DateInterval('PT2S')),
            $time,
        );

        self::assertSame('2S', $timeout->toHeaderValue());
        self::assertSame(2.0, $timeout->toSeconds());
    }
}
