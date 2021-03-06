<?php

namespace acfunpro\afmsgsender;

use dekuan\vdata\CConst;

class CMsgSenderConst
{
	const MSGSENDER_ERROR_FAILED			= CConst::ERROR_USER_START + 50;

	const MSGSENDER_ERROR_API_KEY			= CConst::ERROR_USER_START + 100;

	const MSGSENDER_ERROR_MOBILE_NUM		= CConst::ERROR_USER_START + 105;

	const MSGSENDER_ERROR_CONTENT_EMPTY		= CConst::ERROR_USER_START + 110;

	const MSGSENDER_ERROR_CONTENT_TOO_LONGER	= CConst::ERROR_USER_START + 115;

	const MSGSENDER_ERROR_SYSTEM			= CConst::ERROR_USER_START + 120;

	const MSGSENDER_ERROR_MOBILE_IN_BLACK		= CConst::ERROR_USER_START + 125;

	const MSGSENDER_ERROR_SEND_TOO_MORE		= CConst::ERROR_USER_START + 130;

	const MSGSENDER_ERROR_SMS_CONNECT		= CConst::ERROR_USER_START + 140;

	const MSGSENDER_ERROR_ACCOUNT_OUTAGE		= CConst::ERROR_USER_START + 145;

	const MSGSENDER_ERROR_ACCOUNT_OVERDUE		= CConst::ERROR_USER_START + 150;

	const MSGSENDER_ERROR_MOBILE_EMPTY		= CConst::ERROR_USER_START + 160;

	const MSGSENDER_ERROR_SEND_LIMIT_PER_MOBILE	= CConst::ERROR_USER_START + 165;

	const MSGSENDER_ERROR_SMS_TIMEOUT		= CConst::ERROR_USER_START + 175;

	const MSGSENDER_ERROR_LOST_TAG			= CConst::ERROR_USER_START + 180;

	const MSGSENDER_ERROR_IP_LIMIT			= CConst::ERROR_USER_START + 185;

	const MSGSENDER_ERROR_CONTENT_ILLEGAL		= CConst::ERROR_USER_START + 190;

	const MSGSENDER_ERROR_CURL_NOT_INSTALL		= CConst::ERROR_USER_START + 170;

	const MSGSENDER_ERROR_CURL_INIT			= CConst::ERROR_USER_START + 175;

	const MSGSENDER_ERROR_CURL_REQUEST_TIMEOUT	= CConst::ERROR_USER_START + 195;

	const MSGSENDER_ERROR_CURL_CONNECT_ERROR	= CConst::ERROR_USER_START + 200;

	const MSGSENDER_ERROR_CURL_POST			= CConst::ERROR_USER_START + 260;

	const MSGSENDER_ERROR_WAY			= CConst::ERROR_USER_START + 220;

	const MSGSENDER_ERROR_TMPLATE			= CConst::ERROR_USER_START + 225;

	const MSGSENDER_ERROR_TMPLATE_LOST_PARA		= CConst::ERROR_USER_START + 235;

	const MSGSENDER_ERROR_MOBILE_NUM_MORE		= CConst::ERROR_USER_START + 230;

	const MSGSENDER_ERROR_GET_CONFIG		= CConst::ERROR_USER_START + 240;

	const MSGSENDER_ERROR_SEND_FREQUENT		= CConst::ERROR_USER_START + 245;

	const EMAILSENDER_ERROR_EMAIL_STR		= CConst::ERROR_USER_START + 250;

	const EMAILSENDER_ERROR_SUBJECT_OR_BODY_EMPTY = CConst::ERROR_USER_START + 255;

	const EMAILSENDER_ERROR_FAILED			= CConst::ERROR_USER_START + 260;

}