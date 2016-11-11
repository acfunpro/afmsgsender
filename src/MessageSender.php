<?php

namespace acfunpro\afmsgsender;

use dekuan\delib\CLib;
use acfunpro\afmsgsender\Helper;
use acfunpro\afmsgsender\MessageConst;

class MessageSender
{
    /**
     * 服务商实例
     * @var null
     */
    protected static $agent = null;

    /**
     * 服务商配置信息
     * @var array
     */
    protected static $agentConfig = [];

    /**
     * 短信模板配置信息
     * @var array
     */
    protected static $templateConfig = [];

    public function __construct()
    {
        self::_initAgentConfig(config('message.agent', []));
        self::_initTemplateConfig(config('message.template', []));
    }

    /**
     * 加载服务商配置信息
     * @param array $agent
     * @return array
     */
    private static function _initAgentConfig(array $agent)
    {
        if (!is_array($agent) || !count($agent))
            return Helper::response(MessageConst::AGENT_CONFIG_NOT_FOUND, trans('message.agent_config_not_found_desc'));

        self::$agentConfig = $agent;
    }

    /**
     * 加载模板配置信息
     * @param array $template
     * @return array
     */
    private static function _initTemplateConfig(array $template)
    {
        if (!is_array($template) || !count($template))
            return Helper::response(MessageConst::TEMPLATE_CONFIG_NOT_FOUND, trans('message.template_config_not_found_desc'));

        self::$templateConfig = $template;
    }

    /**
     *
     * @param $name
     * @return string
     */
    protected function useAgent($name)
    {
        $class = 'acfunpro\\afmsgsender\\agents\\' . $name . 'Agent';

        if (null !== self::$agent && self::$agent instanceof $class)
            return self::$agent;

        return self::$agent = new $class;
    }



    public function sender($arrInput)
    {
        $sMobile = isset($arrInput['mobile']) ? $arrInput['mobile'] : '';
        $sApiKey = isset($arrInput['apikey']) ? $arrInput['apikey'] : '';
        $sType   = isset($arrInput['type']) ? $arrInput['type'] : '';
        $sCode   = isset($arrInput['code']) ? $arrInput['code'] : '';

        if (empty($sMobile) || empty($sType) || empty($sCode) || empty($sApiKey))
            return Helper::response(MessageConst::MISSING_PARAMETER, trans('message.missing_parameter_desc'));

        if (!CLib::IsValidMobile($sMobile, true))
            return Helper::response(MessageConst::MOBILE_FORMAT_ERROR, trans('message.mobile_format_error_desc'));

        if (strtolower($sApiKey) != config('message.apikey'))
             return Helper::response(MessageConst::INVALID_APIKEY, trans('message.invalid_apikey_desc'));

        if (!array_key_exists($sType, config('message.template')))
            return Helper::response(MessageConst::TEMPLATE_TYPE_ERROR, trans('message.template_type_error_desc'));

        $sendTime = date('Y-m-d H:i:s');
        $wParam['mobile']  = $sMobile;
        $wParam['code']    = $sCode;
        $wParam['time']    = 5;
        $wParam['content'] = sprintf(self::$templateConfig[$sType]['content'], $sCode);

        // use Welink agent
        $this->useAgent('Welink');
        $result = self::$agent->response($wParam);

        if ($result['State'] != 0) // Welink服务商发送失败
        {
            // 记录发送失败信息
            Helper::messageLog($wParam['mobile'], $sCode, 2, 1, $result['MsgState'], $sendTime);

            return Helper::response(MessageConst::MESSAGE_SEND_FAILED, trans('message.message_send_failed_desc'));
            /**
            | //////////////////////////////////////////////
            |   切换阿里大于服务商
            | //////////////////////////////////////////////
            $aParam['mobile']  = $sMobile;
            $aParam['code']    = $sCode;
            $aParam['time']    = 5;

            // use Alidayu agent
            $this->useAgent('Alidayu');
            $result = self::$agent->response($aParam);

            if (!isset($result['err_code'])) // oh my god! Alidayu send fail!
            {
                //--------------------------------------------------
                // 异常信息示例
                // "code"        => 15
                // "msg"         => "Remote service error"
                // "sub_code"    => "isv.BUSINESS_LIMIT_CONTROL"
                // "sub_msg"     => "触发业务流控"
                // "request_id"  => "ejgl131ffr00"
                //--------------------------------------------------

                Helper::smslog($aParam['mobile'], $sCode, 2, 2, $result['sub_msg'], $sendTime);

                return Helper::response(MessageConst::MESSAGE_SEND_FAILED, trans('message.message_send_failed_desc'));
            }
            else
            {
                Helper::smslog($aParam['mobile'], $sCode, 1, 2, 'success', $sendTime);

                return Helper::response(CConst::STATUS_OKAY, trans('message.message_send_success_desc'));
            }
            //-----------------------------------------------------------
            */
        }
        else
        {
            Helper::messageLog($wParam['mobile'], $sCode, 1, 1, $result['MsgState'], $sendTime);

            return Helper::response(CConst::STATUS_OKAY, trans('message.message_send_success_desc'));
        }

    }
}