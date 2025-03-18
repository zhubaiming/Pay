<?php

declare(strict_types=1);

namespace Zhubaiming\Pay\Shortcuts\Wechat;

use Zhubaiming\Pay\Plugins\AddPayloadBodyPlugin;
use Zhubaiming\Pay\Plugins\AddRadarPlugin;
use Zhubaiming\Pay\Plugins\InvokePlugin;
use Zhubaiming\Pay\Plugins\ResponsePlugin;
use Zhubaiming\Pay\Plugins\StartPlugin;
use Zhubaiming\Pay\Plugins\Wechat\V3\AddPayloadSignaturePlugin;
use Zhubaiming\Pay\Plugins\Wechat\V3\Pay\Mini\PayPlugin;
use Zhubaiming\Pay\Plugins\Wechat\V3\VerifySignaturePlugin;

class MiniShortcut
{
    public function getPlugins()
    {
        return [
            StartPlugin::class,               // 获取传递的自定义参数
            PayPlugin::class,                 // 获取该模式下的默认定义参数
            AddPayloadBodyPlugin::class,      //
            AddPayloadSignaturePlugin::class, // 构造接口请求签名
            AddRadarPlugin::class,            //
            InvokePlugin::class,              // 执行请求，并存储返回结果
            VerifySignaturePlugin::class,     // 校验返回内容签名，验证请求结果签名
            ResponsePlugin::class,            // 验证返回状态
//            VerifySignaturePlugin::class,     // 验证请求结果签名
        ];
    }
}