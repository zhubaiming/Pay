<?php

namespace Zhubaiming\Pay\Plugins;

use Zhubaiming\Pay\Contracts\PluginInterface;

class StartPlugin implements PluginInterface
{

    public function assembly($passable, $next)
    {
        /*
         * $passable->getParams()
         * [
         *     'description' => '商品信息描述，不超过127个字符',
         *     'out_trad_no' => 28973568873705458,
         *     'amount' => ['total' => 1, 'currency' => 'CNY'],
         *     'payer' => ['openid' => 'oaQAW7UWF6z-jH6YljVJi4uvtdI4'],
         *     'attach' => ''
         * ]
         *
         * $passable->getPayload()
         * []
         *
         * $passable->getPayload()
         * [
         *     'description' => '商品信息描述，不超过127个字符',
         *     'out_trad_no' => 28973568873705458,
         *     'amount' => ['total' => 1, 'currency' => 'CNY'],
         *     'payer' => ['openid' => 'oaQAW7UWF6z-jH6YljVJi4uvtdI4'],
         *     'attach' => ''
         * ]
         */
        $passable->mergePayload($passable->getParams());

        return $next($passable);
    }
}