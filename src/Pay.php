<?php

declare(strict_types=1);

namespace Zhubaiming\Pay;

class Pay
{
    private static ?self $instance = null;

    private static array $config = [];

    private static $test = 1;

    private function __construct()
    {
        self::$test = 2;
    }

    private function __clone(): void
    {
    }

    public static function getInstance(): ?self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function __callStatic(string $serverName, array $config = [])
    {
        var_dump($config);

        if (!empty($config)) {

        }

        return self::getInstance()::setNext($serverName);
//        return self::setNext($serverName);
    }

    protected static function setNext($serverName)
    {
        echo "\e[033m" . self::$test . "\e[0m\n";
        die(9);
        $class = ucfirst($name);

        try {
            $reflector = new \ReflectionClass(__NAMESPACE__ . '\\Services\\' . $class . 'Service');

            return $reflector->newInstanceWithoutConstructor();
        } catch (\Exception $exception) {
            throw new \InvalidArgumentException('服务未找到');
        }
    }

    public static function setConfig(array $config)
    {
        self::$config = $config;
    }

    public static function getConfig()
    {
        return self::$config;
    }
}