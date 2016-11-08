<?php

namespace acfunpro\afmsgsender;


use dekuan\vdata\CConst;


/**
 *	Constants
 */
class CSMSSenderConst
{
	//	...
	const MSGSENDER_ERROR_FAILED		= CConst::ERROR_USER_START + 50;

	const MSGSENDER_ERROR_MOBILE_NUM	= CConst::ERROR_USER_START + 100;

	const SERVICE_CONFIG_ERROR			= CConst::ERROR_USER_START + 150;

	const TEMPLATE_CONFIG_ERROR			= CConst::ERROR_USER_START + 200;
}