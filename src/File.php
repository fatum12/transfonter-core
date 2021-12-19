<?php

namespace Fatum12\TransfonterCore;

class File
{
    /**
     * @var string Path to file
     */
    protected $path;
    /**
     * @var string
     */
    protected $type;
    
    /**
     * @var array Magic numbers
     */
    protected static $magic = [];

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string File extension
     */
    public function getType(): string
    {
        if (!$this->type) {
            $this->type = strtolower(pathinfo($this->path, \PATHINFO_EXTENSION));
        }

        return $this->type;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return basename($this->getPath());
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        $type = $this->getType();

        if (isset(static::$magic[$type])) {
            $search = static::$magic[$type];
            $fh = fopen($this->getPath(), 'rb');
            $magic = fread($fh, strlen($search));
            fclose($fh);

            return $search === $magic;
        }

        return true;
    }

    /**
     * @return int Size of the file in bytes
     */
    public function getSize(): int
    {
        return filesize($this->getPath());
    }
}
