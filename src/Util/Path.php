<?php

namespace Fatum12\TransfonterCore\Util;

class Path
{
    public static function uniqueFileName(string $path): string
    {
        $info = pathinfo($path);

        $newPath = $path;
        $index = 0;
        while (file_exists($newPath)) {
            $index++;
            $newPath = $info['dirname'] . '/' . $info['filename'] . '_' . $index . '.' . $info['extension'];
        }

        return $newPath;
    }

    /**
     * @param string $file Path to file
     * @return string Name of the file without extension
     */
    public static function filename(string $file): string
    {
        return pathinfo($file, PATHINFO_FILENAME);
    }

    public static function extension(string $file): string
    {
        return strtolower(pathinfo($file, PATHINFO_EXTENSION));
    }

    public static function normalize(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $path = preg_replace('/[^a-z0-9_\-\s.\/]/i', '', $path);
        $path = preg_replace('/\s+/', ' ', $path);

        $parts = explode('/', $path);
        $result = [];
        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === "..") {
                if (count($result) > 0) {
                    array_pop($result);
                }
                continue;
            }
            if (!preg_match('/[a-z0-9]/i', $part)) {
                continue;
            }
            $part = trim($part, '. ');
            if ($part !== '') {
                $result[] = $part;
            }
        }

        return implode('/', $result);
    }

    public static function mkdir(string $path, int $mode = 0755, bool $recursive = true): void
    {
        if (!is_dir($path) && !@mkdir($path, $mode, $recursive)) {
            throw new \RuntimeException("Unable to create directory: $path");
        }
    }

    public static function join(string $base, string ...$parts): string
    {
        array_walk($parts, function (&$item) {
            $item = trim($item, '\\/');
        });
        $parts = array_filter($parts, function ($part) {
            return $part !== '';
        });

        $path = implode('/', $parts);
        $base = rtrim($base, '\\/');
        return $base . ($base !== '' && $path !== '' ? '/' : '') . $path;
    }

    public static function changeDirectory(string $path, callable $func): void
    {
        $oldDir = getcwd();
        chdir($path);

        try {
            $func();
        } finally {
            chdir($oldDir);
        }
    }

    public static function createTempDirectory(string $prefix = ''): string
    {
        $path = rtrim(sys_get_temp_dir(), '/') . '/' . $prefix . (new \DateTime())->format('U_u') . '_' . mt_rand();
        if (!@mkdir($path, 0755, true)) {
            throw new \RuntimeException("Unable to create temp directory: $path");
        }
        return $path;
    }

    public static function removeDirectory(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }
        if (!is_dir($path)) {
            unlink($path);
            return;
        }
        foreach (new \DirectoryIterator($path) as $file) {
            if ($file->isDot()) {
                continue;
            }
            self::removeDirectory($file->getPathname());
        }
        if (!@rmdir($path)) {
            throw new \RuntimeException("Unable to delete directory: $path");
        }
    }
}
