<?php

namespace acfunpro\afmsgsender;

use dekuan\vdata\CConst;

class EmailConst
{
	const EMAIL_FORMAT_ERROR		= CConst::ERROR_USER_START + 60;

	const MISSING_PARAMETER			= CConst::ERROR_USER_START + 61;

	const TEMPLATE_TYPE_ERROR		= CConst::ERROR_USER_START + 62;

	const EMAIL_SEND_FAILED			= CConst::ERROR_USER_START + 63;

}