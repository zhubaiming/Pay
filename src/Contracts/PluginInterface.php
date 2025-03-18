<?php

namespace Zhubaiming\Pay\Contracts;

interface PluginInterface
{
    public function assembly($passable, $next);
}