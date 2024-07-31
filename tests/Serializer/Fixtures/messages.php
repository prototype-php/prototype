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

namespace Prototype\Tests\Serializer\Fixtures;

use Prototype\Serializer\Field;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class ProtobufMessage
{
    /**
     * @param non-empty-string $path
     * @param non-empty-string $constructorFunction
     */
    public function __construct(
        public readonly string $path,
        public readonly string $constructorFunction,
    ) {}
}

enum CompressionType: int
{
    case NONE = 0;
    case GZIP = 1;
    case LZ4 = 2;
}

#[ProtobufMessage(path: 'resources/message_with_enum_gzip.bin', constructorFunction: 'withGzipCompression')]
#[ProtobufMessage(path: 'resources/message_with_enum_lz4.bin', constructorFunction: 'withLZ4Compression')]
#[ProtobufMessage(path: 'resources/message_with_enum_none.bin', constructorFunction: 'withNoneCompression')]
final class MessageWithEnum
{
    public function __construct(
        public readonly CompressionType $type,
    ) {}

    public static function withGzipCompression(): self
    {
        return new self(CompressionType::GZIP);
    }

    public static function withLZ4Compression(): self
    {
        return new self(CompressionType::LZ4);
    }

    public static function withNoneCompression(): self
    {
        return new self(CompressionType::NONE);
    }
}

enum Corpus: int
{
    case CORPUS_UNSPECIFIED = 0;
    case CORPUS_UNIVERSAL = 1;
    case CORPUS_WEB = 2;
    case CORPUS_IMAGES = 3;
    case CORPUS_LOCAL = 4;
    case CORPUS_NEWS = 5;
    case CORPUS_PRODUCTS = 6;
    case CORPUS_VIDEO = 7;
}

#[ProtobufMessage(path: 'resources/search_request.bin', constructorFunction: 'webCorpus')]
final class SearchRequest
{
    /**
     * @param string $query
     * @param int32 $pageNumber
     * @param int32 $resultsPerPage
     * @param Corpus $corpus
     */
    public function __construct(
        public readonly string $query,
        public readonly int $pageNumber,
        public readonly int $resultsPerPage,
        public readonly Corpus $corpus,
    ) {
    }

    public static function webCorpus(): self
    {
        return new self(
            '?php+protobuf',
            100,
            10,
            Corpus::CORPUS_WEB,
        );
    }
}

final class SearchResult
{
    /**
     * @param list<string> $snippets
     */
    public function __construct(
        public readonly string $url,
        public readonly string $title,
        public readonly array $snippets,
    ) {}
}

#[ProtobufMessage(path: 'resources/search_response.bin', constructorFunction: 'twoResults')]
final class SearchResponse
{
    /**
     * @param list<SearchResult> $results
     * @param fixed32 $total
     */
    public function __construct(
        public readonly array $results,
        public readonly int $total,
    ) {}

    public static function twoResults(): self
    {
        return new self(
            [
                new SearchResult(
                    url: 'https://google.com/?protobuf',
                    title: 'Protobuf is a google implementation of binary serialization format',
                    snippets: ['grpc', 'protobuf'],
                ),
                new SearchResult(
                    url: 'https://google.com/?php+protobuf',
                    title: 'A modern strictly typed full-featured library for protobuf serialization without an inheritance.',
                    snippets: ['grpc', 'protobuf', 'php'],
                ),
            ],
            2,
        );
    }
}

enum PhoneType: int
{
    case MOBILE = 0;
    case HOME = 1;
    case WORK = 2;
}

final class PhoneNumber
{
    public function __construct(
        #[Field(num: 2)]
        public readonly PhoneType $type,
        public readonly string $number,
    ) {}
}

#[ProtobufMessage(path: 'resources/person.bin', constructorFunction: 'withDifferentOrder')]
final class Person
{
    /**
     * @param array<string, string> $info
     * @param sfixed32 $id
     */
    public function __construct(
        public readonly string $name,
        public readonly int $id,
        public readonly string $email,
        public readonly PhoneNumber $phone,
        public readonly array $info,
    ) {}

    public static function withDifferentOrder(): self
    {
        return new self(
            'John Doe',
            -200,
            'johndoe@gmail.com',
            new PhoneNumber(
                PhoneType::MOBILE,
                '7900000000',
            ),
            ['sex' => 'male'],
        );
    }
}

final class Skype
{
    public function __construct(
        public readonly string $id,
    ) {}
}

final class Email
{
    public function __construct(
        public readonly string $address,
    ) {}
}

final class Address
{
    public function __construct(
        public readonly string $street,
        public readonly string $city,
        public readonly string $state,
        public readonly string $zipCode,
    ) {}
}

final class Job
{
    public function __construct(
        public readonly string $title,
        public readonly string $company,
        public readonly string $startDate,
        public readonly string $endDate,
    ) {}
}

#[ProtobufMessage(path: 'resources/candidate.bin', constructorFunction: 'default')]
#[ProtobufMessage(path: 'resources/candidate_with_email.bin', constructorFunction: 'withEmail')]
#[ProtobufMessage(path: 'resources/candidate_without_contact.bin', constructorFunction: 'withoutContact')]
final class Candidate
{
    /**
     * @param int32 $id
     * @param array<string, Address> $addresses
     * @param list<PhoneNumber> $phones
     * @param list<Job> $previousJobs
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly array $addresses,
        public readonly array $phones,
        public readonly Skype|Email|null $contact,
        public array $previousJobs,
    ) {}

    public static function default(): self
    {
        return new self(
            1,
            'John Doe',
            'john.doe@example.com',
            [
                'home' => new Address(
                    street: '123 Main St',
                    city: 'Hometown',
                    state: 'CA',
                    zipCode: '12345',
                ),
                'work' => new Address(
                    street: '456 Business Rd',
                    city: 'Big City',
                    state: 'NY',
                    zipCode: '67890',
                ),
            ],
            [
                new PhoneNumber(PhoneType::MOBILE, '555-1234'),
                new PhoneNumber(PhoneType::HOME, '555-5678'),
            ],
            new Skype('john.doe.skype'),
            [
                new Job(
                    title: 'Software Engineer',
                    company: 'TechCorp',
                    startDate: '2015-06-01',
                    endDate: '2018-08-15',
                ),
                new Job(
                    title: 'Senior Developer',
                    company: 'DevCompany',
                    startDate: '2018-09-01',
                    endDate: '2021-12-31',
                ),
            ],
        );
    }

    public static function withEmail(): self
    {
        return new self(
            1,
            'John Doe',
            'john.doe@example.com',
            [
                'home' => new Address(
                    street: '123 Main St',
                    city: 'Hometown',
                    state: 'CA',
                    zipCode: '12345',
                ),
                'work' => new Address(
                    street: '456 Business Rd',
                    city: 'Big City',
                    state: 'NY',
                    zipCode: '67890',
                ),
            ],
            [
                new PhoneNumber(PhoneType::MOBILE, '555-1234'),
                new PhoneNumber(PhoneType::HOME, '555-5678'),
            ],
            new Email('johndoe@work.com'),
            [
                new Job(
                    title: 'Software Engineer',
                    company: 'TechCorp',
                    startDate: '2015-06-01',
                    endDate: '2018-08-15',
                ),
                new Job(
                    title: 'Senior Developer',
                    company: 'DevCompany',
                    startDate: '2018-09-01',
                    endDate: '2021-12-31',
                ),
            ],
        );
    }

    public static function withoutContact(): self
    {
        return new self(
            1,
            'John Doe',
            'john.doe@example.com',
            [
                'home' => new Address(
                    street: '123 Main St',
                    city: 'Hometown',
                    state: 'CA',
                    zipCode: '12345',
                ),
                'work' => new Address(
                    street: '456 Business Rd',
                    city: 'Big City',
                    state: 'NY',
                    zipCode: '67890',
                ),
            ],
            [
                new PhoneNumber(PhoneType::MOBILE, '555-1234'),
                new PhoneNumber(PhoneType::HOME, '555-5678'),
            ],
            null,
            [
                new Job(
                    title: 'Software Engineer',
                    company: 'TechCorp',
                    startDate: '2015-06-01',
                    endDate: '2018-08-15',
                ),
                new Job(
                    title: 'Senior Developer',
                    company: 'DevCompany',
                    startDate: '2018-09-01',
                    endDate: '2021-12-31',
                ),
            ],
        );
    }
}

#[ProtobufMessage(path: 'resources/timestamp.bin', constructorFunction: 'default')]
final class MessageWithDateTimeInterface
{
    public function __construct(
        public readonly ?\DateTimeInterface $scheduled = null,
    ) {}

    public static function default(): self
    {
        $time = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%d.%d', 1720761326, 237536));
        \assert($time instanceof \DateTimeImmutable);

        return new self($time);
    }
}

#[ProtobufMessage(path: 'resources/timestamp.bin', constructorFunction: 'default')]
final class MessageWithDateTimeImmutable
{
    public function __construct(
        public readonly ?\DateTimeImmutable $scheduled = null,
    ) {}

    public static function default(): self
    {
        $time = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%d.%d', 1720761326, 237536));
        \assert($time instanceof \DateTimeImmutable);

        return new self($time);
    }
}

#[ProtobufMessage(path: 'resources/timestamp.bin', constructorFunction: 'default')]
final class MessageWithDateTime
{
    public function __construct(
        public readonly ?\DateTime $scheduled = null,
    ) {}

    public static function default(): self
    {
        $time = \DateTime::createFromFormat('U.u', \sprintf('%d.%d', 1720761326, 237536));
        \assert($time instanceof \DateTime);

        return new self($time);
    }
}

#[ProtobufMessage(path: 'resources/struct.bin', constructorFunction: 'complex')]
final class Package
{
    /**
     * @param array<string, mixed> $options
     */
    public function __construct(
        public readonly string $name,
        public readonly array $options,
    ) {}

    public static function complex(): self
    {
        return new self(
            'kafkiansky/prototype',
            [
                'version' => 0.1,
                'released' => false,
                'tags' => ['php', 'protobuf'],
                'contributors' => [
                    'johndoe' => [
                        'role' => 'developer',
                        'years' => 28.0,
                        'male' => true,
                        'contacts' => [
                            'email' => 'johndoe@gmail.com',
                        ],
                    ],
                ],
                'releaseDate' => null,
                'package' => 'prototype',
            ],
        );
    }
}

#[ProtobufMessage(path: 'resources/shape.bin', constructorFunction: 'new')]
final class ArrayShapeWithPHPDoc
{
    /**
     * @param int64 $id
     * @param array{name: string, blocked: bool, salary: float, fired: \DateTimeInterface} $info
     */
    public function __construct(
        public readonly int $id,
        public readonly array $info,
    ) {}

    public static function new(): self
    {
        $time = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%d.%d', 1720809416, 679224));
        \assert($time instanceof \DateTimeImmutable);

        return new self(
            123,
            [
                'name' => 'johndoe',
                'blocked' => true,
                'salary' => 120000.5,
                'fired' => $time,
            ],
        );
    }
}

#[ProtobufMessage(path: 'resources/shape.bin', constructorFunction: 'new')]
final class ArrayShapeWithAttribute
{
    /**
     * @param int64 $id
     * @param array{name: string, blocked: bool, salary: float, fired: \DateTimeImmutable} $info
     */
    public function __construct(
        public readonly int $id,
        public readonly array $info,
    ) {}

    public static function new(): self
    {
        $time = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%d.%d', 1720809416, 679224));
        \assert($time instanceof \DateTimeImmutable);

        return new self(
            123,
            [
                'name' => 'johndoe',
                'blocked' => true,
                'salary' => 120000.5,
                'fired' => $time,
            ],
        );
    }
}

#[ProtobufMessage(path: 'resources/duration.bin', constructorFunction: 'twoHoursAndHalf')]
final class Interview
{
    public function __construct(
        public readonly \DateInterval $duration,
    ) {}

    public static function twoHoursAndHalf(): self
    {
        return new self(
            new \DateInterval('PT8400S'),
        );
    }
}

final class NestedMessage
{
    /**
     * @param int32 $nestedId
     * @param list<int32> $values
     */
    public function __construct(
        public readonly int $nestedId = 0,
        public readonly string $nestedName = '',
        public readonly array $values = [],
    ) {}
}

final class AnotherNestedMessage
{
    /**
     * @param array<string, mixed> $additionalData
     * @param array<string, string> $additionalMap
     */
    public function __construct(
        public readonly ?\DateTimeInterface $timestamp = null,
        public readonly array $additionalData = [],
        public readonly array $additionalMap = [],
    ) {}
}

final class DurationMessage
{
    public function __construct(
        public readonly ?\DateInterval $duration = null,
    ) {}
}

#[ProtobufMessage(path: 'resources/complex_message.bin', constructorFunction: 'withSpecialFieldString')]
#[ProtobufMessage(path: 'resources/complex_message_with_special_int.bin', constructorFunction: 'withSpecialFieldInt')]
#[ProtobufMessage(path: 'resources/complex_message_with_special_nested_message.bin', constructorFunction: 'withSpecialFieldNestedMessage')]
#[ProtobufMessage(path: 'resources/complex_message_with_special_datetime.bin', constructorFunction: 'withSpecialFieldDateTime')]
#[ProtobufMessage(path: 'resources/empty.bin', constructorFunction: 'emptyAtAll')]
final class ComplexMessage
{
    /**
     * @param int32 $id
     * @param int64 $bigId
     * @param uint32 $unsignedId
     * @param uint64 $unsignedBigId
     * @param sint32 $signedId
     * @param sint64 $signedBigId
     * @param fixed32 $fixedId
     * @param fixed64 $fixedBigId
     * @param sfixed32 $signedFixedId
     * @param sfixed64 $signedFixedBigId
     * @param double $doubleValue
     * @param bytes $data
     * @param list<string> $tags
     * @param array<string, int32> $metadata
     * @param array<string, mixed> $properties
     * @param string|int32|NestedMessage|\DateTimeInterface $specialField
     * @param list<AnotherNestedMessage> $anotherNestedMessages
     */
    public function __construct(
        public readonly int $id = 0,
        public readonly int $bigId = 0,
        public readonly int $unsignedId = 0,
        public readonly int $unsignedBigId = 0,
        public readonly int $signedId = 0,
        public readonly int $signedBigId = 0,
        public readonly int $fixedId = 0,
        public readonly int $fixedBigId = 0,
        public readonly int $signedFixedId = 0,
        public readonly int $signedFixedBigId = 0,
        public readonly float $floatValue = 0.0,
        public readonly float $doubleValue = 0.0,
        public readonly bool $isActive = false,
        public readonly string $name = '',
        public readonly string $data = '',
        public readonly array $tags = [],
        public readonly array $metadata = [],
        public readonly array $properties = [],
        public readonly ?\DateTimeInterface $createdAt = null,
        public readonly ?\DateInterval $validFor = null,
        public readonly ?NestedMessage $nestedMessage = null,
        public readonly null|string|int|NestedMessage|\DateTimeInterface $specialField = null,
        public readonly array $anotherNestedMessages = [],
        public readonly ?DurationMessage $durationMessage = null,
    ) {}

    public static function withSpecialFieldString(): self
    {
        $time = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%d.%d', 1720809416, 679224));
        \assert($time instanceof \DateTimeImmutable);

        return new self(
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11.199999809265137,
            12.4,
            true,
            'kafkiansky',
            'raw bytes',
            ['php', 'proto'],
            ['x' => 200],
            ['enabled' => true],
            $time,
            new \DateInterval('PT10S'),
            new NestedMessage(
                200,
                'kek0',
                [1, 2, 3],
            ),
            'lol',
            [
                new AnotherNestedMessage(
                    $time,
                    ['releaseDate' => null],
                    ['x' => 'y'],
                ),
            ],
            new DurationMessage(new \DateInterval('PT60S')),
        );
    }

    public static function withSpecialFieldInt(): self
    {
        $time = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%d.%d', 1720809416, 679224));
        \assert($time instanceof \DateTimeImmutable);

        return new self(
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11.199999809265137,
            12.4,
            true,
            'kafkiansky',
            'raw bytes',
            ['php', 'proto'],
            ['x' => 200],
            ['enabled' => true],
            $time,
            new \DateInterval('PT10S'),
            new NestedMessage(
                200,
                'kek0',
                [1, 2, 3],
            ),
            1024,
            [
                new AnotherNestedMessage(
                    $time,
                    ['releaseDate' => null],
                    ['x' => 'y'],
                ),
            ],
            new DurationMessage(new \DateInterval('PT60S')),
        );
    }

    public static function withSpecialFieldNestedMessage(): self
    {
        $time = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%d.%d', 1720809416, 679224));
        \assert($time instanceof \DateTimeImmutable);

        return new self(
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11.199999809265137,
            12.4,
            true,
            'kafkiansky',
            'raw bytes',
            ['php', 'proto'],
            ['x' => 200],
            ['enabled' => true],
            $time,
            new \DateInterval('PT10S'),
            new NestedMessage(
                200,
                'kek0',
                [1, 2, 3],
            ),
            new NestedMessage(13, 'message', [10, 20]),
            [
                new AnotherNestedMessage(
                    $time,
                    ['releaseDate' => null],
                    ['x' => 'y'],
                ),
            ],
            new DurationMessage(new \DateInterval('PT60S')),
        );
    }

    public static function withSpecialFieldDateTime(): self
    {
        $time = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%d.%d', 1720809416, 679224));
        \assert($time instanceof \DateTimeImmutable);

        return new self(
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11.199999809265137,
            12.4,
            true,
            'kafkiansky',
            'raw bytes',
            ['php', 'proto'],
            ['x' => 200],
            ['enabled' => true],
            $time,
            new \DateInterval('PT10S'),
            new NestedMessage(
                200,
                'kek0',
                [1, 2, 3],
            ),
            $time,
            [
                new AnotherNestedMessage(
                    $time,
                    ['releaseDate' => null],
                    ['x' => 'y'],
                ),
            ],
            new DurationMessage(new \DateInterval('PT60S')),
        );
    }

    public static function emptyAtAll(): self
    {
        return new self();
    }
}

#[ProtobufMessage(path: 'resources/primitive_maps.bin', constructorFunction: 'default')]
#[ProtobufMessage(path: 'resources/empty.bin', constructorFunction: 'empty')]
final class PrimitiveMaps
{
    /**
     * @param array<string, int32> $mapStringInt32
     * @param array<int32, string> $mapInt32String
     * @param array<int32, bool> $mapInt32Bool
     * @param array<int64, int32> $mapInt64Int32
     * @param array<uint32, int32> $mapUint32Int32
     * @param array<uint64, int32> $mapUint64Int32
     * @param array<sint32, int32> $mapSint32Int32
     * @param array<sint64, int32> $mapSint64Int32
     * @param array<fixed32, int32> $mapFixed32Int32
     * @param array<fixed64, int32> $mapFixed64Int32
     * @param array<sfixed32, int32> $mapSfixed32Int32
     * @param array<sfixed64, int32> $mapSfixed64Int32
     * @param array<string, string> $mapStringString
     * @param array<int32, int32> $mapInt32Int32
     * @param array<int64, string> $mapInt64String
     * @param array<uint32, string> $mapUint32String
     * @param array<uint64, string> $mapUint64String
     * @param array<sint32, string> $mapSint32String
     * @param array<sint64, string> $mapSint64String
     * @param array<fixed32, string> $mapFixed32String
     * @param array<fixed64, string> $mapFixed64String
     * @param array<sfixed32, string> $mapSfixed32String
     * @param array<sfixed64, string> $mapSfixed64String
     * @param array<string, bool> $mapStringBool
     * @param array<string, float> $mapStringFloat
     * @param array<string, double> $mapStringDouble
     * @param array<string, int64> $mapStringInt64
     * @param array<string, uint32> $mapStringUint32
     * @param array<string, uint64> $mapStringUint64
     * @param array<string, sint32> $mapStringSint32
     * @param array<string, sint64> $mapStringSint64
     * @param array<string, fixed32> $mapStringFixed32
     * @param array<string, fixed64> $mapStringFixed64
     * @param array<string, sfixed32> $mapStringSfixed32
     * @param array<string, sfixed64> $mapStringSfixed64
     */
    public function __construct(
        public readonly array $mapStringInt32 = [],
        public readonly array $mapInt32String = [],
        public readonly array $mapInt32Bool = [],
        public readonly array $mapInt64Int32 = [],
        public readonly array $mapUint32Int32 = [],
        public readonly array $mapUint64Int32 = [],
        public readonly array $mapSint32Int32 = [],
        public readonly array $mapSint64Int32 = [],
        public readonly array $mapFixed32Int32 = [],
        public readonly array $mapFixed64Int32 = [],
        public readonly array $mapSfixed32Int32 = [],
        public readonly array $mapSfixed64Int32 = [],
        public readonly array $mapStringString = [],
        public readonly array $mapInt32Int32 = [],
        public readonly array $mapInt64String = [],
        public readonly array $mapUint32String = [],
        public readonly array $mapUint64String = [],
        public readonly array $mapSint32String = [],
        public readonly array $mapSint64String = [],
        public readonly array $mapFixed32String = [],
        public readonly array $mapFixed64String = [],
        public readonly array $mapSfixed32String = [],
        public readonly array $mapSfixed64String = [],
        public readonly array $mapStringBool = [],
        public readonly array $mapStringFloat = [],
        public readonly array $mapStringDouble = [],
        public readonly array $mapStringInt64 = [],
        public readonly array $mapStringUint32 = [],
        public readonly array $mapStringUint64 = [],
        public readonly array $mapStringSint32 = [],
        public readonly array $mapStringSint64 = [],
        public readonly array $mapStringFixed32 = [],
        public readonly array $mapStringFixed64 = [],
        public readonly array $mapStringSfixed32 = [],
        public readonly array $mapStringSfixed64 = [],
    ) {}

    public static function default(): self
    {
        return new self(
            mapStringInt32: ['key1' => 1, 'key2' => 2],
            mapInt32String: [1 => 'value1', 2 => 'value2'],
            mapInt32Bool: [1 => true, 2 => false],
            mapInt64Int32: [1000000000000 => 1, 2000000000000 => 2],
            mapUint32Int32: [1 => 1, 2 => 2],
            mapUint64Int32: [1000000000000 => 1, 2000000000000 => 2],
            mapSint32Int32: [1 => 1, 2 => 2],
            mapSint64Int32: [1000000000000 => 1, 2000000000000 => 2],
            mapFixed32Int32: [1 => 1, 2 => 2],
            mapFixed64Int32: [1000000000000 => 1, 2000000000000 => 2],
            mapSfixed32Int32: [1 => 1, 2 => 2],
            mapSfixed64Int32: [1000000000000 => 1, 2000000000000 => 2],
            mapStringString: ['key1' => 'value1', 'key2' => 'value2'],
            mapInt32Int32: [1 => 1, 2 => 2],
            mapInt64String: [1000000000000 => "value1", 2000000000000 => "value2"],
            mapUint32String: [1 => 'value1', 2 => 'value2'],
            mapUint64String: [1000000000000 => 'value1', 2000000000000 => 'value2'],
            mapSint32String: [1 => 'value1', 2 => "value2"],
            mapSint64String: [1000000000000 => 'value1', 2000000000000 => 'value2'],
            mapFixed32String: [1 => 'value1', 2 => 'value2'],
            mapFixed64String: [1000000000000 => 'value1', 2000000000000 => 'value2'],
            mapSfixed32String: [1 => 'value1', 2 => 'value2'],
            mapSfixed64String: [1000000000000 => 'value1', 2000000000000 => 'value2'],
            mapStringBool: ['key1' => true, 'key2' => false],
            mapStringFloat: ['key1' => 1.5, 'key2' => 2.5],
            mapStringDouble: ['key1' => 1.2, 'key2' => 2.2],
            mapStringInt64: ['key1' => 1000000000000, 'key2' => 2000000000000],
            mapStringUint32: ['key1' => 1, 'key2' => 2],
            mapStringUint64: ['key1' => 1000000000000, 'key2' => 2000000000000],
            mapStringSint32: ['key1' => 1, 'key2' => 2],
            mapStringSint64: ['key1' => 1000000000000, 'key2' => 2000000000000],
            mapStringFixed32: ['key1' => 1, 'key2' => 2],
            mapStringFixed64: ['key1' => 1000000000000, 'key2' => 2000000000000],
            mapStringSfixed32: ['key1' => 1, 'key2' => 2],
            mapStringSfixed64: ['key1' => 1000000000000, 'key2' => 2000000000000],
        );
    }

    public static function empty(): self
    {
        return new self();
    }
}

#[ProtobufMessage(path: 'resources/primitive_lists.bin', constructorFunction: 'default')]
#[ProtobufMessage(path: 'resources/empty.bin', constructorFunction: 'empty')]
final class PrimitiveLists
{
    /**
     * @param list<int32> $int32List
     * @param list<int64> $int64List
     * @param list<uint32> $uint32List
     * @param list<uint64> $uint64List
     * @param list<sint32> $sint32List
     * @param list<sint64> $sint64List
     * @param list<fixed32> $fixed32List
     * @param list<fixed64> $fixed64List
     * @param list<sfixed32> $sfixed32List
     * @param list<sfixed64> $sfixed64List
     * @param list<float> $floatList
     * @param list<double> $doubleList
     * @param list<bool> $boolList
     * @param list<string> $stringList
     * @param list<bytes> $bytesList
     */
    public function __construct(
        public readonly array $int32List = [],
        public readonly array $int64List = [],
        public readonly array $uint32List = [],
        public readonly array $uint64List = [],
        public readonly array $sint32List = [],
        public readonly array $sint64List = [],
        public readonly array $fixed32List = [],
        public readonly array $fixed64List = [],
        public readonly array $sfixed32List = [],
        public readonly array $sfixed64List = [],
        public readonly array $floatList = [],
        public readonly array $doubleList = [],
        public readonly array $boolList = [],
        public readonly array $stringList = [],
        public readonly array $bytesList = [],
    ) {}

    public static function default(): self
    {
        return new self(
            int32List: [1, 2, 3],
            int64List: [1000000000000, 2000000000000],
            uint32List: [1, 2, 3],
            uint64List: [1000000000000, 2000000000000],
            sint32List: [1, 2, 3],
            sint64List: [1000000000000, 2000000000000],
            fixed32List: [1, 2, 3],
            fixed64List: [1000000000000, 2000000000000],
            sfixed32List: [1, 2, 3],
            sfixed64List: [1000000000000, 2000000000000],
            floatList: [1.5, 2.5, 3.5],
            doubleList: [1.1, 2.2, 3.3],
            boolList: [true, false, true],
            stringList: ['string1', 'string2', 'string3'],
            bytesList: [base64_encode('bytes1'), base64_encode('bytes2')],
        );
    }

    public static function empty(): self
    {
        return new self();
    }
}

#[ProtobufMessage(path: 'resources/scalars.bin', constructorFunction: 'default')]
#[ProtobufMessage(path: 'resources/empty.bin', constructorFunction: 'empty')]
final class Scalars
{
    /**
     * @param int32 $int32Field
     * @param int64 $int64Field
     * @param uint32 $uint32Field
     * @param uint64 $uint64Field
     * @param sint32 $sint32Field
     * @param sint64 $sint64Field
     * @param fixed32 $fixed32Field
     * @param fixed64 $fixed64Field
     * @param sfixed32 $sfixed32Field
     * @param sfixed64 $sfixed64Field
     * @param float $floatField
     * @param double $doubleField
     * @param bool $boolField
     * @param string $stringField
     * @param bytes $bytesField
     */
    public function __construct(
        public readonly int $int32Field = 0,
        public readonly int $int64Field = 0,
        public readonly int $uint32Field = 0,
        public readonly int $uint64Field = 0,
        public readonly int $sint32Field = 0,
        public readonly int $sint64Field = 0,
        public readonly int $fixed32Field = 0,
        public readonly int $fixed64Field = 0,
        public readonly int $sfixed32Field = 0,
        public readonly int $sfixed64Field = 0,
        public readonly float $floatField = 0.0,
        public readonly float $doubleField = 0.0,
        public readonly bool $boolField = false,
        public readonly string $stringField = '',
        public readonly string $bytesField = '',
    ) {}

    public static function default(): self
    {
        return new self(
            int32Field: 123,
            int64Field: 1234567890123456789,
            uint32Field: 123,
            uint64Field: 1234567890123456789,
            sint32Field: -123,
            sint64Field: -1234567890123456789,
            fixed32Field: 123456,
            fixed64Field: 1234567890123456789,
            sfixed32Field: -123456,
            sfixed64Field: -1234567890123456789,
            floatField: 1.25,
            doubleField: 1.23456789,
            boolField: true,
            stringField: 'Hello, World!',
            bytesField: base64_encode('BinaryData'),
        );
    }

    public static function empty(): self
    {
        return new self();
    }
}

enum ExampleEnum: int
{
    case UNKNOWN = 0;
    case FIRST_OPTION = 1;
    case SECOND_OPTION = 2;
}

final class InnerInnerMessage
{
    public function __construct(
        public readonly string $deepInnerField,
    ) {}
}

final class InnerMessage
{
    /**
     * @param int32 $innerField
     */
    public function __construct(
        public readonly int $innerField,
        public readonly string $innerString,
        public readonly InnerInnerMessage $deepInnerMessage,
    ) {}
}

final class OuterMessage
{
    /**
     * @param array<string, InnerMessage> $mapOfInnerMessages
     * @param list<InnerMessage> $repeatedInnerMessages
     */
    public function __construct(
        public readonly ExampleEnum $enumField,
        public readonly InnerMessage $innerMessageField,
        public readonly array $mapOfInnerMessages,
        public readonly \DateTimeInterface $timestampField,
        public readonly \DateInterval $durationField,
        public readonly array $repeatedInnerMessages,
    ) {}
}

#[ProtobufMessage(path: 'resources/root.bin', constructorFunction: 'default')]
#[ProtobufMessage(path: 'resources/empty.bin', constructorFunction: 'empty')]
final class RootMessage
{
    /**
     * @param array<string, OuterMessage> $mapOfOuterMessages
     * @param list<OuterMessage> $repeatedOuterMessages
     */
    public function __construct(
        public readonly ?OuterMessage $outerMessageField = null,
        public readonly array $mapOfOuterMessages = [],
        public readonly array $repeatedOuterMessages = [],
    ) {}

    public static function default(): self
    {
        $deepInnerMessage = new InnerInnerMessage('Deep Inner Message');

        $innerMessage = new InnerMessage(123, 'Inner Message', $deepInnerMessage);

        $mapOfInnerMessages = [
            'key1' => $innerMessage,
            'key2' => new InnerMessage(456, 'Another Inner Message', $deepInnerMessage),
        ];

        $timestamp = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%d.%d', 1720809416, 679224));
        \assert($timestamp instanceof \DateTimeInterface);

        $duration = new \DateInterval('PT10S');

        $outerMessage = new OuterMessage(
            enumField: ExampleEnum::FIRST_OPTION,
            innerMessageField: $innerMessage,
            mapOfInnerMessages: $mapOfInnerMessages,
            timestampField: $timestamp,
            durationField: $duration,
            repeatedInnerMessages: [$innerMessage],
        );

        return new self(
            outerMessageField: $outerMessage,
            mapOfOuterMessages: [
                'outerKey1' => $outerMessage,
                'outerKey2' => new OuterMessage(
                    enumField: ExampleEnum::SECOND_OPTION,
                    innerMessageField: $innerMessage,
                    mapOfInnerMessages: $mapOfInnerMessages,
                    timestampField: $timestamp,
                    durationField: $duration,
                    repeatedInnerMessages: [$innerMessage],
                ),
            ],
            repeatedOuterMessages: [$outerMessage],
        );
    }

    public static function empty(): self
    {
        return new self();
    }
}

#[ProtobufMessage(path: 'resources/recursive.bin', constructorFunction: 'default')]
#[ProtobufMessage(path: 'resources/empty.bin', constructorFunction: 'empty')]
final class RecursiveMessage
{
    /**
     * @param int32 $value
     */
    public function __construct(
        public readonly int $value,
        public readonly ?self $nested,
    ) {}

    public static function default(): self
    {
        return new self(1, new self(2, new self(3, null)));
    }

    public static function empty(): self
    {
        return new self(0, null);
    }
}

enum Status: int
{
    case UNKNOWN = 0;
    case ACTIVE = 1;
    case INACTIVE = 2;
}

final class TooComplexInnerMessage
{
    /**
     * @param int32 $id
     * @param string $name
     * @param list<string> $tags
     * @param array<string, string> $attributes
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly array $tags,
        public readonly array $attributes,
        public readonly \DateTimeInterface $createdAt,
        public readonly \DateInterval $duration,
    ) {}
}

#[ProtobufMessage(path: 'resources/too_complex_message.bin', constructorFunction: 'default')]
#[ProtobufMessage(path: 'resources/empty.bin', constructorFunction: 'empty')]
final class TooComplexMessage
{
    /**
     * @param array<string, TooComplexInnerMessage> $mapOfInnerMessages
     * @param list<TooComplexInnerMessage> $repeatedInnerMessages
     * @param RecursiveMessage $recursiveMessage
     * @param list<int32> $repeatedInts
     * @param array<string, int32> $mapOfInts
     */
    public function __construct(
        public readonly Status $status = Status::UNKNOWN,
        public readonly ?TooComplexInnerMessage $innerMessage = null,
        public readonly array $mapOfInnerMessages = [],
        public readonly array $repeatedInnerMessages = [],
        public readonly ?RecursiveMessage $recursiveMessage = null,
        public readonly array $repeatedInts = [],
        public readonly array $mapOfInts = [],
    ) {}

    public static function default(): self
    {
        $timestamp = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%d.%d', 1720809416, 679224));
        \assert($timestamp instanceof \DateTimeInterface);

        $duration = new \DateInterval('PT10S');

        $innerMessage1 = new TooComplexInnerMessage(
            id: 1,
            name: 'Inner Message 1',
            tags: ['tag1', 'tag2'],
            attributes: ['attr1' => 'value1', 'attr2' => 'value2'],
            createdAt: $timestamp,
            duration: $duration,
        );

        $innerMessage2 = new TooComplexInnerMessage(
            id: 2,
            name: 'Inner Message 2',
            tags: ['tag3', 'tag4'],
            attributes: ['attr3' => 'value3', 'attr4' => 'value4'],
            createdAt: $timestamp,
            duration: $duration,
        );

        return new self(
            status: Status::ACTIVE,
            innerMessage: $innerMessage1,
            mapOfInnerMessages: [
                'key1' => $innerMessage1,
                'key2' => $innerMessage2,
            ],
            repeatedInnerMessages: [$innerMessage1, $innerMessage2],
            recursiveMessage: new RecursiveMessage(1, new RecursiveMessage(2, new RecursiveMessage(3, null))),
            repeatedInts: [1, 2, 3, 4, 5],
            mapOfInts: ['one' => 1, 'two' => 2, 'three' => 3],
        );
    }

    public static function empty(): self
    {
        return new self();
    }
}

final class InnerForUnionMessage
{
    /**
     * @param int64 $id
     * @param list<string> $tags
     * @param array<string, string> $attributes
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly array $tags,
        public readonly array $attributes,
        public readonly \DateTimeInterface $createdAt,
        public readonly \DateInterval $duration,
    ) {}
}

final class NestedUnionMessage
{
    public function __construct(
        public readonly string $id,
        public readonly UnionComplexMessage|Status $nestedPayload,
    ) {}
}

#[ProtobufMessage(path: 'resources/complex_union_string.bin', constructorFunction: 'unionString')]
#[ProtobufMessage(path: 'resources/complex_union_int.bin', constructorFunction: 'unionInt')]
#[ProtobufMessage(path: 'resources/complex_union_nested.bin', constructorFunction: 'unionNested')]
#[ProtobufMessage(path: 'resources/complex_union_of_unions.bin', constructorFunction: 'unionOfUnions')]
final class UnionComplexMessage
{
    /**
     * @param string|fixed32|float|bool|\DateTimeInterface|\DateInterval|InnerForUnionMessage $payload
     */
    public function __construct(
        public readonly Status $status,
        public readonly string|int|float|bool|\DateTimeInterface|\DateInterval|InnerForUnionMessage $payload,
        public readonly \DateTimeInterface|string|NestedUnionMessage $additionalPayload,
        public readonly NestedUnionMessage $outer,
    ) {}

    public static function unionString(): self
    {
        $timestamp = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%d.%d', 1720809416, 679224));
        \assert($timestamp instanceof \DateTimeInterface);

        $nested = new NestedUnionMessage('id', Status::ACTIVE);

        return new self(
            status: Status::ACTIVE,
            payload: 'string payload',
            additionalPayload: $timestamp,
            outer: $nested,
        );
    }

    public static function unionInt(): self
    {
        $nested = new NestedUnionMessage('id', Status::ACTIVE);

        return new self(
            status: Status::ACTIVE,
            payload: 123,
            additionalPayload: 'optional string',
            outer: $nested,
        );
    }

    public static function unionNested(): self
    {
        $timestamp = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%d.%d', 1720809416, 679224));
        \assert($timestamp instanceof \DateTimeInterface);

        $duration = new \DateInterval('PT10S');

        $innerMessage = new InnerForUnionMessage(
            id: 1,
            name: 'Inner Message',
            tags: ['tag1', 'tag2'],
            attributes: ['attr1' => 'value1', 'attr2' => 'value2'],
            createdAt: $timestamp,
            duration: $duration,
        );

        $nested = new NestedUnionMessage('id', Status::ACTIVE);

        return new self(
            status: Status::ACTIVE,
            payload: $innerMessage,
            additionalPayload: 'optional string',
            outer: $nested,
        );
    }

    public static function unionOfUnions(): self
    {
        $timestamp = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%d.%d', 1720809416, 679224));
        \assert($timestamp instanceof \DateTimeInterface);

        $duration = new \DateInterval('PT10S');

        $innerMessage = new InnerForUnionMessage(
            id: 1,
            name: 'Inner Message',
            tags: ['tag1', 'tag2'],
            attributes: ['attr1' => 'value1', 'attr2' => 'value2'],
            createdAt: $timestamp,
            duration: $duration,
        );

        $nested = new NestedUnionMessage('id', Status::ACTIVE);

        $complexMessageString = new self(
            status: Status::ACTIVE,
            payload: 'string payload',
            additionalPayload: $timestamp,
            outer: $nested,
        );

        $nestedOneOfMessage = new NestedUnionMessage(
            id: 'nested1',
            nestedPayload: $complexMessageString,
        );

        $nestedOneOfMessageStatus = new NestedUnionMessage(
            id: 'nested2',
            nestedPayload: Status::INACTIVE,
        );

        return new self(
            Status::INACTIVE,
            $innerMessage,
            $nestedOneOfMessage,
            $nestedOneOfMessageStatus,
        );
    }
}

#[ProtobufMessage(path: 'resources/root.bin', constructorFunction: 'default')]
final class EmptyMessageWillDiscardAllFields
{
    public static function default(): self
    {
        return new self();
    }
}

#[ProtobufMessage(path: 'resources/complex_array_shape.bin', constructorFunction: 'default')]
final class Company
{
    /**
     * @param list<array{
     *     name: string,
     *     id: int32,
     *     email: string,
     *     phones: list<array{number: string, type?: PhoneType}>,
     *     address: array{street: string, city: string, state: string, zip: int32},
     *  }> $employees
     * @param array{
     *     street: string,
     *     city: string,
     *     state: string,
     *     zip: int32,
     * } $headquarters
     * @param array<string, array{
     *     name: string,
     *     description: string,
     *     tasks: list<array{title: string, details: string, priority: int32, deadline: \DateTimeInterface}>,
     *     validUntil: \DateInterval,
     * }> $projects
     */
    public function __construct(
        public readonly string $name,
        public readonly array $employees,
        public readonly array $headquarters,
        public readonly array $projects,
    ) {}

    public static function default(): self
    {
        $timestamp = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%d.%d', 1720809416, 679224));
        \assert($timestamp instanceof \DateTimeInterface);

        $duration = new \DateInterval('PT86400S');

        return new self(
            'Tech Innovators Inc.',
            [
                [
                    'name' => 'Alice Johnson',
                    'id' => 1,
                    'email' => 'alice.johnson@example.com',
                    'phones' => [
                        [
                            'number' => '123-456-7890',
                        ],
                        [
                            'number' => '098-765-4321',
                            'type' => PhoneType::WORK,
                        ],
                    ],
                    'address' => [
                        'street' => '123 Main St',
                        'city' => 'Anytown',
                        'state' => 'CA',
                        'zip' => 12345,
                    ],
                ],
                [
                    'name' => 'Bob Smith',
                    'id' => 2,
                    'email' => 'bob.smith@example.com',
                    'phones' => [
                        [
                            'number' => '555-555-5555',
                            'type' => PhoneType::HOME,
                        ],
                    ],
                    'address' => [
                        'street' => '456 Oak St',
                        'city' => 'Othertown',
                        'state' => 'NY',
                        'zip' => 67890,
                    ],
                ],
            ],
            [
                'street' => '123 Main St',
                'city' => 'Anytown',
                'state' => 'CA',
                'zip' => 12345,
            ],
            [
                'ProjectX' => [
                    'name' => 'Project X',
                    'description' => 'A secret project.',
                    'tasks' => [
                        [
                            'title' => 'Task 1',
                            'details' => 'Detail for task 1',
                            'priority' => 1,
                            'deadline' => $timestamp,
                        ],
                        [
                            'title' => 'Task 2',
                            'details' => 'Detail for task 2',
                            'priority' => 2,
                            'deadline' => $timestamp,
                        ],
                    ],
                    'validUntil' => $duration,
                ],
                'ProjectY' => [
                    'name' => 'Project Y',
                    'description' => 'Another secret project.',
                    'tasks' => [
                        [
                            'title' => 'Task A',
                            'details' => 'Detail for task A',
                            'priority' => 1,
                            'deadline' => $timestamp,
                        ],
                    ],
                    'validUntil' => $duration,
                ],
            ],
        );
    }
}
