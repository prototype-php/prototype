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

namespace Prototype\Grpc\Client;

use Amp\Http\Client\DelegateHttpClient;
use Amp\Http\Client\HttpClientBuilder;
use Prototype\Grpc\Client\Internal\Wire\RequestFactory;
use Prototype\Grpc\Client\Internal\Wire\ResponseFactory;
use Prototype\Grpc\Compression\Compressor;
use Prototype\Grpc\Compression\IdentityCompressor;
use Prototype\Serializer\Serializer;

/**
 * @api
 */
final class ClientBuilder
{
    private ?Serializer $serializer = null;

    private ?DelegateHttpClient $httpClient = null;

    private ?Compressor $compressor = null;

    public function withSerializer(Serializer $serializer): self
    {
        $builder = clone $this;
        $builder->serializer = $serializer;

        return $builder;
    }

    public function withHTTPClient(DelegateHttpClient $httpClient): self
    {
        $builder = clone $this;
        $builder->httpClient = $httpClient;

        return $builder;
    }

    public function withCompressor(Compressor $compressor): self
    {
        $builder = clone $this;
        $builder->compressor = $compressor;

        return $builder;
    }

    public static function buildDefault(ClientOptions $options): Client
    {
        return (new self())->build($options);
    }

    public function build(ClientOptions $options): Client
    {
        $serializer = $this->serializer ?: new Serializer();
        $compressor = $this->compressor ?: new IdentityCompressor();

        return new Client(
            $options,
            $this->httpClient ?: HttpClientBuilder::buildDefault(),
            new RequestFactory(
                $serializer,
                $compressor,
            ),
            new ResponseFactory(
                $serializer,
                $compressor,
            ),
        );
    }
}
