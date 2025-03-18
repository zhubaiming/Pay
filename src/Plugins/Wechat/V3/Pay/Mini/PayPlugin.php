<?php

namespace Zhubaiming\Pay\Plugins\Wechat\V3\Pay\Mini;

use Zhubaiming\Pay\Contracts\PluginInterface;
use Zhubaiming\Pay\Pay;

class PayPlugin implements PluginInterface
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
         *     'attach' => ''
         * ]
         *
         * $passable->getParams()
         * [
         *     'description' => '商品信息描述，不超过127个字符',
         *     'out_trad_no' => 28973568873705458,
         *     'amount' => ['total' => 1, 'currency' => 'CNY'],
         *     'payer' => ['openid' => 'oaQAW7UWF6z-jH6YljVJi4uvtdI4'],
         *     'attach' => ''
         * ]
         */
        $payload = $passable->getPayload();
        $params = $passable->getParams();

        $config = Pay::getConfig();

        /*
         *  * Header
         * Accept: application/json
         * Content-Type: application/json
         * Authorization: 签名认证生成认证信息
         *
         * Body
         * 必填
         * appid: 【公众账号ID】是商户在微信开放平台（移动应用）或公众平台（公众号/小程序）上申请的一个唯一标识。需确保该appid与mchid有绑定关系，具体请参考普通商户模式开发必要参数说明
         * mchid: 【商户号】是由微信支付系统生成并分配给每个商户的唯一标识符，商户号获取方式请参考普通商户模式开发必要参数说明
         * description: 【商品描述】商品信息描述，用户微信账单的商品字段中可见(可参考JSAPI支付示例说明-账单示意图)，商户需传递能真实代表商品信息的描述，不能超过127个字符
         * out_trad_no: 【商户订单号】商户系统内部订单号，要求6-32个字符内，只能是数字、大小写字母_-|* 且在同一个商户号下唯一
         * notify_url: 【商户回调地址】商户接收支付成功回调通知的地址，需按照notify_url填写注意事项规范填写
         * amount: 【订单金额】订单金额信息
         *       total: 【总金额】 订单总金额，单位为分，整型。示例：1元应填写 100
         *       currency: 【货币类型】符合ISO 4217标准的三位字母代码，固定传：CNY，代表人民币
         * payer: 【支付者信息】支付者信息
         *      openid: 【用户标识】用户在商户appid下的唯一标识。下单前需获取到用户的OpenID，详见OpenID获取
         * 选填
         * time_expire: 【支付结束时间】定义：支付结束时间是指用户能够完成该笔订单支付的最后时限，并非订单关闭的时间。超过此时间后，用户将无法对该笔订单进行支付。如需关闭订单，请调用关闭订单API接口。2、格式要求：支付结束时间需遵循rfc3339标准格式：yyyy-MM-DDTHH:mm:ss+TIMEZONE。yyyy-MM-DD 表示年月日；T 字符用于分隔日期和时间部分；HH:mm:ss 表示具体的时分秒；TIMEZONE 表示时区（例如，+08:00 对应东八区时间，即北京时间）
         * attach: 【商户数据包】商户在创建订单时可传入自定义数据包，该数据对用户不可见，用于存储订单相关的商户自定义信息，其总长度限制在128字符以内。支付成功后查询订单API和支付成功回调通知均会将此字段返回给商户，并且该字段还会体现在交易账单
         * goods_tag: 【订单优惠标记】代金券在创建时可以配置多个订单优惠标记，标记的内容由创券商户自定义设置。详细参考：创建代金券批次API。如果代金券有配置订单优惠标记，则必须在该参数传任意一个配置的订单优惠标记才能使用券。如果代金券没有配置订单优惠标记，则可以不传该参数。示例：如有两个活动，活动A设置了两个优惠标记：WXG1、WXG2；活动B设置了两个优惠标记：WXG1、WXG3；下单时优惠标记传WXG2，则订单参与活动A的优惠；下单时优惠标记传WXG3，则订单参与活动B的优惠；下单时优惠标记传共同的WXG1，则订单参与活动A、B两个活动的优惠
         * support_fapiao: 【电子发票入口开放标识】 传入true时，支付成功消息和支付详情页将出现开票入口。需要在微信支付商户平台或微信公众平台开通电子发票功能，传此字段才可生效。 详细参考：电子发票介绍true：是false：否
         * detail: 【优惠功能】 优惠功能
         *       cost_price: 【订单原价】1、商户侧一张小票订单可能被分多次支付，订单原价用于记录整张小票的交易金额。2、当订单原价与支付金额不相等，则不享受优惠。3、该字段主要用于防止同一张小票分多次支付，以享受多次优惠的情况，正常支付订单不必上传此参数。
         *       invoice_id: 【商品小票ID】 商家小票ID
         *       goods_detail: 【单品列表】 单品列表信息
         */

        $passable->mergePayload(array_merge([
            '_method' => 'POST',
            'notify_url' => ''
        ], $this->{$config['mode']}($config)));


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

        return $next($passable);
    }

    protected function normal($config)
    {
        return [
            'appid' => $config['appid'],
            'mchid' => $config['mchid'],
            '_url' => '/v3/pay/transactions/jsapi',
        ];
    }

    protected function partner($config)
    {
        return [
            'sp_appid' => $config['sp_appid'],
            'sp_mchid' => $config['sp_mchid'],
            'sub_appid' => $config['sub_appid'],
            'sub_mchid' => $config['sub_mchid'],
            '_url' => '/v3/pay/partner/transactions/jsapi',
        ];
    }

    protected function global($config)
    {
        return [
            'sp_appid' => '',
            'sp_mchid' => '',
            'sub_appid' => '',
            'sub_mchid' => '',
            '_url' => '/v3/global/transactions/jsapi',
        ];
    }
}