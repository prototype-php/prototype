## Serializer

A modern strictly typed full-featured library for protobuf serialization without an inheritance.

Why do we need another protobuf serialization library when there's `google/protobuf`? PHP is rapidly evolving, and we have long had tools for static analysis at our disposal that help add strict typing to arrays, lists, generics, and so on.
However, all of this is missing in `google/protobuf`. It also lacks promoted readonly properties, modern enums, unions, but includes inheritance.

This library is tightly coupled to the static analysis tools (`psalm`, `phpstan`) and uses their type handling capabilities to produce metadata that is used for protobuf serialization.

## Installation

This package can be installed as a [Composer](https://getcomposer.org/) dependency.

```bash
composer require prototype/serializer
```

## Usage

The library should be used as follows:

```php
<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use Prototype\Serializer\Serializer;

final class Message
{
  public function __construct(
    public readonly string $name,
  ) {}
}

$serializer = new Serializer();

$byteBuffer = $serializer->serialize(new Message('kafkiansky'));

echo bin2hex($byteBuffer->reset()); // 0a0a6b61666b69616e736b79
```

Or if you need to deserialize:

```php
<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use Prototype\Serializer\Serializer;

final class Message
{
  public function __construct(
    public readonly string $name,
  ) {}
}

$serializer = new Serializer();

$byteBuffer = $serializer->serialize(new Message('kafkiansky'));

echo $serializer->deserialize($byteBuffer, Message::class)->name; // kafkiansky
```

## Features
- [scalars](#scalars)
- [enums](#enums)
- [repeated](#repeated)
- [maps](#maps)
- [oneof](#oneof)
- [timestamp](#timestamp)
- [duration](#duration)
- [struct](#struct)
- [array shape](#array-shape)
- [type alias](#type-alias)
- [value object](#value-object)

### Scalars

All scalars available in protobuf can be used as types in phpdoc.

```php
<?php

declare(strict_types=1);

final class Message
{
  /**
   * @param int32 $id
   * @param bytes $data
   * @param fixed64 $epoch
   */
  public function __construct(
    public readonly int $id,
    public readonly string $name,
    public readonly string $data,
    public readonly int $epoch,
  ) {}
}
```

Also [see](#types-mapping) the type compatibility table.

### Enums

You can use either native enums or constant enums as protobuf enums.

```php
<?php

declare(strict_types=1);

enum CompressionType: int
{
  case UNKNOWN = 0;
  case GZIP = 1;
  case LZ4 = 2;
}

final class Message
{
  public function __construct(
    public readonly CompressionType $type,
  ) {}
}
```

Enums must be of type `\IntBackedEnum` and must have a variant with value `0`, as required by the protobuf, to avoid serialization of zero value enums.

If you're too lazy to create a native enum, you can use the class constants as follows:

```php
<?php

declare(strict_types=1);

final class Message
{
    public const TYPE_UNKNOWN = 0;
    public const TYPE_NO_COMPRESSION = -1;
    public const TYPE_GZIP = 1;
    public const TYPE_LZ4 = 2;

    /**
     * @psalm-param self::TYPE_* $compressionType
     */
    public function __construct(
        public readonly int $compressionType,
    ) {}
}
```

A zero value is also mandatory.

But if you're lazy even for constants, you can use literal types with a mandatory `0` value:

```php
<?php

declare(strict_types=1);

final class Message
{
    /**
     * @psalm-param -1|0|1|2 $compressionType
     */
    public function __construct(
        public readonly int $compressionType,
    ) {}
}
```

You can also use type aliases if you want to give variants names:

```php
<?php

declare(strict_types=1);

/**
 * @psalm-type CompressionTypeUnknown = 0
 * @psalm-type CompressionTypeNone = -1
 * @psalm-type CompressionTypeGZIP = 1
 * @psalm-type CompressionTypeLZ4 = 2
 * @psalm-type CompressionType = CompressionTypeUnknown | CompressionTypeNone | CompressionTypeGZIP | CompressionTypeLZ4
 */
final class Message
{
    /**
     * @param CompressionType $compressionType
     */
    public function __construct(
        public readonly int $compressionType,
    ) {}
}
```

A zero value is still required.

### Repeated

To indicate that a field is repeated, it is not enough to say that it is of type `array`, you have to specify `list<T>` or `iterable<T>` via phpdoc, where `T` is any type (object, enum, scalar) except map and list, as protobuf requires.

```php
<?php

declare(strict_types=1);

final class Message
{
    /**
     * @param list<sint32> $partitions
     */
    public function __construct(
        public readonly array $partitions,
    ) {}
}
```

Lists consisting of primitive types (except `string` and `bytes`) will be encoded using `packed` encoding.

As already mentioned, lists can also consist of enums or objects:

```php
<?php

declare(strict_types=1);

enum Label: int
{
  case UNKNOWN = 0;
  case CANCEL = 1;
  case EXIT = 2;
}

final class Partition
{
  /**
   * @param sint32 $id
   */
  public function __construct(
    public readonly int $id,
  ) {}
}

final class Message
{
    /**
     * @param list<Label> $labels
     * @param iterable<Partition> $partitions
     */
    public function __construct(
        public readonly array $labels,
        public readonly iterable $partitions,
    ) {}
}
```

*It is not necessary to make such an array nullable in case the field is not passed. The `Serializer` itself will take care of initializing the lists with an empty (`[]`) value.*

### Maps

Maps in php can also be specified via phpdoc (either `array<K, V>` or `iterable<K, V>`), as you've already been doing for a long time.

```php
<?php

declare(strict_types=1);

final class Topic
{
  /**
   * @param list<sint32> $partitions
   * @param iterable<string, string> $options 
   */   
  public function __construct(
    public readonly array $partitions,
    public readonly iterable $options,
  ) {}
}

final class Message
{
    /**
     * @param array<string, Topic> $topics
     */
    public function __construct(
        public readonly array $topics,
    ) {}
}
```

Only a primitive type can take the place of a key, and it must be explicitly defined.

Like for lists, for maps the `Serializer` will also take care of putting a zero value (`[]`) if they are not present.

### OneOf

Protobuf's `oneof` can be used as native php union type.

```php
<?php

declare(strict_types=1);

final class Message
{
  /**
   * @param null|int32|string $id
   */
  public function __construct(
    public readonly null|int|string $id = null,
  ) {}
}
```

However, in this case null must be specified because `Serializer` does not know what type the default value should be.

Union may consist of all types that the protobuf allows.

```php
<?php

declare(strict_types=1);

final class RabbitMQ
{
  public function __construct(
    public readonly string $exchange,
    public readonly string $routingKey,
  ) {}
}

final class Kafka
{
  /**
   * @param sint32 $partition
   */
  public function __construct(
    public readonly string $topic,
    public readonly int $partition,
  ) {}
}

final class Message
{
  /**
   * @param bytes $value
   * @param array<string, string> $headers
   */
  public function __construct(
    public readonly string $value,
    public readonly null|RabbitMQ|Kafka $destination = null,
    public readonly array $headers = [],
  ) {}
}
```

### Timestamp

Protobuf's timestamp (`google.protobuf.Timestamp`) is mapped to any subtype of `\DateTimeInterface`, including `\DateTimeImmutable` and `\DateTime`. If you specify `\DateTimeInterface` as the type, `Serializer` will use `\DateTimeImmutable` as the default object.

```php
<?php

declare(strict_types=1);

final class Message
{
  public function __construct(
    public readonly ?\DateTimeInterface $deadline = null,
  ) {}
}
```
For nested objects null is mandatory because all data in the protobuf is optional.

### Duration

`google.protobuf.Duration` is only mapped to `\DateInterval` and its inheritors.

```php
<?php

declare(strict_types=1);

final class Message
{
  public function __construct(
    public readonly ?\DateInterval $ttl = null,
  ) {}
}
```

### Struct

Since `google.protobuf.Struct` is a JSON object, we can describe it as an array whose keys are strings and whose values are of any type valid for JSON, but for simplicity we can define the type as `mixed`.

```php
<?php

declare(strict_types=1);

final class Message
{
  /**
   * @param array<string, mixed> $options
   */
  public function __construct(
    public readonly array $options,
  ) {}
}
```

The `iterable<string, mixed>` is also accepted.

However, only `strings`, `booleans`, numbers (`int` and `float`), `nulls`, `lists`, and similar nested `array<string, mixed>` will be allowed for (de)serialization.

### Array Shape

⚠️ The feature is not yet stabilized and therefore has no documentation to keep you from using it if it does fail to be implemented.

### Type Alias

⚠️ The feature is not yet stabilized and therefore has no documentation to keep you from using it if it does fail to be implemented.

### Value Object

⚠️ The feature is not yet stabilized and therefore has no documentation to keep you from using it if it does fail to be implemented.

## Types mapping

| Protobuf Type               | Native Type          | PHPDoc Type            |
|-----------------------------|----------------------|------------------------|
| `fixed32`                   | `int`                | `fixed32`              |
| `fixed64`                   | `int`                | `fixed64`              |
| `sfixed32`                  | `int`                | `sfixed32`             |
| `sfixed64`                  | `int`                | `sfixed64`             |
| `int32`                     | `int`                | `int32`                |
| `uint32`                    | `int`                | `uint32`               |
| `sint32`                    | `int`                | `sint32`               |
| `int64`                     | `int`                | `int64`                |
| `uint64`                    | `int`                | `uint64`               |
| `sint64`                    | `int`                | `sint64`               |
| `string`                    | `string`             | `string`               |
| `bytes`                     | `string`             | `bytes`                |
| `bool`                      | `bool`               | `bool`                 |
| `float`                     | `float`              | `float`                |
| `double`                    | `float`              | `double`               |
| `enum`                      | `enum T: int {}`     | -                      |
| `google.protobuf.Struct`    | `array`              | `array<string, mixed>` |
| `google.protobuf.Timestamp` | `\DateTimeInterface` | -                      |
| `google.protobuf.Duration`  | `\DateInterval`      | -                      |
| `map<K, V>`                 | `array`              | `array<K, V>`          |
| `repeated T`                | `array`              | `list<T>`              |

## Testing

``` bash
$ composer test
```  

## License

The MIT License (MIT). See [License File](../src/Serializer/LICENSE) for more information.
