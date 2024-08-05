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

namespace Prototype\Compiler;

use Antlr\Antlr4\Runtime\CommonTokenStream;
use Antlr\Antlr4\Runtime\Error\Listeners\DiagnosticErrorListener;
use Antlr\Antlr4\Runtime\InputStream;
use Nette\PhpGenerator\PsrPrinter;
use Prototype\Compiler\Exception;
use Prototype\Compiler\Internal\Code\Generator;
use Prototype\Compiler\Internal\Parser\Context\ProtoContext;
use Prototype\Compiler\Internal\Parser\Protobuf3Lexer;
use Prototype\Compiler\Internal\Parser\Protobuf3Parser;
use Prototype\Compiler\Internal\Proto\Schema;
use Prototype\Compiler\Internal\Proto\ToSchemaConverter;
use Prototype\Compiler\Locator\FilesLocator;
use Prototype\Compiler\Output;

/**
 * @api
 */
final class Compiler
{
    private function __construct(
        private readonly Generator $generator = new Generator(
            new PsrPrinter(),
        ),
    ) {}

    public static function buildDefault(): self
    {
        return new self();
    }

    /**
     * @throws CompilerException
     */
    public function compile(
        FilesLocator $locator,
        Output\Writer $writer,
        CompileOptions $options = new CompileOptions(),
    ): void {
        foreach ($locator->files() as $file) {
            /** @var Schema $schema */
            $schema = self::parse($file->stream)->accept( // @phpstan-ignore-line
                new ToSchemaConverter(),
            );

            $this->generator->generateFile(
                $schema,
                ($schema->phpNamespace() ?: $options->phpNamespace) ?: throw Exception\NamespaceIsNotDefined::forSchema(
                    (string)$file->stream,
                ),
                $writer,
            );
        }
    }

    private static function parse(InputStream $stream): ProtoContext // @phpstan-ignore-line
    {
        $parser = new Protobuf3Parser(
            new CommonTokenStream(
                new Protobuf3Lexer($stream),
            ),
        );

        $parser->addErrorListener(new DiagnosticErrorListener());
        $parser->setBuildParseTree(true);

        return $parser->proto();
    }
}
