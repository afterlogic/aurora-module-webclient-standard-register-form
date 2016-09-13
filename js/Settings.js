'use strict';

var
	Types = require('%PathToCoreWebclientModule%/js/utils/Types.js')
;

module.exports = {
	ServerModuleName: '%ModuleName%',
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
			this.ServerModuleName = Types.pString(oAppDataSection.ServerModuleName);
			this.HashModuleName = Types.pString(oAppDataSection.HashModuleName);
			
			this.CustomLogoUrl = Types.pString(oAppDataSection.CustomLogoUrl);
			this.InfoText = Types.pString(oAppDataSection.InfoText);
		}
	}
};