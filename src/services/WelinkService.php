<?php

namespace acfunpro\afmsgsender\service;

use acfunpro\afmsgsender\service\service;

class WelinkService extends Service
{
    protected $config = [];

    protected $url;

    protected $sname;

    protected $spwd;

    protected $sign;

    protected $scorpid;

    protected $sprdid;

    public function __construct()
    {
        $this->config = config('message.service.Welink');
        $this->url = $this->config['url'];
        $this->sname = $this->config['sname'];
        $this->spwd = $this->config['spwd'];
        $this->sign = $this->config['sign'];
        $this->scorpid = $this->config['scorpid'];
        $this->sprdid = $this->config['sprdid'];
    }

    public function createParams(array $params)
    {
        $params = "sname".$this->sname."&spwd=".$this->spwd."&scorpid=&sprdid=".$this->sprdid."&sdst=".$params['mobile']."&smsg=".rawurldecode($params['content'].$this->sign);

        return $params;
    }

    public function request(array $params)
    {
        $data = $this->createParams($params);

        $url_info = parse_url($this->url);
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader .= "Host:" . $url_info['host'] . "\r\n";
        $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader .= "Content-Length:" . strlen($data) . "\r\n";
        $httpheader .= "Connection:close\r\n\r\n";
        //$httpheader .= "Connection:Keep-Alive\r\n\r\n";
        $httpheader .= $data;
        $fd = fsockopen($url_info['host'], 80);
        fwrite($fd, $httpheader);
        $gets = "";
        while(!feof($fd)) {
            $gets .= fread($fd, 128);
        }
        fclose($fd);
        if($gets != ''){
            $start = strpos($gets, '<?xml ');
            if($start> 0) {
                $gets = substr($gets, $start);
            }
        }

        return $gets;
    }

    public function response(array $params)
    {
        $response = $this->request($params);
        // 禁止引用外部xml实体
        libxml_disable_entity_loader(true);

        $xmlstring = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring),true);
        return $val;
    }

}