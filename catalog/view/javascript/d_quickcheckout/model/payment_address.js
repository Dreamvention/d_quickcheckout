qc.PaymentAddress = qc.Model.extend({
	defaults: {
		'payment_address': '',
		'config': '',
		'account': '',
		'addresses': '',
	},
	
	initialize: function(){
		this.set('config', config.account[this.get('account')].payment_address);
	},

	changeAccount: function(account){
		this.set('account', account);
		this.set('config', config.account[this.get('account')].payment_address);
	},

	changeAddress: function(address_id){
		this.set('payment_address.address_id', address_id);

		if( address_id != 'new'){
			this.set('payment_address.shipping_address', 0);
		}
		var json = this.toJSON();
		var that = this;
		$.post('index.php?route=d_quickcheckout/payment_address/update', { 'payment_address' :  json.payment_address }, function(data) {
			that.updateForm(data);

		}, 'json').error(
		);
	},

	updateField: function(name, value){
		this.set(name, value);
		var that = this;
		var json = this.toJSON();
		$.post('index.php?route=d_quickcheckout/payment_address/update', { 'payment_address' : json.payment_address }, function(data) {
			that.updateForm(data);
		}, 'json').error(
		);

	},

	
});
