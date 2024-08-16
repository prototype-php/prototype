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

namespace Prototype\Tests\Compiler;

use Antlr\Antlr4\Runtime\InputStream;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Prototype\Compiler\Compiler;
use Prototype\Compiler\Import\CombineImportResolver;
use Prototype\Compiler\Import\ConstantImportResolver;
use Prototype\Compiler\Import\ImportFile;
use Prototype\Compiler\Import\VirtualImportResolver;
use Prototype\Compiler\Internal\Ir\Validate\ConstraintViolated;
use Prototype\Compiler\Output\PhpFile;
use Prototype\Compiler\Output\Writer;
use Prototype\Compiler\ProtoFile;

#[CoversClass(Compiler::class)]
final class CompilerTest extends TestCase
{
    /**
     * @return iterable<array-key, array{non-empty-string, PhpFile[], array<non-empty-string, ImportFile>}>
     */
    public static function compileFixtures(): iterable
    {
        yield 'scalars' => [
            <<<'PROTO'
syntax = "proto3";

package api.v1.test;

option php_namespace = "App\\V1\\Test";

message RequestWithScalarTypes {
    bool bool_field = 1;
    int32 int32_field = 2;
    int64 int64_field = 3;
    uint32 uint32_field = 4;
    uint64 uint64_field = 5;
    sint32 sint32_field = 6;
    sint64 sint64_field = 7;
    fixed32 fixed32_field = 8;
    fixed64 fixed64_field = 9;
    sfixed32 sfixed32_field = 10;
    sfixed64 sfixed64_field = 11;
    float float_field = 12;
    double double_field = 13;
    string string_field = 14;
    bytes bytes_field = 15;
}

message SimpleResponse {
    int32 status = 1;
}
PROTO,
            [
                new PhpFile(
                    'App\V1\Test',
                    'RequestWithScalarTypes.php',
                    <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\V1\Test;

/**
 * @api
 */
final class RequestWithScalarTypes
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
     * @param double $doubleField
     * @param bytes $bytesField
     */
    public function __construct(
        public readonly ?bool $boolField = null,
        public readonly ?int $int32Field = null,
        public readonly ?int $int64Field = null,
        public readonly ?int $uint32Field = null,
        public readonly ?int $uint64Field = null,
        public readonly ?int $sint32Field = null,
        public readonly ?int $sint64Field = null,
        public readonly ?int $fixed32Field = null,
        public readonly ?int $fixed64Field = null,
        public readonly ?int $sfixed32Field = null,
        public readonly ?int $sfixed64Field = null,
        public readonly ?float $floatField = null,
        public readonly ?float $doubleField = null,
        public readonly ?string $stringField = null,
        public readonly ?string $bytesField = null,
    ) {
    }
}

PHP,
                ),
                new PhpFile(
                    'App\V1\Test',
                    'SimpleResponse.php',
                    <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\V1\Test;

/**
 * @api
 */
final class SimpleResponse
{
    /**
     * @param int32 $status
     */
    public function __construct(
        public readonly ?int $status = null,
    ) {
    }
}

PHP,
                ),
            ],
            [],
        ];

        yield 'wellknown' => [
            <<<'PROTO'
syntax = "proto3";

package api.v1.test;

option php_namespace = "App\\V1\\Test";

import "google/protobuf/timestamp.proto";
import "google/protobuf/duration.proto";
import "google/protobuf/struct.proto";
import "google/protobuf/field_mask.proto";
import "google/type/color.proto";
import "google/type/latlng.proto";
import "google/type/postal_address.proto";
import "google/type/phone_number.proto";
import "google/type/money.proto";
import "google/type/calendar_period.proto";
import "google/type/date.proto";
import "google/type/timeofday.proto";
import "google/type/localized_text.proto";
import "google/type/expr.proto";

message RequestWithWellKnownTypes {
    google.protobuf.Timestamp timestamp_field = 1;
    google.protobuf.Duration duration_field = 2;
    google.protobuf.Struct struct_field = 3;
    google.protobuf.FieldMask field_mask_field = 4;
    google.type.Color color_field = 5;
    google.type.LatLng latlng_field = 6;
    google.type.PostalAddress postal_address_field = 7;
    google.type.PhoneNumber phone_number_field = 8;
    google.type.Money money_field = 9;
    google.type.CalendarPeriod calendar_period_field = 10;
    google.type.Date date_field = 11;
    google.type.TimeOfDay timeofday_field = 12;
    google.type.LocalizedText localized_text_field = 13;
    google.type.Expr expr_field = 14;
}
PROTO,
            [
                new PhpFile(
                    'App\V1\Test',
                    'RequestWithWellKnownTypes.php',
                    <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\V1\Test;

use Prototype\WellKnown\CalendarPeriod;
use Prototype\WellKnown\Color;
use Prototype\WellKnown\Date;
use Prototype\WellKnown\Expr;
use Prototype\WellKnown\FieldMask;
use Prototype\WellKnown\LatLng;
use Prototype\WellKnown\LocalizedText;
use Prototype\WellKnown\Money;
use Prototype\WellKnown\PhoneNumber;
use Prototype\WellKnown\PostalAddress;
use Prototype\WellKnown\TimeOfDay;

/**
 * @api
 */
final class RequestWithWellKnownTypes
{
    /**
     * @param array<string, mixed> $structField
     */
    public function __construct(
        public readonly ?\DateTimeInterface $timestampField = null,
        public readonly ?\DateInterval $durationField = null,
        public readonly array $structField = [],
        public readonly ?FieldMask $fieldMaskField = null,
        public readonly ?Color $colorField = null,
        public readonly ?LatLng $latlngField = null,
        public readonly ?PostalAddress $postalAddressField = null,
        public readonly ?PhoneNumber $phoneNumberField = null,
        public readonly ?Money $moneyField = null,
        public readonly ?CalendarPeriod $calendarPeriodField = null,
        public readonly ?Date $dateField = null,
        public readonly ?TimeOfDay $timeofdayField = null,
        public readonly ?LocalizedText $localizedTextField = null,
        public readonly ?Expr $exprField = null,
    ) {
    }
}

PHP,
                ),
            ],
            [],
        ];

        yield 'relations' => [
            <<<'PROTO'
syntax = "proto3";

package api.v1.test;

option php_namespace = "App\\V1\\Test";

message Request {
    message Task {
        enum Type {
            UNSPECIFIED = 0;
            A = 1;
            B = 2;
        }

        Type type = 1;
        api.v1.test.Type task_type = 2;
    }

    Type type = 1;
    repeated Task tasks = 2;
}

enum Type {
    UNSPECIFIED = 0;
    SYNC = 1;
    ASYNC = 2;
}

message Response {
    Request.Task.Type type = 1;
}

PROTO,
            [
                new PhpFile(
                    'App\V1\Test',
                    'Request.php',
                    <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\V1\Test;

/**
 * @api
 */
final class Request
{
    /**
     * @param list<RequestTask> $tasks
     */
    public function __construct(
        public readonly ?Type $type = null,
        public readonly array $tasks = [],
    ) {
    }
}

PHP,
                ),
                new PhpFile(
                    'App\V1\Test',
                    'RequestTask.php',
                    <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\V1\Test;

/**
 * @api
 */
final class RequestTask
{
    public function __construct(
        public readonly ?RequestTaskType $type = null,
        public readonly ?Type $taskType = null,
    ) {
    }
}

PHP,
                ),
                new PhpFile(
                    'App\V1\Test',
                    'RequestTaskType.php',
                    <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\V1\Test;

/**
 * @api
 */
enum RequestTaskType: int
{
    case UNSPECIFIED = 0;
    case A = 1;
    case B = 2;
}

PHP,
                ),
                new PhpFile(
                    'App\V1\Test',
                    'Type.php',
                    <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\V1\Test;

/**
 * @api
 */
enum Type: int
{
    case UNSPECIFIED = 0;
    case SYNC = 1;
    case ASYNC = 2;
}

PHP,
                ),
                new PhpFile(
                    'App\V1\Test',
                    'Response.php',
                    <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\V1\Test;

/**
 * @api
 */
final class Response
{
    public function __construct(
        public readonly ?RequestTaskType $type = null,
    ) {
    }
}

PHP,
                ),
            ],
            [],
        ];

        yield 'builtin' => [
            <<<'PROTO'
syntax = "proto3";

package api.v1.test;

option php_namespace = "App\\V1\\Test";

message Namespace {
    repeated Class classes = 1;
}

message Class {}

PROTO,
            [
                new PhpFile(
                    'App\V1\Test',
                    'Namespace_.php',
                    <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\V1\Test;

/**
 * @api
 */
final class Namespace_
{
    /**
     * @param list<Class_> $classes
     */
    public function __construct(
        public readonly array $classes = [],
    ) {
    }
}

PHP,
                ),
                new PhpFile(
                    'App\V1\Test',
                    'Class_.php',
                    <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\V1\Test;

/**
 * @api
 */
final class Class_
{
    public function __construct()
    {
    }
}

PHP,
                ),
            ],
            [],
        ];

        yield 'explicit numbering' => [
            <<<'PROTO'
syntax = "proto3";

package api.v1.test;

option php_namespace = "App\\V1\\Test";

message Request {
    message Task {
        enum Type {
            UNSPECIFIED = 0;
        }

        Type type = 6;
    }

    repeated Task tasks = 1;
    string id = 3;
}


PROTO,
            [
                new PhpFile(
                    'App\V1\Test',
                    'Request.php',
                    <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\V1\Test;

use Prototype\Serializer\Field;

/**
 * @api
 */
final class Request
{
    /**
     * @param list<RequestTask> $tasks
     */
    public function __construct(
        #[Field(1)]
        public readonly array $tasks = [],
        #[Field(3)]
        public readonly ?string $id = null,
    ) {
    }
}

PHP,
                ),
                new PhpFile(
                    'App\V1\Test',
                    'RequestTask.php',
                    <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\V1\Test;

use Prototype\Serializer\Field;

/**
 * @api
 */
final class RequestTask
{
    public function __construct(
        #[Field(6)]
        public readonly ?RequestTaskType $type = null,
    ) {
    }
}

PHP,
                ),
                new PhpFile(
                    'App\V1\Test',
                    'RequestTaskType.php',
                    <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\V1\Test;

/**
 * @api
 */
enum RequestTaskType: int
{
    case UNSPECIFIED = 0;
}

PHP,
                ),
            ],
            [],
        ];

        yield 'imports' => [
            <<<'PROTO'
syntax = "proto3";

package api.v1.test;

import "api/v1/options.proto";
import "api/v2/deps.proto";

option php_namespace = "App\\V1\\Test";

message Request {
    repeated Task tasks = 1;
    map<string, api.v2.deps.TaskPayload.Label> labels = 2;
}

message Response {
    api.v1.test.Type type = 1;
}

PROTO,
            [
                new PhpFile(
                    'App\V1\Test',
                    'Request.php',
                    <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\V1\Test;

use App\V2\Deps\TaskPayloadLabel;

/**
 * @api
 */
final class Request
{
    /**
     * @param list<Task> $tasks
     * @param array<string, TaskPayloadLabel> $labels
     */
    public function __construct(
        public readonly array $tasks = [],
        public readonly array $labels = [],
    ) {
    }
}

PHP,
                ),
                new PhpFile(
                    'App\V1\Test',
                    'Response.php',
                    <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\V1\Test;

/**
 * @api
 */
final class Response
{
    public function __construct(
        public readonly ?Type $type = null,
    ) {
    }
}

PHP,
                ),
                new PhpFile(
                    'App\V1\Test',
                    'Task.php',
                    <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\V1\Test;

/**
 * @api
 */
final class Task
{
    public function __construct(
        public readonly ?Type $type = null,
    ) {
    }
}

PHP,
                ),
                new PhpFile(
                    'App\V1\Test',
                    'Type.php',
                    <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\V1\Test;

/**
 * @api
 */
enum Type: int
{
    case UNSPECIFIED = 0;
}

PHP,
                ),
                new PhpFile(
                    'App\V2\Deps',
                    'TaskPayload.php',
                    <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\V2\Deps;

/**
 * @api
 */
final class TaskPayload
{
    public function __construct()
    {
    }
}

PHP,
                ),
                new PhpFile(
                    'App\V2\Deps',
                    'TaskPayloadLabel.php',
                    <<<'PHP'
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace App\V2\Deps;

/**
 * @api
 */
final class TaskPayloadLabel
{
    public function __construct(
        public readonly ?string $name = null,
    ) {
    }
}

PHP,
                ),
            ],
            [
                'api/v1/options.proto' => new ImportFile(
                    'api/v1/options.proto',
                    InputStream::fromString(
                        <<<'PROTO'
syntax = "proto3";

package api.v1.test;

option php_namespace = "App\\V1\\Test";

message Task {
    Type type = 1;
}

enum Type {
    UNSPECIFIED = 0;
}

PROTO,
                    ),
                ),
                'api/v2/deps.proto' => new ImportFile(
                    'api/v2/deps.proto',
                    InputStream::fromString(
                        <<<'PROTO'
syntax = "proto3";

package api.v2.deps;

option php_namespace = "App\\V2\\Deps";

message TaskPayload {
    message Label {
        string name = 1;
    }
}

PROTO,
                    ),
                ),
            ],
        ];
    }

    /**
     * @param non-empty-string                    $protobuf
     * @param non-empty-list<PhpFile>             $phpFiles
     * @param array<non-empty-string, ImportFile> $imports
     */
    #[DataProvider('compileFixtures')]
    public function testCompile(string $protobuf, array $phpFiles, array $imports = []): void
    {
        $matcher = self::exactly(\count($phpFiles));

        $writer = $this->createMock(Writer::class);
        $writer
            ->expects($matcher)
            ->method('writePhpFile')
            ->willReturnCallback(static function (PhpFile $phpFile) use ($phpFiles, $matcher): void {
                self::assertEquals($phpFiles[$matcher->numberOfInvocations() - 1], $phpFile);
            })
        ;

        $compiler = Compiler::build(
            $writer,
            imports: new CombineImportResolver([
                new ConstantImportResolver(array_merge($imports, ['api/v1/test.proto' => new ImportFile('api/v1/test.proto', InputStream::fromString($protobuf))])),
                VirtualImportResolver::build(),
            ]),
        );
        $compiler->compile(ProtoFile::fromString($protobuf));
    }

    /**
     * @return iterable<array-key, array{non-empty-string, class-string<\Throwable>, non-empty-string, array<non-empty-string, ImportFile>}>
     */
    public static function notCompileFixtures(): iterable
    {
        yield 'enum without zero variant' => [
            <<<'PROTO'
syntax = "proto3";

package api.v1.test;

option php_namespace = "App\\V1\\Test";

enum Test {
    A = 1;
}

PROTO,
            ConstraintViolated::class,
            'The enum "Test" must contains zero variant.',
            [],
        ];

        yield 'enum with the same variant names' => [
            <<<'PROTO'
syntax = "proto3";

package api.v1.test;

option php_namespace = "App\\V1\\Test";

enum Test {
    A = 0;
    A = 1;
}

PROTO,
            ConstraintViolated::class,
            'Enum "Test" has variants with the same name "A".',
            [],
        ];

        yield 'enum with the same variant values' => [
            <<<'PROTO'
syntax = "proto3";

package api.v1.test;

option php_namespace = "App\\V1\\Test";

enum Test {
    A = 0;
    B = 1;
    C = 1;
}

PROTO,
            ConstraintViolated::class,
            'Variants "C" and "B" of enum "Test" has the same value "1".',
            [],
        ];

        yield 'nested enum with the same variant values' => [
            <<<'PROTO'
syntax = "proto3";

package api.v1.test;

option php_namespace = "App\\V1\\Test";

message Request {
    enum Test {
        A = 0;
        B = 1;
        C = 1;
    }
}

PROTO,
            ConstraintViolated::class,
            'Variants "C" and "B" of enum "Request.Test" has the same value "1".',
            [],
        ];

        yield 'message with the same field names' => [
            <<<'PROTO'
syntax = "proto3";

package api.v1.test;

option php_namespace = "App\\V1\\Test";

message Test {
    string a = 1;
    int A = 2;
}

PROTO,
            ConstraintViolated::class,
            'Message "Test" has fields with the same name "a".',
            [],
        ];

        yield 'message with the same field numbers' => [
            <<<'PROTO'
syntax = "proto3";

package api.v1.test;

option php_namespace = "App\\V1\\Test";

message Test {
    string a = 1;
    int b = 1;
}

PROTO,
            ConstraintViolated::class,
            'Fields "b" and "a" of message "Test" has the same order "1".',
            [],
        ];

        yield 'nested message with the same field names' => [
            <<<'PROTO'
syntax = "proto3";

package api.v1.test;

option php_namespace = "App\\V1\\Test";

message Request {
    message Test {
        string a = 1;
        int A = 2;
    }
}

PROTO,
            ConstraintViolated::class,
            'Message "Request.Test" has fields with the same name "a".',
            [],
        ];

        yield 'enum conflicts' => [
            <<<'PROTO'
syntax = "proto3";

package api.v1.test;

import "api/v1/options.proto";

option php_namespace = "App\\V1\\Test";

enum Type {
    UNSPECIFIED = 0;
}

PROTO,
            ConstraintViolated::class,
            '"api.v1.test.Type" is already defined in file "api/v1/options.proto".',
            [
                'api/v1/options.proto' => new ImportFile(
                    '/api/v1/options.proto',
                    InputStream::fromString(
                        <<<'PROTO'
syntax = "proto3";

package api.v1.test;

option php_namespace = "App\\V1\\Test";

enum Type {
    UNSPECIFIED = 0;
    A = 1;
}
PROTO,
                    ),
                ),
            ],
        ];

        yield 'message conflicts' => [
            <<<'PROTO'
syntax = "proto3";

package api.v1.test;

import "api/v1/options.proto";

option php_namespace = "App\\V1\\Test";

message Request {}

PROTO,
            ConstraintViolated::class,
            '"api.v1.test.Request" is already defined in file "api/v1/options.proto".',
            [
                'api/v1/options.proto' => new ImportFile(
                    '/api/v1/options.proto',
                    InputStream::fromString(
                        <<<'PROTO'
syntax = "proto3";

package api.v1.test;

option php_namespace = "App\\V1\\Test";

message Request {}
PROTO,
                    ),
                ),
            ],
        ];

        yield 'proto imports self' => [
            <<<'PROTO'
syntax = "proto3";

package api.v1.test;

import "api/v1/request.proto";

option php_namespace = "App\\V1\\Test";

message Request {}

PROTO,
            \LogicException::class,
            'File recursively imports itself: api/v1/request.proto -> api/v1/request.proto.',
            [],
        ];

        yield 'proto imports recursive from third party' => [
            <<<'PROTO'
syntax = "proto3";

package api.v1.test;

import "api/v1/options.proto";

option php_namespace = "App\\V1\\Test";

message Request {}

PROTO,
            \LogicException::class,
            'File recursively imports itself: api/v1/request.proto -> api/v1/options.proto -> api/v1/deps.proto -> api/v1/request.proto.',
            [
                'api/v1/options.proto' => new ImportFile(
                    'api/v1/options.proto',
                    InputStream::fromString(
                        <<<'PROTO'
syntax = "proto3";

package api.v1.test;

import "api/v1/deps.proto";

option php_namespace = "App\\V1\\Test";

PROTO,
                    ),
                ),
                'api/v1/deps.proto' => new ImportFile(
                    'api/v1/deps.proto',
                    InputStream::fromString(
                        <<<'PROTO'
syntax = "proto3";

package api.v1.test;

import "api/v1/request.proto";

option php_namespace = "App\\V1\\Test";

PROTO,
                    ),
                ),
            ],
        ];
    }

    /**
     * @param non-empty-string $protobuf
     * @param class-string<\Throwable> $exception
     * @param non-empty-string $exceptionMessage
     * @param array<non-empty-string, ImportFile> $imports
     */
    #[DataProvider('notCompileFixtures')]
    public function testDoesntCompile(string $protobuf, string $exception, string $exceptionMessage, array $imports = []): void
    {
        self::expectException($exception);
        self::expectExceptionMessage($exceptionMessage);

        $compiler = Compiler::build(
            imports: new CombineImportResolver([
                new ConstantImportResolver(array_merge($imports, ['api/v1/request.proto' => new ImportFile('api/v1/request.proto', InputStream::fromString($protobuf))])),
                VirtualImportResolver::build(),
            ]),
        );
        $compiler->compile(ProtoFile::fromString($protobuf, 'api/v1/request.proto'));
    }
}
