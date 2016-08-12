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
		$this->setNonAuthorizedMethods(array('Register'));
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
	 * @param string $Login Login for authentication.
	 * @param string $Password Password for authentication.
	 * 
	 * @return array
	 * 
	 * @throws \System\Exceptions\ClientException
	 */
	public function Register($Login, $Password)
	{
		$mResult = false;

//		$this->broadcastEvent('Register', array(
//			array (
//				'Login' => $Login,
//				'Password' => $Password,
//			),
//			&$mResult
//		));

		return $mResult;
	}
}
