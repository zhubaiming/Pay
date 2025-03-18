<?php

declare(strict_types=1);

namespace Zhubaiming\Pay\Services;

use Zhubaiming\Pay\Builder;
use Zhubaiming\Pay\Pipeline;

class WechatService
{
    public const NORMAL_URL = 'https://api.mch.weixin.qq.com';

    public const PARTNER_URL = 'https://api.mch.weixin.qq.com';

    public const GLOBAL_URL = 'https://apihk.mch.weixin.qq.com';

    private $shortcut;
    private $passable;

    private function __construct()
    {
    }

    public function __call(string $name, array $arguments)
    {
        $class = ucfirst($name);

        $namespace = substr(__NAMESPACE__, 0, strrpos(__NAMESPACE__, '\\')) . '\\Shortcuts\\Wechat\\' . $class . 'Shortcut';

        try {
            // ReflectionClass 是 PHP 反射 API 的一部分，它允许你在运行时检查和操作类的结构，例如获取类的方法、属性、构造函数，甚至动态实例化对象

            // 代码会检查类是否存在，并获取它的反射信息。如果类不存在，会抛出 ReflectionException
            $reflector = new \ReflectionClass($namespace);

            // 通过反射创建类的实例
            // 1、创建类的新实例，传递参数给构造函数，newInstance($arg1, $arg2, ...)
            // 2、创建实例但不调用构造方法，newInstanceWithoutConstructor()
            // 3、通过参数数组创建类的新实例，newInstanceArgs(array $args)
//            return $reflector->newInstanceWithoutConstructor();
            $this->shortcut = $reflector->newInstanceWithoutConstructor();

//            $this->through($this->shortcut->getPlugins())->via('assembly')->then($this->shortcut->getPlugins());


            $pipeline = new Pipeline();

            $builder = $pipeline
                ->send(new Builder()->setParams(...$arguments)->setPayload([]))
                ->through($this->shortcut->getPlugins())
                ->via('assembly')
                ->then(static fn($builder) => self::ignite($builder));

//            $this->then($this->shortcut->getPlugins());
        } catch (\Exception $exception) {
            throw new \InvalidArgumentException('服务未找到' . $exception->getMessage());
        }
    }

    private static function ignite($builder) // 这里的 $builder 相当于 $passable
    {
        if (!$builder->getDirection()) {
            echo "\e[031m不执行\e[0m\n";
            return $builder;
        }

        echo "\e[032m准备请求第三方 API\e[0m\n";

        $response = $builder->getRadar()->send();

        $builder->setDestination(clone $response)->setDestinationOrigin(clone $response);

        echo "\e[032m请求第三方 API 完毕\e[0m\n";

        return $builder;
    }

    private function parsePipeString($pipe)
    {
        [$name, $parameters] = array_pad(explode(':', $pipe, 2), 2, []);

        if (is_string($parameters)) {
            $parameters = explode(',', $parameters);
        }

        return [$name, $parameters];
    }
}