<?php
/**
 * @copyright Copyright (c) 2016, Afterlogic Corp.
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 * 
 * @package Modules
 */

class StandardRegisterFormWebclientModule extends AApiModule
{
	protected $aSettingsMap = array(
		'ServerModuleName' => array('StandardRegisterFormWebclient', 'string'),
		'HashModuleName' => array('register', 'string'),
		'CustomLogoUrl' => array('', 'string'),
		'InfoText' => array('', 'string'),
	);
	
	/***** public functions might be called with web API *****/
	/**
	 * Obtains list of module settings for authenticated user.
	 * 
	 * @return array
	 */
	public function GetSettings()
	{
		\CApi::checkUserRoleIsAtLeast(\EUserRole::Anonymous);
		
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
	 * @param int $UserId Identifier of user which will contain new account.
	 * @return array
	 * @throws \System\Exceptions\AuroraApiException
	 */
	public function Register($Login, $Password, $UserId)
	{
		\CApi::checkUserRoleIsAtLeast(\EUserRole::Anonymous);
		
		if (empty($UserId))
		{
			\CApi::$__SKIP_CHECK_USER_ROLE__ = true;
	
			$UserId = \CApi::GetModuleDecorator('Core')->CreateUser(0, $Login);
			
			\CApi::$__SKIP_CHECK_USER_ROLE__ = false;
		}

		if (empty($UserId))
		{
			throw new \System\Exceptions\AuroraApiException(\System\Notifications::InvalidInputParameter);
		}
		
		$mResult = false;
		
		$refArgs = array (
			'Login' => $Login,
			'Password' => $Password,
			'UserId' => $UserId,
		);
		$this->broadcastEvent(
			'Register', 
			$refArgs,
			$mResult
		);
		
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
	/***** public functions might be called with web API *****/
}
