qc.Login = qc.Model.extend({
	defaults: '',

	initialize: function(){
		this.set('config', config.account[this.get('account')].login);
	},

	changeAccount: function(account){
		this.set('account', account);
	},

	loginAccount: function(json){
		this.set(json);
		var that = this;
		$.post('index.php?route=d_quickcheckout/login/loginAccount', json, function(data) {
            if(data.login_error){
                  that.updateForm(data);
            }else{
            	if(parseInt(config.general.login_refresh)){ 
					window.location.reload();
				}else{
                    $.post('index.php?route=d_quickcheckout/login/updateAll', json, function(data) {
                        that.updateForm(data);
                    }, 'json').error(
                    );
                }
			}
		}, 'json').error(
		);
	},

	updateAccount: function(account){
		this.set('account', account);
		this.set('config', config.account[this.get('account')].login);
		var json = this.toJSON();
		var that = this;
		$.post('index.php?route=d_quickcheckout/login/updateAccount', { 'account' : json.account }, function(data) {
			that.updateForm(data);
		}, 'json').error(
		);
	},

	

	

});
