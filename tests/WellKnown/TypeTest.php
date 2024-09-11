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

namespace Prototype\Tests\WellKnown;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Prototype\Serializer\Serializer;
use Prototype\WellKnown;

#[CoversClass(WellKnown\Color::class)]
#[CoversClass(WellKnown\Date::class)]
#[CoversClass(WellKnown\DayOfWeek::class)]
#[CoversClass(WellKnown\Empty_::class)]
#[CoversClass(WellKnown\Expr::class)]
#[CoversClass(WellKnown\FieldMask::class)]
#[CoversClass(WellKnown\FloatValue::class)]
#[CoversClass(WellKnown\Interval::class)]
#[CoversClass(WellKnown\LatLng::class)]
#[CoversClass(WellKnown\LocalizedText::class)]
#[CoversClass(WellKnown\Money::class)]
#[CoversClass(WellKnown\Month::class)]
#[CoversClass(WellKnown\PhoneNumber::class)]
#[CoversClass(WellKnown\PostalAddress::class)]
#[CoversClass(WellKnown\ShortCode::class)]
#[CoversClass(WellKnown\TimeOfDay::class)]
final class TypeTest extends TestCase
{
    /**
     * @return iterable<array-key, array{object}>
     */
    public static function fixtures(): iterable
    {
        yield WellKnown\Color::class => [
            new WellKnown\Color(
                red: 250,
                green: 250,
                blue: 250,
                alpha: new WellKnown\FloatValue(
                    250,
                ),
            ),
        ];

        yield WellKnown\Date::class => [
            new WellKnown\Date(
                2024,
                9,
                20,
            ),
        ];

        yield WellKnown\Empty_::class => [
            new WellKnown\Empty_(),
        ];

        yield WellKnown\Expr::class => [
            new WellKnown\Expr(
                'x->y',
                'test',
            ),
        ];

        yield WellKnown\FieldMask::class => [
            new WellKnown\FieldMask([
                'f.a',
                'f.b.d',
            ]),
        ];

        yield WellKnown\FloatValue::class => [
            new WellKnown\FloatValue(100),
        ];

        yield WellKnown\Interval::class => [
            new WellKnown\Interval(
                new \DateTimeImmutable('2024-09-10 00:00'),
                new \DateTimeImmutable('2024-09-11 00:00'),
            ),
        ];

        yield WellKnown\LatLng::class => [
            new WellKnown\LatLng(
                latitude: 300,
                longitude: 400,
            ),
        ];

        yield WellKnown\LocalizedText::class => [
            new WellKnown\LocalizedText(
                'Hello, world',
                'en',
            ),
        ];

        yield WellKnown\Money::class => [
            new WellKnown\Money(
                'USD',
                20,
            ),
        ];

        yield WellKnown\PhoneNumber::class => [
            new WellKnown\PhoneNumber(
                new WellKnown\ShortCode('NY', '555-666-777'),
            ),
        ];

        yield WellKnown\PostalAddress::class => [
            new WellKnown\PostalAddress(
                regionCode: 'US',
                languageCode: 'en',
                addressLines: ['NY'],
            ),
        ];

        yield WellKnown\TimeOfDay::class => [
            new WellKnown\TimeOfDay(
                hours: 10,
                minutes: 20,
                seconds: 6,
            ),
        ];
    }

    #[DataProvider('fixtures')]
    public function testType(object $message): void
    {
        $serializer = new Serializer();

        $buffer = $serializer->serialize($message);
        self::assertEquals($message, $serializer->deserialize($buffer, $message::class));
        self::assertSame(0, $buffer->size());
    }

    public function testDate(): void
    {
        $serializer = new Serializer();

        $date = new WellKnown\Date(
            2024,
            9,
            10,
        );

        $buffer = $serializer->serialize($date);
        self::assertEquals($date, $serializer->deserialize($buffer, WellKnown\Date::class));
        self::assertEquals('2024-09-10', $date->asDateTime()->format('Y-m-d'));
    }
}
