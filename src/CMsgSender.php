<?php

namespace acfunpro\afmsgsender;

use dekuan\delib\CLib;
use dekuan\vdata\CConst;
use dekuan\vdata\CRequest;

class CMsgSender extends CMsgSenderConst
{
	const DEFAULT_SERVICE_TIMEOUT 	= 5;

	const DEFAULT_SERVICE_VERSION 	= '1.0';

	const DEFAULT_SERVICE_URL 		= 'http://msgsender.service.acfun.tv/sendmsg';

	private $m_sVersion;
	private $m_sServiceUrl;
	private $m_nTimeOut;
	private static $g_cStaticInstance;

	public function __construct()
	{
		$this->m_sVersion			= self::DEFAULT_SERVICE_VERSION;
		$this->m_sServiceUrl 		= self::DEFAULT_SERVICE_URL;
		$this->m_nTimeOut 			= self::DEFAULT_SERVICE_TIMEOUT;
	}

	public static function GetInstance()
	{
		if ( is_null( self::$g_cStaticInstance ) || ! isset( self::$g_cStaticInstance ) )
		{
			self::$g_cStaticInstance = new self();
		}
		return self::$g_cStaticInstance;
	}


	//
	//	Set services url
	//
	public function SetServiceUrl( $sUrl )
	{
		//
		//	sUrl	- [in] string	The new services url
		//	RETURN	- boolean
		//
		$bRet	= false;

		if ( CLib::IsExistingString( $sUrl ) )
		{
			$bRet = true;
			$this->m_sServiceUrl = $sUrl;
		}

		return $bRet;
	}


	//
	//	Set services calling timeout
	//
	public function SetServiceTimeout( $nTimeout )
	{
		//
		//	$nTimeout	- [in] int,	timeout in seconds
		//	RETURN		- boolean
		//
		$bRet	= false;

		if ( is_numeric( $nTimeout ) && $nTimeout > 0 )
		{
			$bRet = true;
			$this->m_nTimeOut = $nTimeout;
		}

		return $bRet;
	}

	//
	// Send verify code
	//
	public function SendMessage( $sMobileNumber, $sMessage, $sApiKey )
	{
		//
		// sMobileNumber	-[in] string
		// sMessage 		-[in] string
		// sApiKey 			-[in] string
		//
		$nRet		= CConst::ERROR_UNKNOWN;
		$cRequest	= CRequest::GetInstance();

		//
		// Verify parameter
		//
		if ( ! CLib::IsExistingString( $sMobileNumber ) )
		{
			return CConst::ERROR_PARAMETER;
		}
		if ( ! CLib::IsExistingString( $sMessage ) )
		{
			return CConst::ERROR_PARAMETER;
		}
		if ( ! CLib::IsExistingString( $sApiKey ) )
		{
			return CConst::ERROR_PARAMETER;
		}

		try
		{
			if ( CLib::IsValidMobile( $sMobileNumber, true ) )
			{
				$arrResponse	= [];
				$arrPostData	= [
					'url'		=> $this->m_sServiceUrl,
					'version'	=> $this->m_sVersion,
					'timeout'	=> $this->m_nTimeOut,
					'data'		=> [
						'mobile'	=>	strval( $sMobileNumber ),
						'message'	=>	$sMessage,
						'apikey'	=>	$sApiKey
					]
				];

				$nRpcCall = $cRequest->Post( $arrPostData, $arrResponse );
				if ( CConst::ERROR_SUCCESS == $nRpcCall )
				{
					if ( $cRequest->IsValidVData( $arrResponse ) )
					{
						$nRet = $arrResponse['errorid'];
					}
					else
					{
						$nRet = CConst::ERROR_JSON;
					}
				}
				else
				{
					$nRet = $nRpcCall;
				}
			}
			else
			{
				$nRet = self::MSGSENDER_ERROR_MOBILE_NUM;
			}
		}
		catch ( \Exception $e )
		{
			$nRet = CConst::ERROR_EXCEPTION;
		}

		return $nRet;
	}

}