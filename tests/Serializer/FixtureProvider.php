<?php

declare(strict_types=1);

namespace Prototype\Tests\Serializer;

use Prototype\Tests\Serializer\Fixtures\ProtobufMessage;

final class FixtureProvider
{
    /**
     * @var ?array<string, array{non-empty-string, object}>
     */
    private static ?array $classes = null;

    /**
     * @return array<string, array{non-empty-string, object}>
     */
    public static function messages(): array
    {
        return self::$classes ??= self::loadFromFile(__DIR__ . '/Fixtures/messages.php');
    }

    /**
     * @param non-empty-string $file
     * @return array<string, array{non-empty-string, object}>
     */
    private static function loadFromFile(string $file): array
    {
        $messages = [];

        $declaredClasses = get_declared_classes();

        /** @psalm-suppress UnresolvableInclude */
        require_once $file;

        foreach (array_diff(get_declared_classes(), $declaredClasses) as $class) {
            $reflectionClass = new \ReflectionClass($class);

            /** @psalm-suppress UndefinedClass */
            $attributes = $reflectionClass->getAttributes(ProtobufMessage::class);

            foreach ($attributes as $attribute) {
                $classAttribute = $attribute->newInstance();

                /** @psalm-suppress RedundantCast */
                $messages[(string)$classAttribute->path] = [
                    $classAttribute->path,
                    [$class, $classAttribute->constructorFunction](),
                ];
            }
        }

        /** @var array<string, array{non-empty-string, object}> */
        return $messages;
    }
}
