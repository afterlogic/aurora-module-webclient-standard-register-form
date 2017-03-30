<?php
/**
 * @copyright Copyright (c) 2017, Afterlogic Corp.
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
 */

namespace Aurora\Modules\StandardRegisterFormWebclient;

/**
 * @package Modules
 */
class Module extends \Aurora\System\Module\AbstractWebclientModule
{
	/***** public functions might be called with web API *****/
	/**
	 * Obtains list of module settings for authenticated user.
	 * 
	 * @return array
	 */
	public function GetSettings()
	{
		\Aurora\System\Api::checkUserRoleIsAtLeast(\EUserRole::Anonymous);
		
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
	 * @throws \Aurora\System\Exceptions\ApiException
	 */
	public function Register($Login, $Password, $UserId)
	{
		\Aurora\System\Api::checkUserRoleIsAtLeast(\EUserRole::Anonymous);
		
		if (empty($UserId))
		{
			\Aurora\System\Api::skipCheckUserRole(true);

			$UserId = \Aurora\System\Api::GetModuleDecorator('Core')->CreateUser(0, $Login);
			
			\Aurora\System\Api::skipCheckUserRole(false);
		}

		if (empty($UserId))
		{
			throw new \Aurora\System\Exceptions\ApiException(\Aurora\System\Notifications::InvalidInputParameter);
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
			$oLoginDecorator = \Aurora\System\Api::GetModuleDecorator('StandardLoginFormWebclient');
			$mResult = $oLoginDecorator->Login($Login, $Password);
			\Aurora\System\Api::getAuthenticatedUserId($mResult['AuthToken']);
		}
		
		if (!empty($Name) && !empty($mResult) && !empty($UserId))
		{
			$oCoreDecorator = \Aurora\System\Api::GetModuleDecorator('Core');
			$oCoreDecorator->UpdateUser($UserId, $Name);
		}
		
		return $mResult;
	}
	/***** public functions might be called with web API *****/
}
