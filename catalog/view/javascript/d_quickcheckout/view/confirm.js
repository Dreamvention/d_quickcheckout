qc.ConfirmView = qc.View.extend({
	initialize: function(e){
		this.template = e.template;
		qc.event.bind("update", this.update, this);
		qc.event.bind("changeAccount", this.changeAccount, this);
		qc.event.bind("paymentConfirm", this.paymentConfirm, this);
		
		//fix lost data
		this.render();
	},

	events: {
		'click button#qc_confirm_order': 'confirm',
	},

	template: '',

	forceConfirm: function(){
		var that = this;
		if(qc.confirmOrderVar == 1){
  			console.log('qc.confirmOrderVar = '+ qc.confirmOrderVar)
			that.confirm();
		}
	},

	confirm: function(){
		
		preloaderStart();
		qc.confirmOrderVar = 0;
		var valid = true;

		if($(".has-error").length){
			valid = false;
			preloaderStop();
			$('html,body').animate({ scrollTop: $(".has-error").offset().top-60}, 'slow');
		}

		if($("#d_quickcheckout #cart_view .alert-danger").length){
			valid = false;
			preloaderStop();
			$('html,body').animate({ scrollTop: $("#d_quickcheckout #cart_view .alert-danger").offset().top-60}, 'slow');
		}

		$("#d_quickcheckout form").each(function(){
			if(!$(this).valid()){
				valid = false;
				preloaderStop();
				$('html,body').animate({ scrollTop: $(".has-error").offset().top-60}, 'slow');
			}
		});

        if(this.model.get('account') == 'register'){
            email = $("#d_quickcheckout #payment_address_form #payment_address_email")
            emailVal = email.val();
            var that = this;
            $.ajax({
                url: "index.php?route=d_quickcheckout/field/validate_email",
                async: false,
                method: 'GET',
                dataType: "json",
                data: 'email='+ emailVal,
                success:function( data ) {
                    if(data != true){
                       valid = false
                       preloaderStop();
                       $('html,body').animate({ scrollTop: $(".has-error").offset().top-60}, 'slow');
                   	}
                   	if(valid){
						that.model.update();
					}
                }
            });
        }else{
        	if(valid){
				this.model.update();
			}
        }

		if(parseInt(config.general.analytics_event)){
			ga('send', 'event', config.name, 'click', 'confirm.confirm');
		}

	},

	paymentConfirm: function(){
		console.log('confirm:paymentConfirm');
		preloaderStart();
		$(document).ajaxStop(function() {
			preloaderStop();
		});
	},

	changeAccount: function(account){

		if(this.model.get('account') !== account){
			this.model.changeAccount(account);
			this.render();
		}
	},

	update: function(data){
		//fix lost data
		//this.model.set('data', data);
		var that = this;

		if(data.confirm){
			this.model.set('confirm', data.confirm);
		}

		if(typeof(data.show_confirm) !== 'undefined'){
			this.model.set('show_confirm', data.show_confirm);
			this.render();
		}

		if(typeof(data.payment_popup) !== 'undefined'){
			this.model.set('payment_popup', data.payment_popup);
			this.render();
		}
		
		if(data.account && data.account !== this.model.get('account')){
			this.changeAccount(data.account);
		}

		that.forceConfirm();
		
	},

	render: function(){
		this.focusedElementId = $(':focus').attr('id');
		console.log('confirm:render');
		$(this.el).html(this.template({'model': this.model.toJSON()}));
		this.fields = new qc.FieldView({el:$("#confirm_form"), model: this.model, template: _.template($("#field_template").html())});
		this.fields.render();
		$('#' + this.focusedElementId).focus();
	},
});
