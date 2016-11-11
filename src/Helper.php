<?php

namespace acfunpro\afmsgsender;

use dekuan\vdata\CResponse;
use DB;

class Helper
{
	protected static $instance = null;

	public function __construct()
    {
        if ( null === self::$instance || !isset(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function response($errCode, $errDesc)
    {
        $cResponse = new CResponse();

        return $cResponse->GetVDataResponse($errCode, $errDesc);
    }

    /**
     * 记录发送短信日志
     *
     * @param  string 	 $mobile   手机号
     * @param  integer 	 $code     验证码
     * @param  integer 	 $status   发送状态 1成功 2失败
     * @param  integer 	 $agent    短信服务商
     * @param  string 	 $errorMsg 错误信息
     * @param  timestamp $sendTime 发送时间
     * @return
     */
    public static function messageLog($mobile, $code, $status, $agent, $errorMsg, $sendTime)
    {
    	DB::insert('insert into msgsender_log (mobile, code, isSuccess, agent, errorMsg, sendTime) values (?, ?, ?, ?, ?, ?)', [$mobile, $code, $status, $agent, $errorMsg, $sendTime]);
    }

    /**
     * 记录发送邮件日志
     *
     * @param $email
     * @param $type
     * @param $status
     * @param $sendTime
     */
    public static function emailLog($email, $type, $status, $sendTime)
    {
        DB::insert('insert into sendemail_log (email, type, isSuccess, sendTime) values (?, ?, ?, ?)', [$email, $type, $status, $sendTime]);
    }

}