<?php

namespace Fatum12\TransfonterCore;

class Storage
{
    /**
     * @var array
     */
    private $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function get(string $key, $default = null)
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

    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function drop(string $key): void
    {
        unset($this->data[$key]);
    }
}
