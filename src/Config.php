<?php
namespace Fatum12\TransfonterCore;

class Config
{
    protected $config;

    public function __construct(array $options)
    {
        $this->config = $options;
    }

    public function get($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->config;
        }
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }
        return $default;
    }

    public function set($key, $value)
    {
        $this->config[$key] = $value;
    }
}