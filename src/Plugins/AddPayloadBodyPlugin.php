<?php

namespace Zhubaiming\Pay\Plugins;

use Zhubaiming\Pay\Contracts\PluginInterface;

class AddPayloadBodyPlugin implements PluginInterface
{

    public function assembly($passable, $next)
    {
        /*
         * $passable->getPayload()
         * [
         *     'description' => '商品信息描述，不超过127个字符',
         *     'out_trad_no' => 28973568873705458,
         *     'amount' => ['total' => 1, 'currency' => 'CNY'],
         *     'payer' => ['openid' => 'oaQAW7UWF6z-jH6YljVJi4uvtdI4'],
         *     'attach' => '',
         *     '_method' => 'POST',
         *     'notify_url' => '',
         *     'appid' => '',
         *     'mchid' => '',
         *     '_url' => '/v3/pay/transactions/jsapi',
         * ]
         */
        $passable->mergePayload(['_body' => filter_params($passable->getPayload())]);

        /*
         * $passable->getPayload()
         * [
         *     'description' => '商品信息描述，不超过127个字符',
         *     'out_trad_no' => 28973568873705458,
         *     'amount' => ['total' => 1, 'currency' => 'CNY'],
         *     'payer' => ['openid' => 'oaQAW7UWF6z-jH6YljVJi4uvtdI4'],
         *     'attach' => '',
         *     '_method' => 'POST',
         *     'notify_url' => '',
         *     'appid' => '',
         *     'mchid' => '',
         *     '_url' => '/v3/pay/transactions/jsapi',
         *     '_body' => [
         *         'description' => '商品信息描述，不超过127个字符',
         *         'out_trad_no' => 28973568873705458,
         *         'amount' => ['total' => 1, 'currency' => 'CNY'],
         *         'payer' => ['openid' => 'oaQAW7UWF6z-jH6YljVJi4uvtdI4'],
         *         'attach' => '',
         *         'notify_url' => '',
         *         'appid' => '',
         *         'mchid' => ''
         *     ],
         * ]
         */

        return $next($passable);
    }
}