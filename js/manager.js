'use strict';


module.exports = function (oAppData, iUserRole, bPublic) {
	var
		_ = require('underscore'),
		
		Settings = require('modules/%ModuleName%/js/Settings.js'),
		oSettings = _.extend({}, oAppData[Settings.ServerModuleName] || {}, oAppData['%ModuleName%'] || {}),
		
		bAnonimUser = iUserRole === Enums.UserRole.Anonymous
	;
	
	Settings.init(oSettings);
	
	if (!bPublic && bAnonimUser)
	{
		return {
			/**
			 * Returns login view screen.
			 */
			getScreens: function () {
				var oScreens = {};
				oScreens[Settings.HashModuleName] = function () {
					return require('modules/%ModuleName%/js/views/RegisterView.js');
				};
				return oScreens;
			}
		};
	}
	
	return null;
};
