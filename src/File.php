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
     * Magic numbers
     * @var array
     */
    protected static $magic = [];

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string Font type
     */
    public function getType()
    {
        if (!$this->type) {
            $this->type = strtolower(pathinfo($this->path, \PATHINFO_EXTENSION));
        }

        return $this->type;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return basename($this->getPath());
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $type = $this->getType();

        if (isset(static::$magic[$type])) {
            $search = static::$magic[$type];
            $fh = fopen($this->getPath(), 'rb');
            $magic = fread($fh, strlen($search));
            fclose($fh);

            return $search === $magic;
        }

        return false;
    }
}