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
 * An object representing a short code, which is a phone number that is
 * typically much shorter than regular phone numbers and can be used to
 * address messages in MMS and SMS systems, as well as for abbreviated dialing
 * (e.g. "Text 611 to see how many minutes you have remaining on your plan.").
 *
 * Short codes are restricted to a region and are not internationally
 * dialable, which means the same short code can exist in different regions,
 * with different usage and pricing, even if those regions share the same
 * country calling code (e.g. US and CA).
 *
 * @api
 */
final class ShortCode
{
    public function __construct(
        public readonly string $regionCode,
        public readonly string $number,
    ) {}
}
