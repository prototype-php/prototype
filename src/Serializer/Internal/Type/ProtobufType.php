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

use Typhoon\DeclarationId\NamedClassId;
use Typhoon\Reflection\Annotated\CustomTypeResolver;
use Typhoon\Reflection\Annotated\TypeContext;
use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;

/**
 * @internal
 * @psalm-internal Prototype\Serializer
 * @template-implements Type<TypeSerializer>
 */
enum ProtobufType: string implements Type, CustomTypeResolver
{
    // Uses variable-length encoding.
    // Inefficient for encoding negative numbers – if your field is likely to have negative values, use sint32 instead.
    case int32 = 'int32';

    // Uses variable-length encoding.
    // Inefficient for encoding negative numbers – if your field is likely to have negative values, use sint64 instead.
    case int64 = 'int64';

    // Uses variable-length encoding.
    case uint32 = 'uint32';

    // Uses variable-length encoding.
    case uint64 = 'uint64';

    // Uses variable-length encoding. Signed int value.
    // These more efficiently encode negative numbers than regular int32s.
    case sint32 = 'sint32';

    // Uses variable-length encoding. Signed int value.
    // These more efficiently encode negative numbers than regular int64s.
    case sint64 = 'sint64';

    // Always four bytes.
    // More efficient than uint32 if values are often greater than 2^28.
    case fixed32 = 'fixed32';

    // Always eight bytes.
    // More efficient than uint64 if values are often greater than 2^56.
    case fixed64 = 'fixed64';

    // Always four bytes.
    case sfixed32 = 'sfixed32';

    // Always four bytes.
    case sfixed64 = 'sfixed64';

    // A string must always contain UTF-8 encoded or 7-bit ASCII text, and cannot be longer than 2^32.
    case string = 'string';

    // Always four bytes.
    case float = 'float';

    // Always eight bytes.
    case double = 'double';

    // Uses variable-length encoding.
    case bool = 'bool';

    // May contain any arbitrary sequence of bytes no longer than 2^32.
    case bytes = 'bytes';

    public static function instance(): self
    {
        return self::bool;
    }

    /**
     * {@inheritdoc}
     */
    public function accept(TypeVisitor $visitor): mixed
    {
        /** @psalm-suppress InvalidArgument */
        return match ($this) {
            self::int32,
            self::int64,
            self::uint32,
            self::uint64   => $visitor->namedObject($this, NamedClassId::class(VarintType::class), []),
            self::sint32   => $visitor->namedObject($this, NamedClassId::class(SInt32Type::class), []),
            self::sint64   => $visitor->namedObject($this, NamedClassId::class(SInt64Type::class), []),
            self::fixed32  => $visitor->namedObject($this, NamedClassId::class(Fixed32Type::class), []),
            self::fixed64  => $visitor->namedObject($this, NamedClassId::class(Fixed64Type::class), []),
            self::sfixed32 => $visitor->namedObject($this, NamedClassId::class(SFixed32Type::class), []),
            self::sfixed64 => $visitor->namedObject($this, NamedClassId::class(SFixed64Type::class), []),
            self::string,
            self::bytes    => $visitor->namedObject($this, NamedClassId::class(StringType::class), []),
            self::float    => $visitor->namedObject($this, NamedClassId::class(FloatType::class), []),
            self::double   => $visitor->namedObject($this, NamedClassId::class(DoubleType::class), []),
            self::bool     => $visitor->namedObject($this, NamedClassId::class(BoolType::class), []),
        };
    }

    /**
     * {@inheritdoc}
     */
    public function resolveCustomType(string $unresolvedName, array $typeArguments, TypeContext $context): ?Type
    {
        return self::tryFrom($unresolvedName);
    }
}
