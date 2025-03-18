<?php

namespace Zhubaiming\Pay;

class Http
{
    private string $method;

    private string $url;

    private array $headers = [
        'Content-Type' => 'application/json; charset=utf-8',
        'Accept' => 'application/json, text/plain, application/x-gzip',
        'User-Agent' => '',
    ];

    private string $body = '';

    private $responseEunm = null;

    private $response;

    public function __construct()
    {
    }

    public function setMethod(string $method)
    {
        $this->method = $method;

        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setBody(array $body)
    {
        $this->body = json_encode($body, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setEnum($enum)
    {
        $this->responseEunm = $enum;

        return $this;
    }

    public function send()
    {
        $ch = curl_init($this->url);

        $opts = [
            CURLOPT_RETURNTRANSFER => true,                // 返回结果而非直接输出
            CURLOPT_HEADER => true,                        // 是否返回响应头(默认 false)
            CURLOPT_CUSTOMREQUEST => $this->method,        // 设置 HTTP 方法(GET, POST, PUT, DELETE 等)
            CURLOPT_HTTPHEADER => $this->headers,          // 设置请求头(数组)
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, // 选择 HTTP 版本(CURL_HTTP_VERSION_1_0、CURL_HTTP_VERSION_1_1、CURL_HTTP_VERSION_2_0)
            CURLOPT_TIMEOUT => 30,                         // 超时时间(秒)
            CURLOPT_CONNECTTIMEOUT => 15,                  // 连接超时时间(秒)
            CURLOPT_FOLLOWLOCATION => false                // 是否允许 Location 302 重定向
        ];

        if (in_array($this->method, ['POST', 'PUT'])) $opts[CURLOPT_POSTFIELDS] = $this->body;

        curl_setopt_array($ch, $opts);

        $response = curl_exec($ch);

        if ($response === false) {
            die('cURL 错误: ' . curl_error($ch));
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // HTTP 状态码
        curl_close($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        if ($this->responseEunm::isSuccess($httpCode)) {
            return [
                'headers' => $this->parseHeaders($header),
                'body' => $this->parseBody($header['Content-Type'], $body)
            ];
        } else {
//            throw new HttpExceptio();
            die("\e[031m" . $this->responseEunm::from($httpCode)->getMessage() . "\e[0m\n");
        }
    }

    private function parseHeaders($headerString): array
    {
        $headers = [];
        $lines = explode("\r\n", $headerString);

        foreach ($lines as $line) {
            if (str_contains($line, ': ')) {
                list($key, $value) = explode(': ', $line, 2);
                $headers[$key] = $value;
            }
        }

        return $headers;
    }

    private function parseBody($contentType, $body)
    {
        return match (true) {
            str_contains($contentType, 'application/json') => json_decode($body, true),
            str_contains($contentType, 'application/xml'),
            str_contains($contentType, 'text/xml') => simplexml_load_string($body),
            str_contains($contentType, 'text/html'),
            str_contains($contentType, 'text/plain') => $body,
            default => ''
        };
    }
}