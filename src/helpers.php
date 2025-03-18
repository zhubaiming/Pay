<?php

declare(strict_types=1);

if (!function_exists('config')){
    function config(string $path)
    {
        return include_once $path;
    }
}

if (!function_exists('to_studly_case')) {
    /**
     * 大驼峰
     * @param string $string
     * @return array|string|string[]|null
     */
    function to_studly_case(string $string)
    {
        // 方法1
//        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string)));
        // 方法2
        return preg_replace_callback('/(?:^|-|_)([a-zA-Z])/', function ($matches) {
            return strtoupper($matches[1]);
        }, $string);
    }
}

if (!function_exists('to_camel_case')) {
    /**
     * 小驼峰
     * @param string $string
     * @return string
     */
    function to_camel_case(string $string)
    {
        return lcfirst(to_studly_case($string));
    }
}


if (!function_exists('get_radar_method')) {
    function get_radar_method(array $payload)
    {
        $string = $payload['_method'] ?? null;

        return is_null($string) ? null : strtoupper($string);
    }
}

/* 以下是针对微信的 */

if (!function_exists('get_public_cert')) {
    function get_public_cert(string $keyPath)
    {
        return is_file($keyPath) ? file_get_contents($keyPath) : $keyPath;
    }
}

if (!function_exists('get_wechat_signature')) {
    function get_wechat_signature(string $contents)
    {
        $privateKey = "-----BEGIN RSA PRIVATE KEY-----
MIIEogIBAAKCAQEAptpm+qvIDCh/9wjU26SQCK26ogYkBhDrYxnAaw2JbbBsp1oD
bHKk+1r381NeBUG2HEFAuU+Fr72u5ot3yKdzoF/FajAzQNKnm569/D3upKoi8mYB
aST15Uig8j8qoUW1U217LL0jEHlSnHV3lcaDTXqDpTRR4Bfz9IqOgJgFZ8/oTfEo
mSrjrLYef81Eyxr7ZIMQXEKKEK7V4UXKS0+/fDsiG/cXidhzt8UbTL9vqXqxM2+I
DImyO+FAc/tkBG55LmzxPto1Nq0WbnZzRM/wTzrd0I/8NlevxtFbphg4evlHjFNI
7+GrqR87ViEwuAJJ9Je5QQjct5YJfFRWiZ5CMQIDAQABAoIBAGBi/GhEgezcHIg1
ltlHaFlLGuxsRbUnYwM9phVxnXk7GJlYe2/TjpERjPkIqOC6hBwwadZjJORP3FCc
Mtc8PKRhjuZ377O7vU0915x2nnyLOGL1IE2AJ3iLi0ZFzTea0FPgg+5lWHM00s9F
YI6qPcGtS41M+xtMWwZiYE3TBBRibHiY8ugGyaNAhiMKehyW05uApjlIF55wwCGx
BkyESJpGRR/6853iHke6Ge+xVcMa9QmQdoH0QqL/8kT28PL568mJJr0Ow/83t4+d
Pe70YPzKAxgUnaDsHJqO+b8qH69AEs8rTI5h2Mon6pH+bJT66KUoiXhn+Kf+4LSs
henRP10CgYEA1QJSfuFOWVRjrg3N/rAIc/Ak84BTZavbyrkqBSuoTs9i/nMI/hOz
VxpDntg7Bx2Tctl6sZO3GioTxKdc/YYaTKci1TKBbeginpsqEQVgwkMCy8HpvUmR
fyAMqLwZC4h9+j+NiZtuoFJDTCgv+WYbasX+kWYEUM21bnSYuO7yEQsCgYEAyIdP
r9uzqPgzN34Tmx+CNTa16VjhBh+zkBtXRLDLhWBeIYxoYNJARD98Pb1XZdvpkZZW
Sk7MfaKo2/DomzyyyB/MbHWwAdFi3yb4y7uMJfyC1MzdUSNN3Vp579hJxHkJ+nN4
Ys76yfcEeVOLnvUT1Z0KKCdIWRdT1Lgi+X1itzMCgYBJUXlPzwGG4fNFj97d0X23
Wmt9nSgXkOYgi0eZbAOMzPmIF9R6kBFk49dur4Lx2g5Ms+r1gKC/0sfnIqxxX11i
EQ1+UNoYGJUB/uql3TIG68XkmKR50P7RwRhaZBRC0gJ6xrFTMjsL2ATuC88niyvY
vrn3FiRaI9RVZrDCxwxvLQKBgEXW4okEAqGBuAzGqztmkOnJoTehDdYdKmOxMgap
cGiGdKJIjX3THDDoz3ONQyglnEZpTqpYoV3MTfU0BT8zt6x9bqwDnQY1D7NalmIW
cqw0Mri8lQQSQKcsQLWo5aA466G/n5kCL1Qx5OwAjesRvhOyuvvbGpZ0ymyWqQ+t
fLkDAoGATcul1L8y5D/wNVP1GXbXMZfBsFP3bbqy8c+Ashm6g8OLm2mGNntd5Z6h
1KkID7Yksh+dZ6t7XaPBtGACXX5Eryr537JVvdX8hAVCp5HVtaN/9VBVP8Ka2e4s
VS/xeNgOMQ7uzhRPBJ8HiTmdI1nHhDnYQpGiBgQn0Z5RAkSvFMk=
-----END RSA PRIVATE KEY-----";

        openssl_sign($contents, $sign, $privateKey, OPENSSL_ALGO_SHA256);

        return base64_encode($sign);
    }
}

if (!function_exists('filter_params')) {
    function filter_params(array $params)
    {
        return array_filter($params, function ($param, $key) {
            // php 7及更早版本
            // return strpos($key, '_') !== 0;
            // php 8+
            return !str_starts_with($key, '_');
        }, ARRAY_FILTER_USE_BOTH);
    }
}

if (!function_exists('random_nonce')) {
    function random_nonce(int $length)
    {
        return strtoupper(substr(bin2hex(random_bytes($length)), 0, $length));
    }
}
if (!function_exists('')) {
}
if (!function_exists('')) {
}
if (!function_exists('')) {
}
if (!function_exists('')) {
}