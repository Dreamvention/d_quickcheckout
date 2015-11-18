qc.PaymentView = qc.View.extend({
	initialize: function(e){
		this.template = e.template;
		qc.event.bind("update", this.update, this);
		qc.event.bind("paymentConfirm", this.paymentConfirm, this);
		this.model.on("change", this.render, this);
		this.render();
	},

	template: '',

	paymentConfirm: function() {
		$('.alert').remove();
		if(Number(this.model.get('payment_payment_popup'))){
			$('#payment_modal').modal('show');
		}else{
			//console.log(this.model.get('trigger'));
			var href = $(this.model.get('trigger'), this.$el).attr('href');
			
			if(href != '' && href != undefined) {
				//console.log('clicked link with href='+href);
				document.location.href = href;
			}else{
				//console.log('clicked button');
				$(this.model.get('trigger'), this.$el).click();
			}
		}
		
	},

	update: function(data){
		if(data.payment){
			this.model.set('payment', data.payment);
		}

		if(data.payment_payment_title){
			this.model.set('payment_payment_title', data.payment_payment_title);
		}

		if(typeof(data.payment_payment_popup) !== 'undefined'){
			this.model.set('payment_payment_popup', data.payment_payment_popup);
		}

		if(data.account && data.account !== this.model.get('account')){
			this.changeAccount(data.account);
		}
	},

	render: function(){
		$(this.el).html(this.template({'model': this.model.toJSON()}));

	},


});
