<?php

namespace acfunpro\afmsgsender\agents;

use acfunpro\afmsgsender\agents\Agent;

class AlidayuAgent extends Agent
{
    /**
     * 请求地址
     * @var string
     */
    protected $url;

    /**
     * 应用的App Key
     * @var string
     */
    protected $appKey;

    /**
     * 应用的密匙
     * @var string
     */
    protected $secretKey;

    /**
     * api协议版本
     * @var string
     */
    protected $apiVersion;

    /**
     * 响应格式 默认为xml 可选值:xml json
     * @var string
     */
    protected $format;

    /**
     * api接口名称
     * @var string
     */
    protected $method;

    /**
     * 签名的摘要算法 可选值:hmac md5
     * @var string
     */
    protected $signMethod;

    /**
     * 短信签名
     * @var string
     */
    protected $smsFreeSignName;



    public function __construct()
    {
        $this->url              = config('message.agent.Alidayu.sendUrl');
        $this->appKey           = config('message.agent.Alidayu.appKey');
        $this->secretKey        = config('message.agent.Alidayu.secretKey');
        $this->smsFreeSignName  = config('message.agent.Alidayu.smsFreeSignName');
        $this->method           = 'alibaba.aliqin.fc.sms.num.send';
        $this->apiVersion       = '2.0';
        $this->format           = 'json';
        $this->signMethod       = 'md5';
    }

    /**
     * 生成签名信息
     * @param $params
     * @return mixed
     */
    protected function generateSign($params)
    {
        ksort($params);

        $stringToBeSigned = $this->secretKey;
        foreach ($params as $k => $v)
        {
            if(is_string($v) && "@" != substr($v, 0, 1))
            {
                $stringToBeSigned .= "$k$v";
            }
        }
        unset($k, $v);
        $stringToBeSigned .= $this->secretKey;

        return strtoupper(md5($stringToBeSigned));
    }

    public function createParams(array $data)
    {
        // 组装系统参数
        $sysParams['method'] = $this->method;
        $sysParams['app_key'] = $this->appKey;
        $sysParams['v'] = $this->apiVersion;
        $sysParams['format'] = $this->format;
        $sysParams['sign_method'] = 'md5';
        $sysParams['timestamp'] = date('Y-m-d H:i:s');

        // 组装请求参数
        $apiParams['sms_type']           = 'normal';
        $apiParams['rec_num']            = $data['mobile'];
        $apiParams['sms_template_code']  = 'SMS_25645114';
        $apiParams['sms_free_sign_name'] = $this->smsFreeSignName;
        $apiParams['sms_param']          = $this->getTempDataString($data);

        // 签名
        $sysParams['sign'] = $this->generateSign(array_merge($sysParams, $apiParams));

        return array_merge($sysParams, $apiParams);
    }

    public function request(array $data)
    {
        $params = $this->createParams($data);
        $result = $this->curl($this->url, $params, true);
        return $result;
    }

    public function response(array $data)
    {
        $callBackName = $this->getResponseName($this->method);

        $response = $this->request($data);

        if ($response['request']) {
            $result = json_decode($response['response'], true);
            if (isset($result[$callBackName]['result'])) {
                $result = $result[$callBackName]['result'];
            } elseif (isset($result['error_response'])) {
                $result = $result['error_response'];
            } else {
                $result = ['error_code' => 400, 'msg' => 'request fail'];
            }
        }

        return $result;

    }

    protected function getTempDataString(array $data)
    {
        $data = array_map(function ($value) {
            return (string) $value;
        }, $data);
        return json_encode($data);
    }

    protected function getResponseName($method)
    {
        return str_replace('.', '_', $method) . '_response';
    }

    public function curl($url, array $params, $isPost = false)
    {
        $request = true;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($isPost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            $params = http_build_query($params);
            curl_setopt($ch, CURLOPT_URL, $params ? "$url?$params" : $url);
        }
        $response = curl_exec($ch);
        if ($response === false) {
            $request = false;
            $response = curl_getinfo($ch);
        }
        curl_close($ch);
        return compact('request', 'response');
    }
}