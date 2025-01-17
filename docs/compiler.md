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

Consider a simple example:
```protobuf
syntax = "proto3";

package scheduler.api.v1;

option go_package = "api/v1/scheduler;scheduler";
option php_namespace = "Scheduler\\Api\\V1";

message Request {
 string name = 1;
 fixed32 id = 2;
}

message Response {
 enum Type {
  UNSPECIFIED = 0;
  OK = 1;
 }

 Type type = 1;
 fixed32 id = 2;
}

service TaskController {
 rpc Launch(Request) returns (Response) {}
}
```

The prototype compiler will generate the following PHP code:

1. `Scheduler/Api/V1/Request.php`:
```php
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace Scheduler\Api\V1;

/**
 * @api
 */
final class Request
{
    /**
     * @param fixed32 $id
     */
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?int $id = null,
    ) {
    }
}
```

2. `Scheduler/Api/V1/Response.php`:
```php
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace Scheduler\Api\V1;

/**
 * @api
 */
final class Response
{
    /**
     * @param fixed32 $id
     */
    public function __construct(
        public readonly ?ResponseType $type = null,
        public readonly ?int $id = null,
    ) {
    }
}
```

3. `Scheduler/Api/V1/ResponseType.php`:
```php
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace Scheduler\Api\V1;

/**
 * @api
 */
enum ResponseType: int
{
    case UNSPECIFIED = 0;
    case OK = 1;
}
```

4. `Scheduler/Api/V1/TaskControllerClient.php`:
```php
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace Scheduler\Api\V1;

use Amp\Cancellation;
use Amp\NullCancellation;
use Prototype\Grpc\Client\Client;
use Prototype\Grpc\Client\GrpcRequest;
use Prototype\Grpc\Client\RequestException;
use Prototype\Grpc\StatusCode;

/**
 * @api
 */
final class TaskControllerClient
{
    public function __construct(
        private readonly Client $client,
    ) {
    }

    /**
     * @throws RequestException
     */
    public function launch(Request $request, Cancellation $cancellation = new NullCancellation()): Response
    {
        $response = $this->client->invoke(
            new GrpcRequest('/scheduler.api.v1.TaskController/Launch', $request, Response::class),
            $cancellation,
        );

        if (StatusCode::OK !== $response->statusCode) {
            throw new RequestException($response->statusCode, $response->grpcMessage);
        }

        return $response->message;
    }
}
```

5. `Scheduler/Api/V1/TaskControllerDefaultServer.php`:
```php
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace Scheduler\Api\V1;

use Amp\Cancellation;
use Amp\NullCancellation;
use Prototype\Grpc\Server\MethodNotImplemented;

/**
 * @api
 */
abstract class TaskControllerServer
{
    public function launch(Request $request, Cancellation $cancellation = new NullCancellation()): Response
    {
        throw new MethodNotImplemented('/scheduler.api.v1.TaskController/Launch');
    }
}
```

6. `Scheduler/Api/V1/TaskControllerServerServiceRegistrar.php`:
```php
<?php

/**
 * This code was auto-generated by the prototype/compiler@dev.
 * Do not edit this file manually.
 */

declare(strict_types=1);

namespace Scheduler\Api\V1;

use Prototype\Grpc\Server\RpcMethod;
use Prototype\Grpc\Server\ServiceDescriptor;
use Prototype\Grpc\Server\ServiceRegistrar;
use Prototype\Grpc\Server\ServiceRegistry;

/**
 * @api
 */
final class TaskControllerServerRegistrar implements ServiceRegistrar
{
    public function __construct(
        private readonly TaskControllerServer $server,
    ) {
    }

    public function register(ServiceRegistry $registry): ServiceRegistry
    {
        return $registry->addService(
            new ServiceDescriptor(
                'scheduler.api.v1.TaskController',
                [
                    new RpcMethod('Launch', RpcMethod::createHandler($this->server->launch(...), Request::class)),
                ],
            ),
        );
    }
}
```

## License

The MIT License (MIT). See [License File](../src/Compiler/LICENSE) for more information.
