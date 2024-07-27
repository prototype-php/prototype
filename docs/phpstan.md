# PHPStan extensions and rules for protobuf

With this extension you can use protobuf types everywhere in `@var`, `@param`, `@return` phpdocs including lists, arrays and array shapes.

The following example will produce no errors:

```php
final class Request
{
    /**
      * @param array{id: int32} $info
      */
    public function __construct(
        public readonly array $info,
    ) {}
}

$request = new Request(['id' => 1]);
```

But if you change `id` to a negative number, you get an error from PHPStan:
```shell
 ------ --------------------------------------------------------------------------------------------------------------- 
  Line   ...                                                                                               
 ------ --------------------------------------------------------------------------------------------------------------- 
  14     Parameter #1 $info of class Request constructor expects array{id: int<0, 4294967295>}, array{id: -1} given.  
         🪪  argument.type                                                                                              
         💡 Offset 'id' (int<0, 4294967295>) does not accept type -1. 
```

## Type mapping:
| PHPDoc Type |          PHPStan Type          |
|:-----------:|:------------------------------:|
|   `int32`   |      `int<0, 4294967295>`      |
|  `uint32`   |      `int<0, 4294967295>`      |
|  `fixed32`  |      `int<0, 4294967295>`      |
|  `sint32`   | `int<-2147483648, 2147483647>` |
| `sfixed32`  | `int<-2147483648, 2147483647>` |
|   `int64`   |         `int<0, max>`          |
|  `uint64`   |         `int<0, max>`          |
|  `fixed64`  |         `int<0, max>`          |
|  `sint64`   |             `int`              |
| `sfixed64`  |             `int`              |
|   `bytes`   |            `string`            |

## Installation

To use this extension, require it in [Composer](https://getcomposer.org/):

```
composer require --dev prototype/phpstan-extension
```

If you also install [phpstan/extension-installer](https://github.com/phpstan/extension-installer) then you're all set!

<details>
  <summary>Manual installation</summary>

If you don't want to use `phpstan/extension-installer`, include extension.neon in your project's PHPStan config:

```
includes:
    - vendor/prototype/phpstan-extension/extension.neon
```
</details>