<?php

namespace Zhubaiming\Pay\Plugins;

use Zhubaiming\Pay\Contracts\PluginInterface;
use Zhubaiming\Pay\Enums\Wechat\V3\HttpEnum;
use Zhubaiming\Pay\Http;

class AddRadarPlugin implements PluginInterface
{

    public function assembly($passable, $next)
    {
        /*
         * $passable->getPayload()
         * [
         *     "description" => "商品信息描述，不超过127个字符",
         *     "out_trad_no" => 5100525001935739845,
         *     "amount" => [
         *         "total" => 1,
         *         "currency" => "CNY"
         *     ],
         *     "payer" => [
         *         "openid" => "oaQAW7UWF6z-jH6YljVJi4uvtdI4"
         *     ],
         *     "attach" => "",
         *     "_method" => "POST",
         *     "notify_url" => "",
         *     "appid" => "",
         *     "mchid" => "",
         *     "_url" => "/v3/pay/transactions/jsapi",
         *     "_body" => [
         *         "description" => "商品信息描述，不超过127个字符",
         *         "out_trad_no" => 5100525001935739845,
         *         "amount" => [
         *             "total" => 1,
         *             "currency" => "CNY"
         *         ],
         *         "payer" => [
         *             "openid" => "oaQAW7UWF6z-jH6YljVJi4uvtdI4"
         *         ],
         *         "attach" => "",
         *         "notify_url" => "",
         *         "appid" => "",
         *         "mchid" => "",
         *     ],
         *     "_authorization" => 'WECHATPAY2-SHA256-RSA2048 mchid="",serial_no="408B07E79B8269FEC3D5D3E6AB8ED163A6A380DB",timestamp="1741936549",nonce_str="9225A533E4484EB280CC43563F16CBFC",signature="gLce6NrKW/1CTABKm2noBYTUyNvbZuybtVfbFdnFhmLxYQHiiMZbbKU0Cg2oSEeN4I1dpa0KJF8hq61GY6Fsi5uy2BHvqenkB38g7NEIWIuZa/YFgTBkvIVATleoTiF+H82ZZKecqWzzYpUPQ4b7zd14U6IbOEqJ28pEDbGPiz0aGxmsWEVJieE1m6BgtWaZqcR5K+l67B7AqKbHWzxJ1kYBBPvxXNuTz78QNBHjuBkPIRoRo36egZFXtQPwI9xUM7whflfiRIjC3yhMRwMvOr84b9xCH8YtNPI8s4J0bCiGzd6WfI1V1sR/L3+wlDXVquRxWJ9K7oxWa9yy92RBfg=="'
         * ]
         */
        $payload = $passable->getPayload();

        $passable->setRadar(new Http()
            ->setMethod($payload['_method'])
            ->setUrl($payload['_url'])
            ->setHeaders($this->setHeaders($payload))
            ->setBody($payload['_body'])
            ->setEnum(HttpEnum::class)
        );

        return $next($passable);
    }

    private function setHeaders($payload)
    {
        return [
            'Authorization' => $payload['_authorization']
        ];
    }
}
