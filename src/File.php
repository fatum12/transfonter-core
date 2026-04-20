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
            $fh = fopen($this->getPath(), 'rb');
            if ($fh === false) {
                throw new \RuntimeException("Cannot open file for reading: " . $this->getPath());
            }
            try {
                foreach (static::$magic[$type] as $search) {
                    rewind($fh);
                    $magic = fread($fh, strlen($search));
                    if  ($search === $magic) {
                        return true;
                    }
                }
                return false;
            } finally {
                fclose($fh);
            }
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
