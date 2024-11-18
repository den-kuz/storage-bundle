<?php

declare(strict_types=1);

namespace D3N\StorageBundle\Storage;

use Closure;
use Generator;
use SplFileInfo;

interface StorageInterface
{
    public function getName(): string;

    public function move(string|SplFileInfo $file, ?string $name = null, array $path = []): string;

    public function hardLink(string|SplFileInfo $file, ?string $name = null, array $path = []): string;

    public function listDirectoriesNatOrderPatternFirst(array $path = [], ?string $pattern = null, int $depth = 0): Generator;

    /**
     * @param Closure[] $filters
     */
    public function listFiles(array $path = [], string $name = '*', bool $ignoreDots = true, bool $ignoreVcs = true, array $filters = [], ?int $limit = null, ?int $offset = null): Generator;

    public function listDirectories(array $path = [], string $name = '*', ?int $depth = null): Generator;

    public function getFilepath(string $name, array $path = [], bool $touch = false): string;

    public function getDirectory(array $path = []): string;

    public function isDirectoryEmpty(array $path): bool;

    public function removeWithDirectories(string $path): void;
}
