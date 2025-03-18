<?php

namespace Zhubaiming\Pay\Plugins;

use Zhubaiming\Pay\Contracts\PluginInterface;

class ResponsePlugin implements PluginInterface
{

    public function assembly($passable, $next)
    {
        $passable = $next($passable);

        var_dump($passable->getDestination());

        return $passable;
    }
}