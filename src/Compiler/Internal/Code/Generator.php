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

use Nette\PhpGenerator\PhpFile;
use Prototype\Compiler\Internal\Ir;

/**
 * @internal
 * @psalm-internal Prototype\Compiler
 */
final class Generator
{
    private readonly Ir\TypeVisitor $typeVisitor;

    public function __construct(
        private readonly PhpFileFactory $files,
    ) {
        $this->typeVisitor = new ProtoTypeToPhpTypeVisitor();
    }

    /**
     * @param non-empty-string $phpNamespace
     * @return \Generator<non-empty-string, PhpFile>
     */
    public function generate(
        Ir\Proto $proto,
        string $phpNamespace,
    ): \Generator {
        foreach ($proto->definitions as $definition) {
            $file = $this->files->newFile();

            $typeName = $definition->generate(
                new DefinitionGenerator(
                    $file->addNamespace(
                        Naming\NamespaceLike::name($phpNamespace),
                    ),
                    $this->typeVisitor,
                ),
            );

            yield $typeName.'.php' => $file;
        }
    }
}
