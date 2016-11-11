<?php

namespace acfunpro\afmsgsender;

use dekuan\delib\CLib;
use dekuan\vdata\CRequest;

class SendService
{
	const DEFAULT_SERVICE_TIMEOUT 	= 5;

	const DEFAULT_SERVICE_VERSION 	= '1.0';

	const DEFAULT_SERVICE_URL 		= 'http://msgsender.service.acfun.tv';

	private $_sServiceUrl;
	private $_nServiceTimeOut;

	public function __construct()
	{
		$this->_sServiceUrl 	= self::DEFAULT_SERVICE_URL;
		$this->_nServiceTimeOut = self::DEFAULT_SERVICE_TIMEOUT;
	}

	public function email($email, $username, $type, $method = 'POST')
	{
		$arrResponse = [];

		$arrPostData = [
			'method' 	=> $method,
			'url'	 	=> $this->_sServiceUrl . '/emailsender',
			'data'	 	=> [
				'email' 	=> $email,
				'type' 		=> $type,
				'username' 	=> $username,
			],
			'timeout'	=> self::DEFAULT_SERVICE_TIMEOUT,
			'version'	=> self::DEFAULT_SERVICE_VERSION,
		];

		return $cRequest->Post($arrPostData, $arrResponse);

	}

	public function message($mobile, $code, $type, $apiKey, $method = 'POST')
	{
		$arrResponse = [];

		$arrPostData = [
			'method' 	=> $method,
			'url'	 	=> $this->_sServiceUrl . '/msgsender',
			'data'	 	=> [
				'mobile' 	=> $mobile,
				'code'		=> $code,
				'type' 		=> $type,
				'apikey' 	=> $apiKey,
			],
			'timeout'	=> self::DEFAULT_SERVICE_TIMEOUT,
			'version'	=> self::DEFAULT_SERVICE_VERSION,
		];

		return $cRequest->Post($arrPostData, $arrResponse);

	}

	public function setServiceUrl($sUrl)
	{
		$bRes = false;

		if (CLib::IsExistingString($sUrl))
		{
			$bRes = true;
			$this->_sServiceUrl = $sUrl;
		}

		return $bRes
	}

	public function setSetviceTimeOut($nTimeout)
	{
		$bRes = false;

		if (is_numeric($nTimeout) && $nTimeout > 0)
		{
			$bRes = true;
			$this->_nServiceTimeOut = $nTimeout;
		}

		$return $bRes;
	}

}