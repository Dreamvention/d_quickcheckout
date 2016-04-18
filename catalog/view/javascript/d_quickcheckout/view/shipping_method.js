qc.ShippingMethodView = qc.View.extend({
	initialize: function(e){
		this.template = e.template;
		qc.event.bind("update", this.update, this);
		this.model.on("change", this.render, this);
		this.render();

	},
	
	events: {
		'change input[type=radio]': 'updateShippingMethod',
		'change select': 'updateShippingMethod',
	},

	template: '',

	updateShippingMethod: function(e){

		this.model.update($('#shipping_method_form').serializeArray());
		if(parseInt(config.general.analytics_event)){
			ga('send', 'event', config.name, 'update', 'shipping_method.'+e.currentTarget.value);
		}
		preloaderStart();
	},

	render: function(){
		this.focusedElementId = $(':focus').attr('id');
		$(this.el).html(this.template({'model': this.model.toJSON()}));
		$('#' + this.focusedElementId).focus();
	},

	update: function(data){
		console.log('shipping_method:render');
		if(data.shipping_method){
			this.model.set('shipping_method', data.shipping_method);

		}

		if(data.shipping_methods){
			this.model.set('shipping_methods', data.shipping_methods);
		}

		if(typeof(data.show_shipping_method) !== 'undefined'){
			this.model.set('show_shipping_method', data.show_shipping_method);
		}

		if(data.shipping_error){
			this.model.set('shipping_error', data.shipping_error);
		}
		
		if(data.account && data.account !== this.model.get('account')){
			this.changeAccount(data.account);
		}

		if(data.shipping_method_error){
			this.model.set('error', data.shipping_method_error);
		}
	},

});
