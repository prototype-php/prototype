# Proto Compiler

## Installation

This package can be installed as a [Composer](https://getcomposer.org/) dependency.

```bash
composer require prototype/compiler
```

## Manual usage

```php
<?php

declare(strict_types=1);

use Prototype\Compiler\Compiler;
use Prototype\Compiler\Output\FileWriter;
use Prototype\Compiler\ProtoFile;

require_once __DIR__.'/../vendor/autoload.php';

$compiler = Compiler::build(new FileWriter(__DIR__.'/build'));

$compiler->compile(
    ProtoFile::fromPath(__DIR__.'/path/to/proto/file'),
);
```

## Cli usage

```shell
composer prototype compile path/to/proto/files -o path/to/generated/files -i path/to/import/files
```

## License

The MIT License (MIT). See [License File](../src/Compiler/LICENSE) for more information.
