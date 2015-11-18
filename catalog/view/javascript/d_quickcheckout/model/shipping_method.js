qc.ShippingMethod = qc.Model.extend({
	defaults: '',

	initialize: function(){
		this.set('config', config.account[this.get('account')].shipping_method);
	},

	changeAccount: function(account){
		this.set('account', account);
		this.set('config', config.account[this.get('account')].shipping_method);
	},

	update: function(json) {
		var that = this;
		$.post('index.php?route=d_quickcheckout/shipping_method/update', json, function(data) {
			that.updateForm(data);
			
		}, 'json').error(
		);
	},

	
});
