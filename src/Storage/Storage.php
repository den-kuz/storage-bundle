<?php

declare(strict_types=1);

namespace D3N\StorageBundle\Storage;

use Generator;
use LimitIterator;
use RuntimeException;
use SplFileInfo;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use function dirname;
use const DIRECTORY_SEPARATOR;

final readonly class Storage implements StorageInterface
{
    public function __construct(private Filesystem $fs, private string $dir, private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @throws IOException When origin cannot be renamed
     */
    public function move(string|SplFileInfo $file, ?string $name = null, array $path = []): string
    {
        $filepath = $this->getPathname($file);
        $name ??= basename($filepath);
        $target = $this->getFilepath($name, $path);
        $this->fs->rename($filepath, $target, true);

        return $target;
    }

    /**
     * @throws IOException           When link fails
     * @throws FileNotFoundException When original file is missing or not a file
     */
    public function hardLink(string|SplFileInfo $file, ?string $name = null, array $path = []): string
    {
        $filepath = $this->getPathname($file);
        $name ??= basename($filepath);
        $target = $this->getFilepath($name, $path);

        if (file_exists($target)) {
            @unlink($target);
        }

        $this->fs->hardlink($filepath, $target);

        return $target;
    }

    /**
     * Возвращает список каталогов отсортированные natsort
     * Каталоги совпадающие с $pattern идут первыми.
     */
    public function listDirectoriesNatOrderPatternFirst(array $path = [], ?string $pattern = null, int $depth = 0): Generator
    {
        $matchDirs = $dirs = [];

        foreach ($this->listDirectories($path, '*', $depth) as $dir) {
            match (null !== $pattern && str_contains(basename($dir->getPathname()), $pattern)) {
                true => $matchDirs[] = $dir->getPathname(),
                false => $dirs[] = $dir->getPathname(),
            };
        }

        natsort($matchDirs);
        natsort($dirs);

        yield from $matchDirs;

        yield from $dirs;
    }

    public function listFiles(array $path = [], string $name = '*', bool $ignoreDots = true, bool $ignoreVcs = true, array $filters = [], ?int $limit = null, ?int $offset = null): Generator
    {
        $finder = $this
            ->getBaseFinder($path, $name)
            ->sortByModifiedTime()
            ->ignoreDotFiles($ignoreDots)
            ->ignoreVCS($ignoreVcs)
            ->ignoreUnreadableDirs(true)
            ->files();

        foreach ($filters as $filter) {
            $finder->filter($filter);
        }

        yield from new LimitIterator($finder->getIterator(), $offset ?? 0, $limit ?? -1);
    }

    public function listDirectories(array $path = [], string $name = '*', ?int $depth = null): Generator
    {
        $directories = $this->getBaseFinder($path, $name)->directories();

        if (null !== $depth) {
            $directories->depth($depth);
        }

        yield from $directories;
    }

    public function getFilepath(string $name, array $path = [], bool $touch = false): string
    {
        $filepath = $this->getDirectory($path) . DIRECTORY_SEPARATOR . $name;

        if ($touch) {
            touch($filepath);
        }

        return $filepath;
    }

    /**
     * @throws IOException On any directory creation failure
     */
    public function getDirectory(array $path = []): string
    {
        $dir = implode(DIRECTORY_SEPARATOR, $path);

        if (!str_starts_with($dir, $this->dir)) {
            $dir = $this->dir . DIRECTORY_SEPARATOR . $dir;
        }

        if (!is_dir($dir)) {
            $this->fs->mkdir($dir);
        }

        return $dir;
    }

    public function isDirectoryEmpty(array $path): bool
    {
        $directory = $this->getDirectory($path);
        $handle = opendir($directory);

        if (false === $handle) {
            throw new RuntimeException("Can`t open dir {$directory}");
        }

        while ($item = readdir($handle)) {
            if ('.' !== $item && '..' !== $item) {
                closedir($handle);

                return false;
            }
        }
        closedir($handle);

        return true;
    }

    public function removeWithDirectories(string $path): void
    {
        $path = rtrim($path, DIRECTORY_SEPARATOR);

        if ($this->dir === $path || !file_exists($path)) {
            return;
        }

        if (!is_dir($path)) {
            @unlink($path);
        } elseif (false === @rmdir($path)) {
            return;
        }

        $dir = dirname($path);
        $this->removeWithDirectories($dir);
    }

    private function getPathname(string|SplFileInfo $file): string
    {
        return ($file instanceof SplFileInfo) ? $file->getPathname() : $file;
    }

    private function getBaseFinder(array $path, string $name): Finder
    {
        return (new Finder())
            ->in($this->getDirectory($path))
            ->name('*' === $name ? [] : $name);
    }
}
