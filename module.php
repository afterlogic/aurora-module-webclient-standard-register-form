<?php

class StandardRegisterFormWebclientModule extends AApiModule
{
	protected $aSettingsMap = array(
		'ServerModuleName' => array('StandardRegisterFormWebclient', 'string'),
		'HashModuleName' => array('register', 'string'),
		'CustomLogoUrl' => array('', 'string'),
		'InfoText' => array('', 'string'),
	);

	/**
	 * Initializes Standard Login Form Module.
	 * 
	 * @return array
	 */
	public function init()
	{
		$this->setNonAuthorizedMethods(array('GetAppData', 'Register'));
	}
	
	/**
	 * Obtains settings of the Standard Login Form Module.
	 * 
	 * @return array
	 */
	public function GetAppData()
	{
		return array(
			'ServerModuleName' => $this->getConfig('ServerModuleName', ''),
			'HashModuleName' => $this->getConfig('HashModuleName', ''),
			'CustomLogoUrl' => $this->getConfig('CustomLogoUrl', ''),
			'InfoText' => $this->getConfig('InfoText', ''),
		);
	}
	
	/**
	 * Broadcasts Register event to other modules to log in the system with specified parameters.
	 * 
	 * @param string $Name New name for user.
	 * @param string $Login Login for authentication.
	 * @param string $Password Password for authentication.
	 * @param int $UserId Identificator of user which will contain new account.
	 * 
	 * @return array
	 * 
	 * @throws \System\Exceptions\AuroraApiException
	 */
	public function Register($Name, $Login, $Password, $UserId)
	{
		if (empty($UserId))
		{
			throw new \System\Exceptions\AuroraApiException(\System\Notifications::AccessDenied);
		}
		
		$mResult = false;

		$this->broadcastEvent('Register', array(
			array (
				'Login' => $Login,
				'Password' => $Password,
				'UserId' => $UserId,
			),
			&$mResult
		));
		
		if (!empty($mResult))
		{
			$oLoginDecorator = \CApi::GetModuleDecorator('StandardLoginFormWebclient');
			$mResult = $oLoginDecorator->Login($Login, $Password);
			\CApi::getAuthenticatedUserId($mResult['AuthToken']);
		}
		
		if (!empty($Name) && !empty($mResult) && !empty($UserId))
		{
			$oCoreDecorator = \CApi::GetModuleDecorator('Core');
			$oCoreDecorator->UpdateUser($UserId, $Name);
		}

		return $mResult;
	}
}
