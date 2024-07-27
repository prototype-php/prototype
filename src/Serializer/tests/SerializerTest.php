<?php

declare(strict_types=1);

namespace Kafkiansky\Prototype\Tests;

use Kafkiansky\Binary\Buffer;
use Kafkiansky\Binary\Endianness;
use Kafkiansky\Prototype\Serializer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

#[CoversClass(Serializer::class)]
final class SerializerTest extends TestCase
{
    #[DataProviderExternal(FixtureProvider::class, 'messages')]
    public function testDeserialize(string $resourcePath, object $message): void
    {
        /** @var false|non-empty-string $bin */
        $bin = file_get_contents(__DIR__.'/Fixtures/'.$resourcePath);
        self::assertNotFalse($bin);

        $buffer = Buffer::fromString(
            $bin,
            Endianness::little(),
        );

        $serializer = new Serializer();
        self::assertEquals($message, $serializer->deserialize($buffer, $message::class));
        self::assertTrue($buffer->isEmpty());
    }

    #[DataProviderExternal(FixtureProvider::class, 'messages')]
    public function testSerialize(string $_, object $message): void
    {
        $serializer = new Serializer();
        $buffer = $serializer->serialize($message);
        self::assertEquals($message, $serializer->deserialize($buffer, $message::class));
        self::assertTrue($buffer->isEmpty());
    }
}
