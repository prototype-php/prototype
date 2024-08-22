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

namespace Prototype\Compiler\Internal\Code;

use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\Parameter;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PromotedParameter;
use Prototype\Compiler\Internal\Code\Type\CombinedTypeVisitor;
use Prototype\Compiler\Internal\Code\Type\ResolveRpcTypeVisitor;
use Prototype\Compiler\Internal\Ir;
use Prototype\Compiler\Internal\Ir\Field;
use Prototype\Compiler\Internal\Naming;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 */
final class DefinitionGenerator
{
    public function __construct(
        private readonly PhpNamespace $namespace,
        private readonly Ir\Proto $proto,
        private readonly ImportStorage $imports,
    ) {}

    /**
     * @return non-empty-string
     */
    public function generateClient(Ir\Service $service): string
    {
        $client = $this
            ->namespace
            ->addUse('Amp\Cancellation')
            ->addUse('Amp\NullCancellation')
            ->addUse('Prototype\Grpc\Client\GrpcRequest')
            ->addUse('Prototype\Grpc\Client\Client')
            ->addUse('Prototype\Grpc\Client\RequestException')
            ->addUse('Prototype\Grpc\StatusCode')
            ->addClass($clientName = \sprintf('%sClient', Naming\ClassLike::name($service->name)))
            ->setFinal()
            ->addComment('@api')
        ;

        $constructor = $client
            ->addMethod('__construct')
        ;

        $constructor->setParameters([
            (new PromotedParameter('client'))
                ->setType('Client')
                ->setPrivate()
                ->setReadOnly(),
        ]);

        $rpcTypeVisitor = new CombinedTypeVisitor(
            new Type\ApplyTypeVisitor(new Type\WellKnownTypeVisitor()),
            new Type\ApplyTypeVisitor(new ResolveRpcTypeVisitor($this->proto)),
            new Type\ApplyTypeVisitor(
                new Type\ResolveImportReferenceTypeVisitor($this->proto, $this->imports),
            ),
        );

        foreach ($service->rpc as $rpc) {
            $method = $client
                ->addMethod(Naming\SnakeCase::toCamelCase($rpc->name))
            ;

            $phpParameterType = Ir\TypeIdent::message($rpc->inType->name)->accept($rpcTypeVisitor);

            foreach ($phpParameterType->uses as $use) {
                $this->namespace->addUse($use);
            }

            $method
                ->addComment('@throws RequestException')
                ->setParameters([
                    (new Parameter('request'))
                        ->setType($phpParameterType->nativeType),
                    (new Parameter('cancellation'))
                        ->setType('Cancellation')
                        ->setDefaultValue(Literal::new('NullCancellation')),
                ])
            ;

            $phpReturnType = Ir\TypeIdent::message($rpc->outType->name)->accept($rpcTypeVisitor);

            foreach ($phpReturnType->uses as $use) {
                $this->namespace->addUse($use);
            }

            $method
                ->setReturnType($phpReturnType->nativeType)
            ;

            $method
                ->addBody(
                    <<<'PHP'
$response = $this->client->invoke(
    new GrpcRequest(?, ?, ?),
    ?,
);

if (StatusCode::OK !== $response->statusCode) {
    throw new RequestException($response->statusCode, $response->grpcMessage);
}

return $response->message;
PHP,
                    [
                        \sprintf('/%s.%s/%s', $service->packageName, $service->name, $rpc->name),
                        new Literal('$request'),
                        new Literal(\sprintf('%s::class', Naming\ClassLike::name($phpReturnType->nativeType))),
                        new Literal('$cancellation'),
                    ],
                )
            ;
        }

        return $clientName;
    }

    /**
     * @return non-empty-string
     */
    public function generateServerStub(Ir\Service $service): string
    {
        $server = $this
            ->namespace
            ->addUse('Amp\Cancellation')
            ->addUse('Amp\NullCancellation')
            ->addUse('Prototype\Grpc\Server\MethodNotImplemented')
            ->addClass($serverName = \sprintf('%sServer', Naming\ClassLike::name($service->name)))
            ->setAbstract()
            ->addComment('@api')
        ;

        $rpcTypeVisitor = new CombinedTypeVisitor(
            new Type\ApplyTypeVisitor(new Type\WellKnownTypeVisitor()),
            new Type\ApplyTypeVisitor(new ResolveRpcTypeVisitor($this->proto)),
            new Type\ApplyTypeVisitor(
                new Type\ResolveImportReferenceTypeVisitor($this->proto, $this->imports),
            ),
        );

        foreach ($service->rpc as $rpc) {
            $method = $server
                ->addMethod(Naming\SnakeCase::toCamelCase($rpc->name))
                ->setPublic()
            ;

            $phpParameterType = Ir\TypeIdent::message($rpc->inType->name)->accept($rpcTypeVisitor);

            foreach ($phpParameterType->uses as $use) {
                $this->namespace->addUse($use);
            }

            $method
                ->setParameters([
                    (new Parameter('request'))
                        ->setType($phpParameterType->nativeType),
                    (new Parameter('cancellation'))
                        ->setType('Cancellation')
                        ->setDefaultValue(Literal::new('NullCancellation')),
                ])
            ;

            $phpReturnType = Ir\TypeIdent::message($rpc->outType->name)->accept($rpcTypeVisitor);

            foreach ($phpReturnType->uses as $use) {
                $this->namespace->addUse($use);
            }

            $method
                ->setReturnType($phpReturnType->nativeType)
            ;

            $method
                ->addBody(
                    <<<'PHP'
throw new MethodNotImplemented(?);
PHP,
                    [
                        \sprintf('/%s.%s/%s', $service->packageName, $service->name, $rpc->name),
                    ],
                )
            ;
        }

        return $serverName;
    }

    /**
     * @return non-empty-string
     */
    public function generateServiceRegistrar(Ir\Service $service): string
    {
        $registry = $this
            ->namespace
            ->addUse('Prototype\Grpc\Server\ServiceRegistrar')
            ->addUse('Prototype\Grpc\Server\ServiceRegistry')
            ->addUse('Prototype\Grpc\Server\ServiceDescriptor')
            ->addUse('Prototype\Grpc\Server\RpcMethod')
            ->addClass($registryName = \sprintf('%sServerRegistrar', Naming\ClassLike::name($service->name)))
            ->addImplement('ServiceRegistrar')
            ->setFinal()
            ->addComment('@api')
        ;

        $registry
            ->addMethod('__construct')
            ->setParameters([
                (new PromotedParameter('server'))
                    ->setReadOnly()
                    ->setPrivate()
                    ->setType(\sprintf('%sServer', Naming\ClassLike::name($service->name))),
            ])
        ;

        $rpcTypeVisitor = new CombinedTypeVisitor(
            new Type\ApplyTypeVisitor(new Type\WellKnownTypeVisitor()),
            new Type\ApplyTypeVisitor(new ResolveRpcTypeVisitor($this->proto)),
            new Type\ApplyTypeVisitor(
                new Type\ResolveImportReferenceTypeVisitor($this->proto, $this->imports),
            ),
        );

        $registry
            ->addMethod('register')
            ->setReturnType('ServiceRegistry')
            ->setParameters([
                (new Parameter('registry'))
                    ->setType('ServiceRegistry'),
            ])
            ->setComment('{@inheritdoc}')
            ->setBody(
                \sprintf(
                    <<<'PHP'
return $registry->addService(
    new ServiceDescriptor(
        ?,
        [
            %s,
        ],
    ),
);
PHP,
                    implode(",\n\t\t\t", array_fill(0, \count($service->rpc), '?')),
                ),
                [
                    \sprintf('%s.%s', $service->packageName, $service->name),
                    ...array_map(
                        fn (Ir\Rpc $rpc): Literal => new Literal(
                            <<<'PHP'
new RpcMethod(?, ?)
PHP,
                            [
                                $rpc->name,
                                new Literal(
                                    <<<'PHP'
RpcMethod::createHandler($this->server->?(...), ?::class)
PHP,
                                    [
                                        Naming\SnakeCase::toCamelCase($rpc->name),
                                        (function () use ($rpc, $rpcTypeVisitor): Literal {
                                            $type = Ir\TypeIdent::message($rpc->inType->name)->accept($rpcTypeVisitor);

                                            foreach ($type->uses as $use) {
                                                $this->namespace->addUse($use);
                                            }

                                            return new Literal($type->nativeType);
                                        })(),
                                    ],
                                ),
                            ],
                        ),
                        $service->rpc,
                    ),
                ],
            )
        ;

        return $registryName;
    }

    /**
     * @return non-empty-string
     */
    public function generateEnum(Ir\Enum $enum): string
    {
        $phpEnum = $this
            ->namespace
            ->addEnum($enumName = Naming\ClassLike::name($enum->name))
            ->addComment('@api')
            ->setType('int')
        ;

        foreach ($enum as $enumCase) {
            $phpEnum->addCase(
                Naming\EnumLike::case($enumCase->name),
                $enumCase->value,
            );
        }

        return $enumName;
    }

    /**
     * @return non-empty-string
     */
    public function generateClass(Ir\Message $message): string
    {
        $class = $this
            ->namespace
            ->addClass($className = Naming\ClassLike::name($message->name))
            ->setFinal()
            ->addComment('@api')
        ;

        $numberExplicitly = self::shouldAddExplicitNumbering($message->fields);

        if ($numberExplicitly) {
            $this->namespace->addUse('Prototype\Serializer\Field');
        }

        $method = $class
            ->addMethod('__construct')
        ;

        /** @var PromotedParameter[] $parameters */
        $parameters = [];

        foreach ($message as $field) {
            /** @var PhpType $type */
            $type = $field->type->accept(
                new Type\CombinedTypeVisitor(
                    new Type\ApplyTypeVisitor(new Type\ScalarTypeVisitor()),
                    new Type\ApplyTypeVisitor(new Type\WellKnownTypeVisitor()),
                    new Type\ApplyTypeVisitor(
                        new Type\ResolveLocalReferenceTypeVisitor($message->typeStorage(), $this->proto),
                    ),
                    new Type\ApplyTypeVisitor(
                        new Type\ResolveImportReferenceTypeVisitor($this->proto, $this->imports),
                    ),
                ),
            );

            foreach ($type->uses as $use) {
                $this->namespace->addUse($use);
            }

            $fieldName = Naming\SnakeCase::toCamelCase($field->name);

            $parameter = (new PromotedParameter($fieldName))
                ->setReadOnly()
                ->setType($type->nativeType)
                ->setNullable($type->nullable)
                ->setDefaultValue($type->default)
            ;

            if ($numberExplicitly) {
                $parameter->addAttribute('Field', [$field->number]);
            }

            $parameters[] = $parameter;

            if ('' !== ($phpDoc = $type->toPhpDoc($fieldName))) {
                $method->addComment($phpDoc);
            }
        }

        $method->setParameters($parameters);

        return $className;
    }

    /**
     * @param Field[] $fields
     */
    private static function shouldAddExplicitNumbering(array $fields): bool
    {
        // Specifies whether to add an explicit number indication to the fields.
        // Add field attribute when numbers are not monotonically increasing or have a gap greater than one.
        for ($i = 0; $i < \count($fields) - 1; ++$i) {
            if ($fields[$i]->number > $fields[$i+1]->number || ($fields[$i+1]->number - $fields[$i]->number) !== 1) {
                return true;
            }
        }

        // In the simplest case, when numbering does not start from one, we also add a number attribute to all fields.
        return ($fields[0]->number ?? 1) !== 1;
    }
}
