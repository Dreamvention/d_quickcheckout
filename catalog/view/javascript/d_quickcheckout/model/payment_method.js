qc.PaymentMethod = qc.Model.extend({
	defaults: '',

	initialize: function(){
		this.set('config', config.account[this.get('account')].payment_method);
	},

	changeAccount: function(account){
		this.set('account', account);
		this.set('config', config.account[this.get('account')].payment_method);
	},

	update: function(json) {
		var that = this;
		$.post('index.php?route=d_quickcheckout/payment_method/update', json, function(data) {
			that.updateForm(data);

		}, 'json').error(
		);
	},

});
