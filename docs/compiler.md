# Proto Compiler

## Installation

This package can be installed as a [Composer](https://getcomposer.org/) dependency.

```bash
composer require prototype/compiler
```

## Usage

```php
<?php

declare(strict_types=1);

use Prototype\Compiler\Compiler;
use Prototype\Compiler\Locator\ProtoFile;
use Prototype\Compiler\Output\FileWriter;

require_once __DIR__.'/../vendor/autoload.php';

$compiler = Compiler::build(new FileWriter(__DIR__.'/build'));

$compiler->compile(
    ProtoFile::fromPath(__DIR__.'/path/to/proto/file'),
);
```

## License

The MIT License (MIT). See [License File](../src/Compiler/LICENSE) for more information.
