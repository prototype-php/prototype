# GRPC implementation in async PHP

## Installation

This package can be installed as a [Composer](https://getcomposer.org/) dependency.

```bash
composer require prototype/grpc
```

## Contents:
- [Client](#client)
- [Server](#server)
- [Timeouts](#timeouts)

## Client

An example of compiled client from compiler [docs](compiler.md):
```php
<?php

declare(strict_types=1);

use Scheduler\Api\V1\Request;
use Scheduler\Api\V1\TaskControllerClient;
use Prototype\Grpc\Client\ClientBuilder;
use Prototype\Grpc\Client\ClientOptions;

require_once __DIR__.'/../vendor/autoload.php';

$client = new TaskControllerClient(
    ClientBuilder::buildDefault(
        new ClientOptions('http://localhost:5000'),
    ),
);

var_dump($client->launch(new Request('test', 1)));
```

## Server

An example of compiled server from compiler [docs](compiler.md):
```php
<?php

declare(strict_types=1);

use Amp\Cancellation;
use Amp\NullCancellation;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Prototype\Grpc\Compression\GZIPCompressor;
use Prototype\Grpc\Server\ServerBuilder;
use Scheduler\Api\V1\TaskControllerServer;
use Scheduler\Api\V1\Request;
use Scheduler\Api\V1\Response;
use Scheduler\Api\V1\ResponseType;
use Scheduler\Api\V1\TaskControllerServerRegistrar;
use function Amp\trapSignal;

require_once __DIR__.'/../vendor/autoload.php';

/**
 * @api
 */
final class DefaultTaskControllerServer extends TaskControllerServer
{
    public function launch(Request $request, Cancellation $cancellation = new NullCancellation()): Response
    {
        var_dump($request->name);

        return new Response(ResponseType::OK, $request->id ?: 0);
    }
}

$server = (new ServerBuilder())
    ->withLogger(new Logger('grpc', [new StreamHandler(\STDOUT)]))
    ->withCompressor(new GZIPCompressor())
    ->registerFromService(
        new TaskControllerServerRegistrar(new DefaultTaskControllerServer()),
    )
    ->build()
;

$server->serve();

$signal = trapSignal([\SIGHUP, \SIGINT, \SIGQUIT, \SIGTERM]);
$server->shutdown();
```

## Timeouts

You can configure client timeouts using interceptors:
```php
<?php

declare(strict_types=1);

use Prototype\Grpc\Interceptor\AddGrpcTimeout;
use Prototype\Grpc\Timeout;
use Revolt\EventLoop;
use Scheduler\Api\V1\Request;
use Scheduler\Api\V1\TaskControllerClient;
use Prototype\Grpc\Client\ClientBuilder;
use Prototype\Grpc\Client\ClientOptions;

require_once __DIR__.'/../vendor/autoload.php';

$client = new TaskControllerClient(
    (new ClientBuilder())
        ->withInterceptor(new AddGrpcTimeout(Timeout::milliseconds(500)))
        ->build(
            new ClientOptions('http://localhost:5001'),
        ),
);

var_dump($client->launch(new Request('recurrent')));
```

The generated server will also take the `grpc-timeout` header into account, creating a `TimeoutCancellation` that is passed to your handler along with the request.
You should use it in any `io` operations so that the server has a chance to cancel a long request.

However, you can configure your own timeout for requests, which will be used if the client has not passed its own value for timeout.

```php
<?php

declare(strict_types=1);

use Prototype\Grpc\Server\ServerBuilder;
use Prototype\Grpc\Timeout;
use Scheduler\Api\V1\TaskControllerServerRegistrar;

require_once __DIR__.'/../vendor/autoload.php';

$server = (new ServerBuilder())
    ->withRequestTimeout(Timeout::seconds(2))
    ->registerFromService(
        new TaskControllerServerRegistrar(...),
    )
    ->build()
;

$server->serve();
```

## License

The MIT License (MIT). See [License File](../src/Grpc/LICENSE) for more information.
