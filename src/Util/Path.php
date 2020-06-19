<?php

namespace Fatum12\TransfonterCore\Util;

use Fatum12\TransfonterCore\Exception\BaseException;

class Path
{
    public static function uniqueFileName($path)
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
    public static function filename($file)
    {
        return pathinfo($file, PATHINFO_FILENAME);
    }

    public static function normalize($path)
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

    public static function mkdir($path, $mode = 0755, $recursive = true)
    {
        if (!is_dir($path) && !@mkdir($path, $mode, $recursive)) {
            throw new \RuntimeException("Unable to create directory: {$path}");
        }
    }

    public static function join($base, ...$parts)
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
}
