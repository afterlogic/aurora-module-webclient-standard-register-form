<?php
/**
 * This code is licensed under AGPLv3 license or Afterlogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\StandardRegisterFormWebclient;

use Aurora\System\SettingsProperty;

/**
 * @property bool $Disabled
 * @property string $ServerModuleName
 * @property string $HashModuleName
 * @property string $CustomLogoUrl
 * @property string $InfoText
 * @property string $BottomInfoHtmlText
 */

class Settings extends \Aurora\System\Module\Settings
{
    protected function initDefaults()
    {
        $this->aContainer = [
            "Disabled" => new SettingsProperty(
                false,
                "bool",
                null,
                "Setting to true disables the module",
            ),
            "ServerModuleName" => new SettingsProperty(
                "StandardRegisterFormWebclient",
                "string",
                null,
                "Defines name of the module responsible for registration page",
            ),
            "HashModuleName" => new SettingsProperty(
                "register",
                "string",
                null,
                "Defines URL hash used by the module",
            ),
            "CustomLogoUrl" => new SettingsProperty(
                "",
                "string",
                null,
                "Defines URL of logo image used on registration page",
            ),
            "InfoText" => new SettingsProperty(
                "",
                "string",
                null,
                "Defines additional text message shown on registration page",
            ),
            "BottomInfoHtmlText" => new SettingsProperty(
                "",
                "string",
                null,
                "Defines bottom text message shown on registration page",
            ),
        ];
    }
}
