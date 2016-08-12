'use strict';

var
	Types = require('%PathToCoreWebclientModule%/js/utils/Types.js')
;

module.exports = {
	ServerModuleName: 'StandardRegisterFormWebclient',
	HashModuleName: 'register',
	
	CustomLogoUrl: '',
	InfoText: '',
	
	/**
	 * Initializes settings from AppData section.
	 * 
	 * @param {Object} oAppDataSection contains module settings from server.
	 */
	init: function (oAppDataSection) {
		if (oAppDataSection)
		{
			this.CustomLogoUrl = Types.pString(oAppDataSection.CustomLogoUrl);
			this.InfoText = Types.pString(oAppDataSection.InfoText);
		}
	}
};