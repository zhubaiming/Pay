<?php

namespace Zhubaiming\Pay\Plugins\Wechat\V3;

use Zhubaiming\Pay\Contracts\PluginInterface;

class VerifySignaturePlugin implements PluginInterface
{

    public function assembly($passable, $next)
    {
        echo "\e[32mIVerifySignaturePlugin\e[0m\n";
        $passable->setDirection(true);
//        return $next($passable);

        $passable = $next($passable);

        echo "\e[33m2\e[0m\n";

        return $passable;
    }

//1$ echo -n -e \
//2 'GET\n/v3/refund/domestic/refunds/123123123123\n1554208460\n593BEC0C930BF1AFEB40B4A08C8FB242\n\n' \
//3 | openssl dgst -sha256 -sign apiclient_test_key.pem \
//4 | openssl base64 -A
//
//
//
//
//$openssl base64 -d -A <<< \ 'CtcbzwtQjN8rnOXItEBJ5aQFSnIXESeV28Pr2YEmf9wsDQ8Nx25ytW6FXBCAFdrr0mgqngX3AD9gNzjnNHzSGTPBSsaEkIfhPF4b8YRRTpny88tNLyprXA0GU5ID3DkZHpjFkX1hAp/D0fva2GKjGRLtvYbtUk/OLYqFuzbjt3yOBzJSKQqJsvbXILffgAmX4pKql+Ln+6UPvSCeKwznvtPaEx+9nMBmKu7Wpbqm/+2ksc0XwjD+xlvlECkCxfD/OJ4gN3IurE0fpjxIkvHDiinQmk51BI7zQD8k1znU7r/spPqB+vZjc5ep6DC5wZUpFu5vJ8MoNKjCu8wnzyCFdA==' > signature.txt
//（3）最后，验证签名，得到验签结果，请确认你的结果和文档的结果一致，如果验签结果是Verification Failure，请确认是否获取到了正确的微信支付公钥或者验签串是否有严格按照文档格式换行
//
//
//1$ openssl dgst -sha256 -verify 1900009191_wxp_pub.pem -signature signature.txt << EOF
//21554209980
//3c5ac7061fccab6bf3e254dcf98995b8c
//4{"data":[{"serial_no":"5157F09EFDC096DE15EBE81A47057A7232F1B8E1","effective_time":"2018-03-26T11:39:50+08:00","expire_time":"2023-03-25T11:39:50+08:00","encrypt_certificate":{"algorithm":"AEAD_AES_256_GCM","nonce":"d215b0511e9c","associated_data":"certificate","ciphertext":"..."}}]}
//5EOF
}