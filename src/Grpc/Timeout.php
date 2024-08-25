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

namespace Prototype\Grpc;

/**
 * @api
 */
final class Timeout
{
    private const TYPE_HOUR        = 'H';
    private const TYPE_MINUTE      = 'M';
    private const TYPE_SECOND      = 'S';
    private const TYPE_MILLISECOND = 'm';
    private const TYPE_MICROSECOND = 'u';
    private const TYPE_NANOSECOND  = 'n';

    /** @var non-empty-array<non-empty-string, 1> */
    private const UNITS = [
        self::TYPE_HOUR => 1,
        self::TYPE_MINUTE => 1,
        self::TYPE_SECOND => 1,
        self::TYPE_MILLISECOND => 1,
        self::TYPE_MICROSECOND => 1,
        self::TYPE_NANOSECOND => 1,
    ];

    /**
     * @psalm-param non-negative-int $value
     * @psalm-param self::TYPE_* $unit
     */
    private function __construct(
        private readonly int $value,
        private readonly string $unit,
    ) {}

    /**
     * @psalm-param non-negative-int $value
     */
    public static function hours(int $value): self
    {
        return new self($value, self::TYPE_HOUR);
    }

    /**
     * @psalm-param non-negative-int $value
     */
    public static function minutes(int $value): self
    {
        return new self($value, self::TYPE_MINUTE);
    }

    /**
     * @psalm-param non-negative-int $value
     */
    public static function seconds(int $value): self
    {
        return new self($value, self::TYPE_SECOND);
    }

    /**
     * @psalm-param non-negative-int $value
     */
    public static function milliseconds(int $value): self
    {
        return new self($value, self::TYPE_MILLISECOND);
    }

    /**
     * @psalm-param non-negative-int $value
     */
    public static function microseconds(int $value): self
    {
        return new self($value, self::TYPE_MICROSECOND);
    }

    /**
     * @psalm-param non-negative-int $value
     */
    public static function nanoseconds(int $value): self
    {
        return new self($value, self::TYPE_NANOSECOND);
    }

    public static function fromInterval(
        \DateInterval $interval,
        \DateTimeImmutable $time = new \DateTimeImmutable('NOW'),
    ): self {
        return self::fromDateTime($time->add($interval), $time);
    }

    public static function fromDateTime(
        \DateTimeImmutable $deadline,
        \DateTimeImmutable $time = new \DateTimeImmutable('NOW'),
    ): self {
        return self::seconds(max($deadline->getTimestamp() - $time->getTimestamp(), 0));
    }

    /**
     * @param non-empty-string $timeout
     */
    public static function fromString(string $timeout): self
    {
        if (\strlen($timeout) < 2) {
            throw new \InvalidArgumentException(\sprintf('Timeout string "%s" is too short.', $timeout));
        }

        if (\strlen($timeout) > 9) {
            throw new \InvalidArgumentException(\sprintf('Timeout string "%s" is too long.', $timeout));
        }

        $unit = $timeout[\strlen($timeout) - 1];

        if (!self::isKnownUnit($unit)) {
            throw new \InvalidArgumentException(\sprintf('Timeout unit "%s" is not recognized.', $unit));
        }

        $value = substr($timeout, 0, \strlen($timeout) - 1);

        if (!is_numeric($value)) {
            throw new \InvalidArgumentException(\sprintf('Timeout value "%s" is not a valid number.', $value));
        }

        $factory = match ($unit) {
            self::TYPE_HOUR        => self::hours(...),
            self::TYPE_MINUTE      => self::minutes(...),
            self::TYPE_SECOND      => self::seconds(...),
            self::TYPE_MILLISECOND => self::milliseconds(...),
            self::TYPE_MICROSECOND => self::microseconds(...),
            self::TYPE_NANOSECOND  => self::nanoseconds(...),
        };

        return $factory(max((int) $value, 0));
    }

    /**
     * @return non-empty-string
     */
    public function toHeaderValue(): string
    {
        return \sprintf('%d%s', $this->value, $this->unit);
    }

    public function toSeconds(): float
    {
        return match ($this->unit) {
            self::TYPE_HOUR        => 3600 * $this->value,
            self::TYPE_MINUTE      => 60 * $this->value,
            self::TYPE_SECOND      => $this->value,
            self::TYPE_MILLISECOND => 0.001 * $this->value,
            self::TYPE_MICROSECOND => 0.000001 * $this->value,
            self::TYPE_NANOSECOND  => 0.000000001 * $this->value,
        };
    }

    /**
     * @param non-empty-string $value
     * @psalm-assert-if-true self::TYPE_* $value
     */
    private static function isKnownUnit(string $value): bool
    {
        return isset(self::UNITS[$value]);
    }
}
