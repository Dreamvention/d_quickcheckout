qc.ShippingAddressView = qc.View.extend({
	initialize: function(e){
		this.template = e.template;
		qc.event.bind("update", this.update, this);
		this.render();
	},

	events: {
		'change input[type=radio].shipping-address': 'changeAddress',
		'change select.country_id': 'changeCountry',
	},

	template: '',

	fields: '',

	changeAccount: function(account){
		if(this.model.get('account') !== account){
			this.model.changeAccount(account);
			this.render();
		}
	},

	changeAddress: function(e){
		this.model.changeAddress(e.currentTarget.value);
		this.render();
		if(parseInt(config.general.analytics_event)){
			ga('send', 'event', config.name, 'update', 'shipping_address.changeAddress');
		}
		preloaderStart();
	},

	changeCountry: function(e){
		this.model.set('shipping_address.zone_id', 0);
		this.setZone(e.currentTarget.value);
		if(parseInt(config.general.analytics_event)){
			ga('send', 'event', config.name, 'update', 'shipping_address.changeCountry');
		}
		preloaderStart();

	},

	setZone: function(country_id){
		var that = this;
		$.post('index.php?route=d_quickcheckout/field/getZone', { country_id : country_id }, function(data) {
			that.model.set('config.fields.zone_id.options', data);
			that.render();
		}, 'json');
	},

	update: function(data){
		if(data.shipping_address){
			this.model.set('shipping_address', data.shipping_address);
		}

		if(typeof(data.show_shipping_address) !== 'undefined' && data.show_shipping_address !== this.model.get('show_shipping_address')){
			this.model.set('show_shipping_address', data.show_shipping_address);
			this.render();
		}

		if(data.addresses){
			this.model.set('addresses', data.addresses);
			this.render();
		}

		if(typeof(data.account) !== 'undefined' && data.account !== this.model.get('account')){
			this.changeAccount(data.account);
			this.setZone(this.model.get('shipping_address.country_id'));
		}

		if(data.shipping_address_refresh){
			this.render();
		}
	},

	render: function(){
		this.focusedElementId = $(':focus').attr('id');
		console.log('shipping_address:render');
		$(this.el).html(this.template({'model': this.model.toJSON()}));
		this.fields = $.extend(true, {}, new qc.FieldView({el:$("#shipping_address_form"), model: this.model, template: _.template($("#field_template").html())}));
		this.fields.render();
		$('#' + this.focusedElementId).focus();
	},
});