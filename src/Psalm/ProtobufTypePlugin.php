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

namespace Prototype\Psalm;

use Psalm\Plugin\EventHandler\AfterClassLikeVisitInterface;
use Psalm\Plugin\EventHandler\AfterExpressionAnalysisInterface;
use Psalm\Plugin\EventHandler\AfterFunctionLikeAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterClassLikeVisitEvent;
use Psalm\Plugin\EventHandler\Event\AfterExpressionAnalysisEvent;
use Psalm\Plugin\EventHandler\Event\AfterFunctionLikeAnalysisEvent;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;
use Psalm\Type;

/**
 * @api
 */
final class ProtobufTypePlugin implements
    PluginEntryPointInterface,
    AfterClassLikeVisitInterface,
    AfterExpressionAnalysisInterface,
    AfterFunctionLikeAnalysisInterface
{
    public function __invoke(RegistrationInterface $registration, ?\SimpleXMLElement $config = null): void
    {
        $registration->registerHooksFromClass(self::class);
    }

    public static function afterExpressionAnalysis(AfterExpressionAnalysisEvent $event): ?bool
    {
        self::fixTypes($event);

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public static function afterClassLikeVisit(AfterClassLikeVisitEvent $event): void
    {
        self::fixTypes($event);
    }

    public static function afterStatementAnalysis(AfterFunctionLikeAnalysisEvent $event): ?bool
    {
        self::fixTypes($event);

        return null;
    }

    private static function fixTypes(AfterClassLikeVisitEvent|AfterExpressionAnalysisEvent|AfterFunctionLikeAnalysisEvent $event): void
    {
        $types = iterator_to_array(self::types());

        $fixType =
            /**
             * @psalm-return ($type is null ? null|Type\Union : Type\Union)
             */
            static fn (?Type\Union $type = null): ?Type\Union => self::fixType($types, $type);

        if ($event instanceof AfterClassLikeVisitEvent) {
            foreach ($event->getStorage()->methods as $method) {
                /** @psalm-suppress InvalidArgument */
                $method->return_type = $fixType($method->return_type);

                foreach ($method->params as $param) {
                    /** @psalm-suppress InvalidArgument */
                    $param->type = $fixType($param->type);
                }
            }

            foreach ($event->getStorage()->properties as $property) {
                /** @psalm-suppress InvalidArgument */
                $property->type = $fixType($property->type);
            }
        }

        if ($event instanceof AfterExpressionAnalysisEvent) {
            foreach ($event->getContext()->vars_in_scope as $varName => $varType) {
                $event->getContext()->vars_in_scope[$varName] = $fixType($varType); /** @phpstan-ignore-line */
            }

            $event
                ->getStatementsSource()
                ->addSuppressedIssues(['UndefinedDocblockClass'])
            ;
        }
    }

    /**
     * @param array<non-empty-string, Type\Atomic> $prototypes
     * @psalm-return ($union is null ? null|Type\Union : Type\Union)
     */
    private static function fixType(array $prototypes, ?Type\Union $union = null): ?Type\Union
    {
        if (null !== $union) {
            if ($union->hasArray()) {
                $array = $union->getArray();

                if ($array instanceof Type\Atomic\TKeyedArray) {
                    $arrayProperties = $array->properties;

                    foreach ($array->properties as $idx => $type) {
                        $arrayProperties[$idx] = self::fixType($prototypes, $type);
                    }

                    /** @psalm-suppress DocblockTypeContradiction, RedundantConditionGivenDocblockType */
                    $union = new Type\Union(['array' => new Type\Atomic\TKeyedArray(
                        $arrayProperties,
                        $array->class_strings,
                        null === $array->fallback_params ? null : [
                            self::fixType($prototypes, $array->fallback_params[0]),
                            self::fixType($prototypes, $array->fallback_params[1]),
                        ],
                        $array->is_list,
                        $array->from_docblock,
                    )]);
                } else if ($array instanceof Type\Atomic\TArray) {
                    $union = new Type\Union(['array' => new Type\Atomic\TArray([
                        self::fixType($prototypes, $array->type_params[0]),
                        self::fixType($prototypes, $array->type_params[1]),
                    ])]);
                }
            }

            $atomicTypes = $union->getAtomicTypes();

            foreach ($atomicTypes as $typeId => $type) {
                $nonNamespacedTypeId = self::typeWithoutNamespace($typeId);

                if (isset($prototypes[$nonNamespacedTypeId])) {
                    $type = $prototypes[$nonNamespacedTypeId];
                }

                $atomicTypes[$typeId] = $type;
            }

            $union = $union->setTypes($atomicTypes);
        }

        return $union;
    }

    /**
     * @return non-empty-string
     */
    private static function typeWithoutNamespace(string $typeId): string
    {
        /** @psalm-suppress RiskyTruthyFalsyComparison */
        $pos = strrpos($typeId, '\\') ?: -1;

        /** @var non-empty-string */
        return substr($typeId, $pos + 1);
    }

    /**
     * @return \Traversable<non-empty-string, Type\Atomic>
     */
    private static function types(): \Traversable
    {
        foreach (['int32', 'uint32', 'fixed32'] as $type) {
            yield $type => new Type\Atomic\TIntRange(0, 4294967295);
        }

        foreach (['sint32', 'sfixed32'] as $type) {
            yield $type => new Type\Atomic\TIntRange(-2147483648, 2147483647);
        }

        foreach (['int64', 'uint64', 'fixed64'] as $type) {
            yield $type => new Type\Atomic\TIntRange(0, null);
        }

        foreach (['sint64', 'sfixed64'] as $type) {
            yield $type => new Type\Atomic\TInt();
        }

        yield 'bytes' => new Type\Atomic\TString();
    }
}
