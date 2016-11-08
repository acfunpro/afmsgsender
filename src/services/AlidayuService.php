<?php

namespace acfunpro\afmsgsender\service;

use acfunpro\afmsgsender\service\service;

class AlidayuService extends Service
{
    protected $config = [];

    /**
     * Request address
     * @var string
     */
    protected $url;

    /**
     * App key
     * @var string
     */
    protected $appKey;

    /**
     * App secret
     * @var string
     */
    protected $secretKey;

    /**
     * Api version
     * @var string
     */
    protected $apiVersion;

    /**
     * Response format （Default：xml Optional：xml json）
     * @var string
     */
    protected $format;

    /**
     * Api method name
     * @var string
     */
    protected $method;

    /**
     * Signature algorithm  （Optional：hmac md5）
     * @var string
     */
    protected $signMethod;

    /**
     * SMS free sign name
     * @var string
     */
    protected $smsFreeSignName;


    public function __construct()
    {
        $this->config           = config('message.service.Alidayu');
        $this->url              = $this->config['sendUrl'];
        $this->appKey           = $this->config['appKey'];
        $this->secretKey        = $this->config['secretKey'];
        $this->smsFreeSignName  = $this->config['smsFreeSignName'];
        $this->method           = 'alibaba.aliqin.fc.sms.num.send';
        $this->apiVersion       = '2.0';
        $this->format           = 'json';
        $this->signMethod       = 'md5';
    }

    /**
     * Generate sign
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

    public function createParams(array $params)
    {
        // System params
        $sysParams['method']        = $this->method;
        $sysParams['app_key']       = $this->appKey;
        $sysParams['v']             = $this->apiVersion;
        $sysParams['format']        = $this->format;
        $sysParams['sign_method']   = $this->signMethod;
        $sysParams['timestamp']     = date('Y-m-d H:i:s');

        // Api request params
        $apiParams['sms_type']           = 'normal';
        $apiParams['rec_num']            = $data['mobile'];
        $apiParams['sms_template_code']  = $data['templateId'];
        $apiParams['sms_free_sign_name'] = $this->smsFreeSignName;
        $apiParams['sms_param']          = $this->getTempDataString($data);

        $sysParams['sign'] = $this->generateSign(array_merge($sysParams, $apiParams));

        return array_merge($sysParams, $apiParams);
    }

    public function request(array $params)
    {
        $params = $this->createParams($params);
        $result = $this->curl($this->url, $params, true);
        return $result;
    }

    public function response(array $params)
    {
        $callBackName = $this->genResponseName($this->method);

        $response = $this->request($params);
        if ($response['request']) {
            $result = json_decode($response['response'], true);
            if (isset($result[$callBackName]['result'])) {
                $result = $result[$callBackName]['result'];
            } elseif (isset($result['error_response'])) {
                $result = $result['error_response'];
            } else {
                $result = '请求失败';
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

    protected function genResponseName($method)
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