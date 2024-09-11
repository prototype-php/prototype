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
 * Represents a postal address, e.g. for postal delivery or payments addresses.
 * Given a postal address, a postal service can deliver items to a premise, P.O.
 * Box or similar.
 * It is not intended to model geographical locations (roads, towns, mountains).
 * In typical usage an address would be created via user input or from importing
 * existing data, depending on the type of process.
 *
 * @api
 */
final class PostalAddress
{
    /**
     * @param ?int32 $revision
     * @param list<string> $addressLines
     * @param list<string> $recipients
     */
    public function __construct(
        public readonly ?int $revision = null,
        public readonly ?string $regionCode = null,
        public readonly ?string $languageCode = null,
        public readonly ?string $postalCode = null,
        public readonly ?string $sortingCode = null,
        public readonly ?string $administrativeArea = null,
        public readonly ?string $locality = null,
        public readonly ?string $sublocality = null,
        public readonly array $addressLines = [],
        public readonly array $recipients = [],
        public readonly ?string $organization = null,
    ) {}
}
