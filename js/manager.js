'use strict';


module.exports = function (oAppData) {
	var
		_ = require('underscore'),
		
		App = require('%PathToCoreWebclientModule%/js/App.js'),
		
		Settings = require('modules/%ModuleName%/js/Settings.js'),
		oSettings = _.extend({}, oAppData[Settings.ServerModuleName] || {}, oAppData['%ModuleName%'] || {}),
		
		bAnonimUser = App.getUserRole() === Enums.UserRole.Anonymous
	;
	
	Settings.init(oSettings);
	
	if (!App.isPublic() && bAnonimUser)
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
