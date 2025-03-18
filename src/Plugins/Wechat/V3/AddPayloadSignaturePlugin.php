<?php

namespace Zhubaiming\Pay\Plugins\Wechat\V3;

use Carbon\Carbon;
use Zhubaiming\Pay\Contracts\PluginInterface;
use Zhubaiming\Pay\Services\WechatService;

class AddPayloadSignaturePlugin implements PluginInterface
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

        $payload = $passable->getPayload();

        $timestamp = Carbon::now()->timestamp;
        $nonce_str = random_nonce(32);
        $signContent = $this->getSignatureContent($payload, $timestamp, $nonce_str);
        $signature = $this->getSignature($payload, $timestamp, $nonce_str, $signContent);

        $passable->mergePayload(['_url' => WechatService::NORMAL_URL . $payload['_url'], '_authorization' => $signature]);

        return $next($passable);
    }

    public function getSignatureContent(array $payload, int $timestamp, string $nonce_str)
    {
        $url_parse = parse_url($payload['_url']);

        /*
         * path参数
         * GET
         * /v3/refund/domestic/refunds/123123123123(完整url，即替换过参数之后的url)
         * 1554208460
         * 593BEC0C930BF1AFEB40B4A08C8FB242(调用随机数函数生成，将得到的值转换为字符串)
         * 空串，只需附加一个\n
         * GET\n/v3/refund/domestic/refunds/123123123123\n1554208460\n593BEC0C930BF1AFEB40B4A08C8FB242\n\n
         */

        /*
         * body参数
         * POST
         * /v3/pay/transactions/jsapi
         * 1554208460
         * 593BEC0C930BF1AFEB40B4A08C8FB242
         * {"appid":"wxd678efh567hg6787","mchid":"1230000109","description":"Image形象店-深圳腾大-QQ公仔","out_trade_no":"1217752501201407033233368018","notify_url":"https://www.weixin.qq.com/wxpay/pay.php","amount":{"total":100,"currency":"CNY"},"payer":{"openid":"oUpF8uMuAJO_M2pxb1Q9zNjWeS6o"}}
         * POST\n/v3/pay/transactions/jsapi\n1554208460\n593BEC0C930BF1AFEB40B4A08C8FB242\n{"appid":"wxd678efh567hg6787","mchid":"1230000109","description":"Image形象店-深圳腾大-QQ公仔","out_trade_no":"1217752501201407033233368018","notify_url":"https://www.weixin.qq.com/wxpay/pay.php","amount":{"total":100,"currency":"CNY"},"payer":{"openid":"oUpF8uMuAJO_M2pxb1Q9zNjWeS6o"}}\n
         */

        /*
         * query参数
         * GET
         *
         * limit=5
         * offset=10
         * authorized_data={"business_type":"FAVOR_STOCK", "stock_id":"2433405"}
         * partner={"type":"APPID","appid":"wx4e1916a585d1f4e9","merchant_id":"2480029552"}
         * /v3/marketing/partnerships?limit=5&offset=10&authorized_data%3D%7B%22business_type%22%3A%22FAVOR_STOCK%22%2C%20%22stock_id%22%3A%222433405%22%7D&partner%3D%7B%22type%22%3A%22APPID%22%2C%22appid%22%3A%22wx4e1916a585d1f4e9%22%2C%22merchant_id%22%3A%222480029552%22%7D(对请求的url进行 encodeURL)
         *
         * 1554208460
         * 593BEC0C930BF1AFEB40B4A08C8FB242
         * 空串，只需附加一个\n
         * GET\n/v3/marketing/partnerships?limit=5&offset=10&authorized_data%3D%7B%22business_type%22%3A%22FAVOR_STOCK%22%2C%20%22stock_id%22%3A%222433405%22%7D&partner%3D%7B%22type%22%3A%22APPID%22%2C%22appid%22%3A%22wx4e1916a585d1f4e9%22%2C%22merchant_id%22%3A%222480029552%22%7D\n1554208460\n593BEC0C930BF1AFEB40B4A08C8FB242\n\n
         */

        /*
         * $a = [
        'limit' => 5,
        'offset' => 10,
        'authorized_data' => ['business_type' => 'FAVOR_STOCK', 'stock_id' => '2433405'],
        'partner' => ['type' => 'APPID', 'appid' => 'wx4e1916a585d1f4e9', 'merchant_id' => '2480029552']
    ];

//    $s = [];
    $s = array_map(function ($key, $value) {
        if (is_array($value)) {
            return rawurlencode($key . '=' . json_encode($value));
        } else {
            return $key . '=' . $value;
        }
    }, array_keys($a), $a);

    dd($s, implode('&', $s));
         */

        return get_radar_method($payload) . "\n" .
        $url_parse['path'] . (isset($url_parse['query']) ? '?' . $url_parse['query'] : '') . "\n" .
        $timestamp . "\n" .
        $nonce_str . "\n" .
        empty($payload) ? '' : json_encode($payload, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
    }

    public function getSignature(array $payload, int $timestamp, string $nonce_str, $signContent)
    {
        /*
         * // 读取 PEM 格式的证书文件
$cert = file_get_contents('path_to_your_cert.pem');

// 解析证书
$parsedCert = openssl_x509_parse($cert);

// 获取证书序列号
$serialNumber = $parsedCert['serialNumberHex'];
         */
        $auth = sprintf(
            'mchid="%s",serial_no="%s",timestamp="%d",nonce_str="%s",signature="%s"',
            $payload['mchid'],
            '408B07E79B8269FEC3D5D3E6AB8ED163A6A380DB',
            $timestamp,
            $nonce_str,
            get_wechat_signature($signContent)
        );

        return 'WECHATPAY2-SHA256-RSA2048 ' . $auth;
    }
}