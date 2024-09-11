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
 *  An object representing a phone number, suitable as an API wire format.
 *
 * This representation:
 *
 *  - should not be used for locale-specific formatting of a phone number, such
 *    as "+1 (650) 253-0000 ext. 123"
 *
 *  - is not designed for efficient storage
 *  - may not be suitable for dialing - specialized libraries (see references)
 *    should be used to parse the number for that purpose
 *
 * To do something meaningful with this number, such as format it for various
 * use-cases, convert it to an `i18n.phonenumbers.PhoneNumber` object first.
 *
 * @api
 */
final class PhoneNumber
{
    public function __construct(
        public readonly null|string|ShortCode $kind = null,
        public readonly ?string $extension = null,
    ) {}
}
