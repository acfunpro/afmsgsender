<?php

namespace acfunpro\afmsgsender;

use Mail;
use dekuan\delib\CLib;
use acfunpro\afmsgsender\Helper;
use acfunpro\afmsgsender\EmailConst;

class EmailSender
{
	public function sender($arrInput)
	{
		$sEmail  = isset($arrInput['email']) ? $arrInput['email'] : '';
		$sType	 = isset($arrInput['type']) ? $arrInput['type'] : '';
		$uName	 = isset($arrInput['username']) ? $arrInput['username'] : '';

		if (empty($sEmail) || empty($sType) || empty($uName))
			return Helper::response(EmailConst::MISSING_PARAMETER, trans('email.missing_parameter_desc'));

		if (! filter_var($sEmail, FILTER_VALIDATE_EMAIL))
			return Helper::response(EmailConst::EMAIL_FORMAT_ERROR, trans('email.email_format_error_desc'));

		if (! array_key_exists($sType, config('sendemail')))
			return Helper::response(EmailConst::TEMPLATE_TYPE_ERROR, trans('email.template_type_error_desc'));

		// 邮件标题
		$subject  = config('sendemail')[$sType]['subject'];
		// 邮件模板
		$template = 'emails.' . config('sendemail')[$sType]['template'];

		$route 		= 'http://www.acfun.tv/resetpwd/';
		$token		= base64_encode($sEmail . $uName . time());
		$verifyurl	= $route . $token;
		$sendTime   = date('Y-m-d H:i:s');

   		$result = Mail::send(
								$template,
								[
									'username' => $uName,
									'reseturl' => $verifyurl
								],
								function ($m) use ($sEmail, $uName, $subject) {
									$m->to($sEmail, $uName)->subject($subject);
								}
							);

		if (1 !== $result) {

			Helper::emailLog($sEmail, $sType, 2, $sendTime);

			return Helper::response(EmailConst::EMAIL_SEND_FAILED, trans('email.email_send_failed_desc'));
		}
		else
		{

			Helper::emailLog($sEmail, $sType, 1, $sendTime);

			return Helper::response(CConst::STATUS_OKAY, trans('email.email_send_success_desc'));

		}

	}
}