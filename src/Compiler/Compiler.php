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

use Nette\PhpGenerator\Printer;
use Nette\PhpGenerator\PsrPrinter;
use Prototype\Compiler\Internal\Code;
use Prototype\Compiler\Internal\Ir;
use Prototype\Compiler\Internal\Ir\Hook\Hooks;
use Prototype\Compiler\Internal\Ir\Validate;

/**
 * @api
 */
final class Compiler
{
    private function __construct(
        private readonly Code\Generator $generator,
        private readonly Output\Writer $writer,
        private readonly Printer $printer,
        private readonly Ir\ProtoResolver $protoResolver,
    ) {}

    public static function build(
        Output\Writer $writer = new Output\StdOutWriter(),
        ?Import\ImportResolver $imports = null,
    ): self {
        return new self(
            new Code\Generator(
                new Code\PhpFileFactory(CompilerVersion::pretty()),
            ),
            $writer,
            (new PsrPrinter())->setTypeResolving(false),
            Ir\ProtoResolver::build(
                $imports ?: new Import\CombineImportResolver([
                    Import\VirtualImportResolver::build(),
                ]),
                hooks: new Hooks([
                    new Validate\EnumContainsZeroVariant(),
                    new Validate\AllEnumVariantNamesAreUnique(),
                    new Validate\AllEnumVariantValuesAreUnique(),
                    new Validate\AllMessageFieldNamesAreUnique(),
                    new Validate\AllMessageFieldNumbersAreUnique(),
                    new Validate\NoConflictTypes(),
                ]),
            ),
        );
    }

    public function compile(
        ProtoFile $file,
        CompileOptions $options = new CompileOptions(), // for future use.
    ): void {
        foreach (($files = $this->protoResolver->resolve($file->path, $file->stream)) as $proto) {
            foreach (
                $this->generator->generate(
                    $proto,
                    $files,
                    $proto->phpNamespace(),
                ) as $fileName => $phpFile
            ) {
                $this->writer->writePhpFile(
                    new Output\PhpFile(
                        $fileName,
                        $this->printer->printFile($phpFile),
                    ),
                );
            }
        }
    }

    /**
     * @param iterable<ProtoFile> $files
     */
    public function compileAll(
        iterable $files,
        CompileOptions $options = new CompileOptions(),
    ): void {
        foreach ($files as $file) {
            $this->compile($file, $options);
        }
    }
}
