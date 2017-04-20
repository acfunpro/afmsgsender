<?php

namespace acfunpro\afmsgsender;

use dekuan\delib\CLib;
use dekuan\vdata\CConst;
use dekuan\vdata\CRequest;

class CMailSender extends CMsgSenderConst
{
	const DEFAULT_SERVICE_TIMEOUT 	= 5;

	const DEFAULT_SERVICE_VERSION 	= '1.0';

	const DEFAULT_SERVICE_URL 		= 'http://msgsender.service.acfun.cn/sendemail';
	
	const DEFAULT_SERVICE_CC_URL	= 'http://msgsender.service.acfun.cn/sendeccmail';

	private $m_sVersion;
	private $m_sServiceUrl;
	private $m_sServiceCCUrl;
	private $m_nTimeOut;
	private static $g_cStaticInstance;

	public function __construct()
	{
		$this->m_sVersion			= self::DEFAULT_SERVICE_VERSION;
		$this->m_sServiceUrl 		= self::DEFAULT_SERVICE_URL;
		$this->m_sServiceCCUrl		= self::DEFAULT_SERVICE_CC_URL;
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
	//	Set services url
	//
	public function SetServiceCCUrl( $sUrl )
	{
		//
		//	sUrl	- [in] string	The new services url
		//	RETURN	- boolean
		//
		$bRet	= false;

		if ( CLib::IsExistingString( $sUrl ) )
		{
			$bRet = true;
			$this->m_sServiceCCUrl = $sUrl;
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


	public function SendMail( $sMailAddress, $sSubject, $sBody, $sApiKey )
	{
		//
		// sMailAddress			-[in] string
		// sSubject 			-[in] string
		// sBody 				-[in] string
		// sApiKey				-[in] string
		//
		$nRet		= CConst::ERROR_UNKNOWN;
		$cRequest	= CRequest::GetInstance();

		//
		// Verify parameter
		//
		if ( ! CLib::IsExistingString( $sMailAddress ) )
		{
			return CConst::ERROR_PARAMETER;
		}
		if ( ! CLib::IsExistingString( $sSubject ) )
		{
			return CConst::ERROR_PARAMETER;
		}
		if ( ! CLib::IsExistingString( $sBody ) )
		{
			return CConst::ERROR_PARAMETER;
		}

		try
		{
			if ( CLib::IsValidEMail( $sMailAddress, false, true ) )
			{
				$arrResponse	= [];
				$arrPostData	= [
					'url'		=> $this->m_sServiceUrl,
					'version'	=> $this->m_sVersion,
					'timeout'	=> $this->m_nTimeOut,
					'data'		=> [
						'email'		=>  $sMailAddress,
						'subject'	=>	$sSubject,
						'body'		=>	$sBody,
						'apikey'	=> $sApiKey
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
				$nRet = self::EMAILSENDER_ERROR_EMAIL_STR;
			}
		}
		catch ( \Exception $e )
		{
			$nRet = CConst::ERROR_EXCEPTION;
		}

		return $nRet;

	}

	public function SendCCEmail( $sMailAddress, $arrMailAddress, $sSubject, $sBody, $sApiKey )
	{
		//
		// sMailAddress			-[in] string
		// arrMailAddress       -[in] array
		// sSubject 			-[in] string
		// sBody 				-[in] string
		// sApiKey				-[in] string
		//
		$nRet		= CConst::ERROR_UNKNOWN;
		$cRequest	= CRequest::GetInstance();
		$arrMailAddress = (array) $arrMailAddress;

		//
		// Verify parameter
		//
		if ( ! CLib::IsExistingString( $sMailAddress ) )
		{
			return CConst::ERROR_PARAMETER;
		}
		foreach ( $arrMailAddress as $sCCMailAddress )
		{
			if ( ! CLib::IsExistingString( $sCCMailAddress ) && ! CLib::IsValidEMail( $sCCMailAddress, false, true ) )
			{
				return CConst::ERROR_PARAMETER;
			}
		}
		if ( ! CLib::IsExistingString( $sSubject ) )
		{
			return CConst::ERROR_PARAMETER;
		}
		if ( ! CLib::IsExistingString( $sBody ) )
		{
			return CConst::ERROR_PARAMETER;
		}


		try
		{
			if ( CLib::IsValidEMail( $sMailAddress, false, true ) )
			{
				$arrResponse	= [];
				$arrPostData	= [
					'url'		=> $this->m_sServiceCCUrl,
					'version'	=> $this->m_sVersion,
					'timeout'	=> $this->m_nTimeOut,
					'data'		=> [
						'email'		=>  $sMailAddress,
						'cc_emails'	=>	$arrMailAddress,
						'subject'	=>	$sSubject,
						'body'		=>	$sBody,
						'apikey'	=> 	$sApiKey
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
				$nRet = self::EMAILSENDER_ERROR_EMAIL_STR;
			}
		}
		catch ( \Exception $e )
		{
			$nRet = CConst::ERROR_EXCEPTION;
		}

		return $nRet;

	}

}
