<?php

namespace acfunpro\afmsgsender;

use dekuan\vdata\CConst;

class MessageConst
{
	const MOBILE_FORMAT_ERROR		= CConst::ERROR_USER_START + 50;

	const MISSING_PARAMETER			= CConst::ERROR_USER_START + 51;

	const TEMPLATE_TYPE_ERROR		= CConst::ERROR_USER_START + 52;

	const AGENT_CONFIG_NOT_FOUND	= CConst::ERROR_USER_START + 53;

	const TEMPLATE_CONFIG_NOT_FOUND = CConst::ERROR_USER_START + 54;

	const MESSAGE_SEND_FAILED		= CConst::ERROR_USER_START + 55;

	const PARAMETER_NOT_EMPTY		= CConst::ERROR_USER_START + 56;

	const MSGSENDER_ERROR_FAILED	= CConst::ERROR_USER_START + 57;

	const INVALID_APIKEY			= CConst::ERROR_USER_START + 58;
}