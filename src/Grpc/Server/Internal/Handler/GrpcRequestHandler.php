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

namespace Prototype\Grpc\Server\Internal\Handler;

use Amp\DeferredFuture;
use Amp\Http\HttpStatus;
use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Server\Trailers;
use Kafkiansky\Binary\Buffer as RequestBuffer;
use Kafkiansky\Binary\Endianness;
use Prototype\Byte\Buffer as BodyBuffer;
use Prototype\Grpc\Compression\Compressor;
use Prototype\Grpc\Compression\IdentityCompressor;
use Prototype\Grpc\Server\Internal\Cancellation\CancellationFactory;
use Prototype\Grpc\Server\RpcMethod;
use Prototype\Grpc\StatusCode;
use Prototype\Serializer\Serializer;

/**
 * @internal
 * @psalm-internal Prototype\Grpc\Server
 */
final class GrpcRequestHandler implements RequestHandler
{
    private readonly RequestBuffer $requestResponseBuffer;

    private readonly BodyBuffer $protobuf;

    /**
     * @param array<non-empty-string, array<non-empty-string, RpcMethod>> $services
     * @param array<non-empty-string, Compressor> $compressors
     */
    public function __construct(
        private readonly Serializer $serializer,
        private readonly CancellationFactory $cancellations,
        private readonly array $services,
        private readonly array $compressors,
    ) {
        $this->requestResponseBuffer = RequestBuffer::empty(Endianness::network());
        $this->protobuf = BodyBuffer::default();
    }

    public function handleRequest(Request $request): Response
    {
        $cancellation = $this->cancellations->createCancellation();

        /** @psalm-var DeferredFuture<array<non-empty-string, non-empty-string>> $trailers */
        $trailers = new DeferredFuture();

        $response = new Response(HttpStatus::OK, ['Content-Type' => 'application/grpc', 'TE' => 'trailers']);
        $response->setTrailers(new Trailers($trailers->getFuture()));

        /** @var array{0?: non-empty-string, 1?: non-empty-string} $parts */
        $parts = explode('/', ltrim($request->getUri()->getPath(), '/'));

        if (\count($parts) < 2) {
            $trailers->complete(StatusCode::INVALID_ARGUMENT->asHeaders());

            return $response;
        }

        [$serviceName, $rpcName] = $parts;

        $rpc = $this->services[$serviceName][$rpcName] ?? null;

        if (null === $rpc) {
            $trailers->complete(StatusCode::UNIMPLEMENTED->asHeaders());

            return $response;
        }

        $requestBody = $request
            ->getBody()
            ->buffer($cancellation)
        ;

        if ('' === $requestBody) {
            $trailers->complete(StatusCode::INVALID_ARGUMENT->asHeaders());

            return $response;
        }

        $requestBuffer = $this
            ->requestResponseBuffer
            ->clone()
            ->write($requestBody)
        ;

        [$compressed, $body, $compressor] = [
            $requestBuffer->consumeInt8(),
            $requestBuffer->consume($requestBuffer->consumeUint32()),
            new IdentityCompressor(),
        ];

        if ($compressed === 1) {
            $compressorName = $request->getHeader('grpc-encoding');

            if (null === $compressorName || !isset($this->compressors[$compressorName])) {
                $trailers->complete(StatusCode::UNIMPLEMENTED->asHeaders());

                return $response;
            }

            $compressor = $this->compressors[$compressorName];
        }

        if ('' !== $body) {
            $body = $compressor->decompress($body);
        }

        $out = ($rpc->handler)(
            fn (string $requestType): object => $this->serializer->deserialize(
                $this->protobuf->clone()->write($body),
                $requestType,
            ),
            $cancellation,
        );

        $responseBody = $this
            ->serializer
            ->serialize($out)
            ->reset()
        ;

        if ('' !== $responseBody) {
            $responseBody = $compressor->compress($responseBody);
        }

        $response->setBody(
            $this->requestResponseBuffer
                ->writeInt8((int)(!($compressor instanceof IdentityCompressor)))
                ->writeUint32(\strlen($responseBody))
                ->write($responseBody)
                ->reset(),
        );

        $response->addHeader('grpc-encoding', $compressor->name());
        $trailers->complete(StatusCode::OK->asHeaders());

        return $response;
    }
}
