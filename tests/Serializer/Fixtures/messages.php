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
