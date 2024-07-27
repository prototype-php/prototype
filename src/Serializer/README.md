## Prototype

A modern strictly typed full-featured library for protobuf serialization without an inheritance.

## Installation

This package can be installed as a [Composer](https://getcomposer.org/) dependency.

```bash
composer require kafkiansky/prototype
```

## Features
- [x] `scalar (any type of numbers and floats, string, bool)`
- [x] `repeated`
- [x] `map`
- [x] `oneof`
- [x] `enum`
- [x] `message (recursive)`
- [x] `google.protobuf.Timestamp`
- [x] `google.protobuf.Duration`
- [x] `google.protobuf.Struct`

## Types mapping

| Protobuf Type               | Native Type          | PHPDoc Type            | Requires typehint? |
|-----------------------------|----------------------|------------------------|--------------------|
| `fixed32`                   | `int`                | `fixed32`              | **Yes**            |
| `fixed64`                   | `int`                | `fixed64`              | **Yes**            |
| `sfixed32`                  | `int`                | `sfixed32`             | **Yes**            |
| `sfixed64`                  | `int`                | `sfixed64`             | **Yes**            |
| `int32`                     | `int`                | `int32`                | **No**             |
| `uint32`                    | `int`                | `uint32`               | **No**             |
| `sint32`                    | `int`                | `sint32`               | **Yes**            |
| `int64`                     | `int`                | `int64`                | **No**             |
| `uint64`                    | `int`                | `uint64`               | **No**             |
| `sint64`                    | `int`                | `sint64`               | **Yes**            |
| `string`                    | `string`             | `string`               | **No**             |
| `bytes`                     | `string`             | `bytes`                | **No**             |
| `bool`                      | `bool`               | `bool`                 | **No**             |
| `float`                     | `float`              | `float`                | **No**             |
| `double`                    | `float`              | `double`               | **Yes**            |
| `enum`                      | `enum T: int {}`     | -                      | **No**             |
| `google.protobuf.Struct`    | `array`              | `array<string, mixed>` | **Yes**            |
| `google.protobuf.Timestamp` | `\DateTimeInterface` | -                      | **No**             |
| `google.protobuf.Duration`  | `\DateInterval`      | -                      | **No**             |
| `map<K, V>`                 | `array`              | `array<K, V>`          | **Yes**            |
| `repeated T`                | `array`              | `list<T>`              | **Yes**            |

## Testing

``` bash
$ composer test
```  

## License

The MIT License (MIT). See [License File](LICENSE) for more information.
