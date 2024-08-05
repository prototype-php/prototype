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
use Prototype\Compiler\Locator\RecursiveFilesLocator;
use Prototype\Compiler\Output\FileWriter;

require_once __DIR__.'/../vendor/autoload.php';

$compiler = Compiler::buildDefault();

$compiler->compile(
    new RecursiveFilesLocator(__DIR__.'/proto'),
    new FileWriter(__DIR__.'/build'),
);
```

## License

The MIT License (MIT). See [License File](../src/Compiler/LICENSE) for more information.
