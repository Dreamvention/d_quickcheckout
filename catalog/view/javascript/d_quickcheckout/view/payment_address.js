qc.PaymentAddressView = qc.View.extend({
	initialize: function(e){
		this.template = e.template;
		qc.event.bind("update", this.update, this);
		this.render();
	},

	events: {
		'change input[type=radio].payment-address': 'changeAddress',
		'change select.country_id': 'changeCountry',
		'change select.zone_id': 'zoneDelay'
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
			ga('send', 'event', config.name, 'update', 'payment_address.changeAddress');
		}
		preloaderStart();
	},

	changeCountry: function(e){
		if(e.currentTarget.value !== ''){
			this.model.set('shipping_address.zone_id', 0);
			this.setZone(e.currentTarget.value);
			if(parseInt(config.general.analytics_event)){
				ga('send', 'event', config.name, 'update', 'payment_address.changeCountry');
			}
			preloaderStart();
		} else {
			this.model.set('payment_address.zone_id', '');
			this.render();   
		}
	},
	setZone: function(country_id){
		var that = this;
		$.post('index.php?route=d_quickcheckout/field/getZone', { country_id : country_id }, function(data) {
			that.model.set('config.fields.zone_id.options', data);
			that.render();
		}, 'json');
	},

	update: function(data){
		console.log('payment_address:render');
		var render_state = false;
		

		if(typeof(data.shipping_required) !== 'undefined'){
			this.model.set('shipping_required', data.shipping_required);
			//this.render();
			render_state = true;
		}

		if(data.addresses){
			this.model.set('addresses', data.addresses);
			//this.render();
			render_state = true;
		}

		if(data.account && data.account !== this.model.get('account')){
			this.changeAccount(data.account);
			this.setZone(this.model.get('payment_address.country_id'));
		}

		if(data.payment_address){
			this.model.set('payment_address', data.payment_address);
			// render_state = true;
		}

		if(data.payment_address_refresh){			
			//this.render();
			render_state = true;
		}
		if(render_state){
			this.render();
		}
		$("#payment_address_shipping_address").attr("disabled", false);
		$("#payment_address_zone_id").attr("disabled", false);
	},

	shipping_required: function(){
		if(this.model.get('shipping_required')){
			$('#payment_address_shipping_address_input').show();
		} else {
			$('#payment_address_shipping_address_input').hide();
		}
	},

	render: function(){
		this.focusedElementId = $(':focus').attr('id');
		console.log('payment_address:render');
		$(this.el).html(this.template({'model': this.model.toJSON()}));
		this.fields = $.extend(true, {}, new qc.FieldView({el:$("#payment_address_form"), model: this.model, template: _.template($("#field_template").html())}));
		this.fields.render();
		//this.setZone(this.model.get('payment_address.country_id'));
		this.shipping_required();
		$('#' + this.focusedElementId).focus();
	},

	zoneDelay: function(){
		console.log("payment_address:zone_delay");
		$("#payment_address_shipping_address").attr("disabled", true);
	}
});
