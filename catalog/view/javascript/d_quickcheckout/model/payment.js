qc.Payment = qc.Model.extend({
	defaults: '',

	initialize: function(){
		this.set('config', config.account[this.get('account')].payment);
	},

	changeAccount: function(account){
		this.set('account', account);
		this.set('config', config.account[this.get('account')].payment);
	},

});
