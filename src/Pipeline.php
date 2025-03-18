<?php

namespace Zhubaiming\Pay;

class Pipeline
{
    protected mixed $passable;

    protected array $plugins = [];
    protected array $pipes = [];

    protected string $method = 'handle';

    public function send(mixed $passable): static
    {
        $this->passable = $passable;

        return $this;
    }

    public function through(mixed $plugins) // (mixed $pipes)
    {
        $this->plugins = is_array($plugins) ? $plugins : func_get_args();

        return $this;
    }

    public function via(string $method)
    {
        $this->method = $method;

        return $this;
    }

    public function then($callback)
    {
        $pipeline = array_reduce(array_reverse($this->plugins), $this->carry(), $this->prepareDestination($callback));

        return $pipeline($this->passable);
    }

    protected function prepareDestination($callback)
    {
        return static function ($passable) use ($callback) {
            return $callback($passable);
        };
    }

    protected function carry()
    {
        return function ($stack, $plugin) {
            return function ($passable) use ($stack, $plugin) {
                $plugin = new $plugin();

                $carry = method_exists($plugin, 'assembly') ? $plugin->assembly($passable, $stack) : $plugin($passable, $stack);

                return $carry;
            };
        };
    }
}