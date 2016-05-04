qc.CartView = qc.View.extend({
	initialize: function(e){
		this.template = e.template;
		qc.event.bind("update", this.update, this);
		this.model.on("change", this.render, this);
		this.render();
	},

	events: {
		'click button.increase': 'increaseQuantity',
		'click button.decrease': 'decreaseQuantity',
		'click button.delete': 'deleteQuantity',
		'change input.qc-product-qantity': 'updateQuantity',
		'change input#voucher': 'updateVoucher',
		'change input#coupon': 'updateCoupon',
		'change input#reward': 'updateReward',
	},

	template:'',

	decreaseQuantity: function(e){
		var quantity = $(e.currentTarget).parents('.qc-quantity').find('input.qc-product-qantity');
		this.model.updateQuantity(quantity.attr('name'), parseInt(quantity.val())-1);
		if(parseInt(config.general.analytics_event)){
			ga('send', 'event', config.name, 'click', 'cart.quantity.decrease');
		}
		preloaderStart();
	},

	increaseQuantity: function(e){ 
		var quantity = $(e.currentTarget).parents('.qc-quantity').find('input.qc-product-qantity');
		this.model.updateQuantity(quantity.attr('name'), parseInt(quantity.val())+1);
		if(parseInt(config.general.analytics_event)){
			ga('send', 'event', config.name, 'click', 'cart.quantity.increase');
		}
		preloaderStart();
	},

	deleteQuantity: function(e){
		var quantity = $(e.currentTarget).parents('.qc-quantity').find('input.qc-product-qantity');
		this.model.updateQuantity(quantity.attr('name'), 0);
		if(parseInt(config.general.analytics_event)){
			ga('send', 'event', config.name, 'click', 'cart.quantity.delete');
		}
		preloaderStart();
	},

	updateQuantity: function(e){
		var quantity = $(e.currentTarget);
		this.model.updateQuantity(quantity.attr('name'), parseInt(quantity.val()));
		if(parseInt(config.general.analytics_event)){
			ga('send', 'event', config.name, 'update', 'cart.quantity');
		}
		preloaderStart();
	},

	updateMiniCart: function(total){
		if(parseInt(config.general.update_mini_cart)){
			this.model.updateMiniCart();
		}
	},

	updateVoucher: function(e){
		this.model.updateVoucher($(e.currentTarget).val());
		this.model.set('errors', []);
		this.model.set('successes', []);
		if(parseInt(config.general.analytics_event)){
			ga('send', 'event', config.name, 'update', 'cart.voucher');
		}
		preloaderStart();
	},

	updateCoupon: function(e){
		this.model.updateCoupon($(e.currentTarget).val());
		this.model.set('errors', []);
		this.model.set('successes', []);
		if(parseInt(config.general.analytics_event)){
			ga('send', 'event', config.name, 'update', 'cart.coupon');
		}
		preloaderStart();
	},

	updateReward: function(e){
		this.model.updateReward($(e.currentTarget).val());
		this.model.set('errors', []);
		this.model.set('successes', []);
		if(parseInt(config.general.analytics_event)){
			ga('send', 'event', config.name, 'update', 'cart.reward');
		}
		preloaderStart();
	},

	update: function(data){
		if(data.cart){
			this.model.set('cart', data.cart);
		}

		if(data.products){
			this.model.set('products', data.products);
		}

		if(data.vouchers){
			this.model.set('vouchers', data.vouchers);
		}

		if(data.totals){
			this.model.set('totals', data.totals);
			this.updateMiniCart(data.total);
		}

		if(data.cart_errors){
			this.model.set('errors', data.cart_errors);
		}

		if(typeof(data.cart_error) !== 'undefined'){
			this.model.set('error', data.cart_error);
		}

		if(data.cart_successes){
			this.model.set('successes', data.cart_successes);
		}

		if(typeof(data.show_price) !== 'undefined'){
			this.model.set('show_price', data.show_price);
		}

		if(data.account && data.account !== this.model.get('account')){
			this.changeAccount(data.account);
		}

		if(data.cart_weight){
			this.model.set('cart_weight', data.cart_weight);
		}
	},

	render: function(){
		this.focusedElementId = $(':focus').attr('id');
		console.log('cart:render');
		$(this.el).html(this.template({'model': this.model.toJSON()}));
		$('.qc-product-qantity').each(function(){
			$(this).mask($(this).attr('data-mask'),{placeholder:""});
		});
		$('#' + this.focusedElementId).focus();
	},

});
