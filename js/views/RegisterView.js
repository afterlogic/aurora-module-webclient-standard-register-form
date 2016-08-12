'use strict';

var
	_ = require('underscore'),
	$ = require('jquery'),
	ko = require('knockout'),
	
	TextUtils = require('%PathToCoreWebclientModule%/js/utils/Text.js'),
	UrlUtils = require('%PathToCoreWebclientModule%/js/utils/Url.js'),
	Utils = require('%PathToCoreWebclientModule%/js/utils/Common.js'),
	
	Api = require('%PathToCoreWebclientModule%/js/Api.js'),
	App = require('%PathToCoreWebclientModule%/js/App.js'),
	Browser = require('%PathToCoreWebclientModule%/js/Browser.js'),
	
	CAbstractScreenView = require('%PathToCoreWebclientModule%/js/views/CAbstractScreenView.js'),
	
	Ajax = require('%PathToCoreWebclientModule%/js/Ajax.js'),
	Settings = require('modules/%ModuleName%/js/Settings.js'),
	
	$html = $('html')
;

/**
 * @constructor
 */
function CRegisterView()
{
	CAbstractScreenView.call(this);
	
	this.sCustomLogoUrl = Settings.CustomLogoUrl;
	this.sInfoText = Settings.InfoText;
	
	this.login = ko.observable('');
	this.password = ko.observable('');
	
	this.loginFocus = ko.observable(false);
	this.passwordFocus = ko.observable(false);

	this.loading = ko.observable(false);

	this.canTryRegister = ko.computed(function () {
		return !this.loading();
	}, this);

	this.registerButtonText = ko.computed(function () {
		return this.loading() ? TextUtils.i18n('COREWEBCLIENT/ACTION_REGISTER_IN_PROGRESS') : TextUtils.i18n('COREWEBCLIENT/ACTION_REGISTER');
	}, this);

	this.registerCommand = Utils.createCommand(this, this.register, this.canTryRegister);

	this.shake = ko.observable(false).extend({'autoResetToFalse': 800});
	
	App.broadcastEvent('%ModuleName%::ConstructView::after', {'Name': this.ViewConstructorName, 'View': this});
}

_.extendOwn(CRegisterView.prototype, CAbstractScreenView.prototype);

CRegisterView.prototype.ViewTemplate = '%ModuleName%_RegisterView';
CRegisterView.prototype.ViewConstructorName = 'CRegisterView';

CRegisterView.prototype.onBind = function ()
{
	$html.addClass('non-adjustable-valign');
};

/**
 * Focuses login input after view showing.
 */
CRegisterView.prototype.onShow = function ()
{
	_.delay(_.bind(function(){
		if (this.login() === '')
		{
			this.loginFocus(true);
		}
	},this), 1);
};

/**
 * Checks login input value and sends register request to server.
 */
CRegisterView.prototype.register = function ()
{
	if (!this.loading() && ('' !== $.trim(this.login())))
	{
		var oParameters = {
			'Login': this.login(),
			'Password': this.password()
		};

		this.loading(true);

		Ajax.send('%ModuleName%', 'Register', oParameters, this.onRegisterResponse, this);
	}
	else
	{
		this.loginFocus(true);
		this.shake(true);
	}
};

/**
 * Receives data from the server. Shows error and shakes form if server has returned false-result.
 * Otherwise clears search-string if it don't contain "reset-pass", "invite-auth" and "external-services" parameters and reloads page.
 * 
 * @param {Object} oResponse Data obtained from the server.
 * @param {Object} oRequest Data has been transferred to the server.
 */
CRegisterView.prototype.onRegisterResponse = function (oResponse, oRequest)
{
	if (false === oResponse.Result)
	{
		this.loading(false);
		this.shake(true);
		
		Api.showErrorByCode(oResponse, TextUtils.i18n('COREWEBCLIENT/ERROR_PASS_INCORRECT'));
	}
	else
	{
		
		if (window.location.search !== '' &&
			UrlUtils.getRequestParam('reset-pass') === null &&
			UrlUtils.getRequestParam('invite-auth') === null &&
			UrlUtils.getRequestParam('external-services') === null)
		{
			UrlUtils.clearAndReloadLocation(Browser.ie8AndBelow, true);
		}
		else
		{
			UrlUtils.clearAndReloadLocation(Browser.ie8AndBelow, false);
		}
	}
};

module.exports = new CRegisterView();
