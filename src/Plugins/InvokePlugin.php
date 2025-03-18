<?php

namespace Zhubaiming\Pay\Plugins;

use Zhubaiming\Pay\Contracts\PluginInterface;

class InvokePlugin implements PluginInterface
{

    public function assembly($passable, $next)
    {
        echo "\e[32mInvokePlugin\e[0m\n";
//        return $next($passable);

        /**
         * class Builder {
         * public int $a = 1;
         * }
         *
         * class PluginA {
         * public function assembly($passable, $next)
         * {
         * $passable = $next($passable); // 先让下一个 Plugin 处理
         * echo "PluginA 修改前: " . $passable->a . PHP_EOL; // 这里会输出 1
         * $passable->a = 2; // 修改 $passable->a
         * echo "PluginA 修改后: " . $passable->a . PHP_EOL; // 这里会输出 2
         * return $passable;
         * }
         * }
         *
         * class PluginB {
         * public function assembly($passable, $next)
         * {
         * echo "PluginB 处理时: " . $passable->a . PHP_EOL; // 这里会输出 1
         * return $next($passable);
         * }
         * }
         */
        $passable = $next($passable);

        echo "\e[33m1\e[0m\n";

//        $destination = $passable->getDestination();
//
//        var_dump($destination);
//
        return $passable;
    }
}