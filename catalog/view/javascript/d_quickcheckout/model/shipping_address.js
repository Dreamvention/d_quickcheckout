
qc.ShippingAddress = qc.Model.extend({
	defaults: {
		'shipping_address': '',
		'config': '',
		'account': '',
		'addresses': '',
		'show_shipping_address': '',
	},

	initialize: function(){
		this.set('config', config.account[this.get('account')].shipping_address);
	},

	changeAccount: function(account){
		this.set('account', account);
		this.set('config', config.account[this.get('account')].shipping_address);
	},

	changeAddress: function(address_id){
		this.set('shipping_address.address_id', address_id);

		var json = this.toJSON();
		var that = this;
		$.post('index.php?route=d_quickcheckout/shipping_address/update', { 'shipping_address' :  json.shipping_address }, function(data) {
			that.updateForm(data);

		}, 'json').error(
		);
	},

	updateField: function(name, value){
		clearTimeout(qc.shipping_address_waiting);
		this.set(name, value);
		var json = this.toJSON();
		var that = this;
		qc.shipping_address_waiting = setTimeout(function () {
			$.post('index.php?route=d_quickcheckout/shipping_address/update', { 'shipping_address' : json.shipping_address }, function(data) {
				that.updateForm(data);

			}, 'json').error(
			);
		}, 500);

	},

	

});
