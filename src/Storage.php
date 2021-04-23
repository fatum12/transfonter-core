<?php

namespace Fatum12\TransfonterCore;

class Storage
{
    /**
     * @var array
     */
    protected $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function has($key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }
        return $default;
    }

    public function getAll(): array
    {
        return $this->data;
    }

    public function set($key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function drop($key): void
    {
        unset($this->data[$key]);
    }
}
