<?php
namespace acfunpro\afmsgsender;


use dekuan\vdata\CConst;
use dekuan\vdata\CRequest;
use dekuan\delib\CLib;


/**
 *	class of CSMSSender
 */
class CSMSSender extends CSMSSenderConst
{
	//
	//	...
	//
	const CFG_DEFAULT_TIME_OUT		= 5;	//	in seconds

	//	...
	const DEFAULT_SERVICE_URL		= 'http://msgsender.service.acfun.tv/sms';
	const DEFAULT_SERVICE_TIMEOUT	=  5;		//	in seconds
	const DEFAULT_SERVICE_VERSION	=  '1.0';	//	default version


	//
	//	static
	//
	private static $serviceConfig 		= [];
	private static $templateConfig 		= [];
	private static $g_cStaticService 	= null;

	//
	//	private
	//
	private $m_sServiceUrl;
	private $m_sServiceTimeout;


	public function __construct()
	{
		$this->m_sServiceUrl		= self::DEFAULT_SERVICE_URL;
		$this->m_sServiceTimeout	= self::DEFAULT_SERVICE_TIMEOUT;
		$this->_initServiceConfig(config('msgsender.service', []));
		$this->_initTemplateConfig(config('msgsender.template', []));
	}

	//
	//	initialization service Configuration
	//
	private function _initServiceConfig($serviceConfig)
	{
       if (!is_array($serviceConfig) || !count($serviceConfig))
            return CConst::SERVICE_CONFIG_ERROR;

        self::$serviceConfig = $serviceConfig;
	}

	//
	//	initialization template Configuration
	//
	private function _initTemplateConfig($templateConfig)
	{
       if (!is_array($templateConfig) || !count($templateConfig))
            return CConst::TEMPLATE_CONFIG_ERROR;

        self::$templateConfig = $templateConfig;
	}

	/**
	 * Service instance
	 * @param  string $serviceName (Alidayu/Welink)
	 * @return object
	 */
	public static function useService($serviceName)
	{
        $className = 'acfunpro\\afmsgsender\\service\\' . $serviceName . 'Service';

        if (null !== self::$g_cStaticService && self::$g_cStaticService instanceof $className)
            return self::$g_cStaticService;

        return self::$g_cStaticService = new $className;
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
			$this->m_sServiceTimeout = $nTimeout;
		}

		return $bRet;
	}


	//
	//	send verification code
	//
	public function SendVerifyCode( $sMobileNumber, $sCode, $sType, $sApiKey, $sVersion = self::DEFAULT_SERVICE_VERSION )
	{
		//
		//	nChannel	- [in] int	channel
		//	sMobileNumber	- [in] string
		//	sCode		- [in] array	verify code
		//	sType       - [in] string   template type
		//	sApiKey		- [in] string	api key
		//	sVersion	- [in] string	required service version
		//	RETURN		- error id
		//
		$nRet		= CConst::ERROR_UNKNOWN;
		$cRequest	= CRequest::GetInstance();

		if ( ! CLib::IsExistingString( $sMobileNumber ) )
		{
			return CConst::ERROR_PARAMETER;
		}
		if ( ! CLib::IsExistingString( $sCode ) )
		{
			return CConst::ERROR_PARAMETER;
		}
		if ( ! CLib::IsExistingString( $sApiKey ) )
		{
			return CConst::ERROR_PARAMETER;
		}


		//	...
		try
		{
			if ( CLib::IsValidMobile ( $sMobileNumber , true ) )
			{
				$arrResponse	= [];
				$arrPostData	= $this->_GetPostDataByAliDaYu( $sMobileNumber, $sCode, $sType, $sApiKey );
				$sVersion	= ( CLib::IsExistingString( $sVersion, true ) ? trim( $sVersion ) : self::DEFAULT_SERVICE_VERSION );
				$nRpcCall	= $cRequest->Post
				(
					[
						'url'		=> $this->m_sServiceUrl,
						'data'		=> $arrPostData,
						'version'	=> $sVersion,
						'timeout'	=> $this->m_sServiceTimeout,
					],
					$arrResponse
				);
				if ( CConst::ERROR_SUCCESS == $nRpcCall )
				{
					if ( $cRequest->IsValidVData( $arrResponse ) )
					{
						$nRet = $arrResponse[ 'errorid' ];
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

	////////////////////////////////////////////////////////////////////////////////
	//	Private
	//

	//
	//	阿里大鱼数据格式
	//
	private function _GetPostDataByAliDaYu( $sMobileNumber, $sCode, $sType, $sApiKey )
	{
		$param['mobile'] 	 = $sMobileNumber;
		$param['code']	     = $sCode;
		$param['templateId'] = self::$templateConfig[$sType]['templateId'];

		$service = $this->useService('Alidayu');
		$service->response($param);
		return
		[
			'mobile'	=> strval( $sMobileNumber ),
			'code'		=> $sCode,
			'apikey'	=> $sApiKey,
		];
	}

	////////////////////////////////////////////////////////////////////////////////
	//	Private
	//

	//
	//	微网数据格式
	//
	private function _GetPostDataByWeLink( $sMobileNumber, $sType, $sCode, $sApiKey )
	{
		$param['mobile'] = $sMobileNumber;
		$param['content'] = sprintf(self::$templateConfig[$sType]['content'], $sCode);

		$service = $this->useService('Welink');
		$service->response($param);
		return
		[
			'mobile'	=> strval( $sMobileNumber ),
			'code'		=> $sCode,
			'apikey'	=> $sApiKey,
		];
	}
}