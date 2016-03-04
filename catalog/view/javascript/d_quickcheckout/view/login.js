qc.LoginView = qc.View.extend({
	initialize: function(e){
		Backbone.View.prototype.initialize.call(this);
		this.template = e.template;
		qc.event.bind("changeAccount", this.changeAccount, this);
		this.model.on("change", this.render, this);
		this.render();
	},

	events: {
		'change input[name=account]': 'updateAccount',
		'click button#button_login': 'loginAccount'
	},

	template: '',

	render: function(){
		this.focusedElementId = $(':focus').attr('id');
		console.log('login:render');
		$(this.el).html(this.template({'model' : this.model.toJSON() } ));
		$('body').on('click', function(){
			$('#login_button_popup').removeClass('active');
		})
		$('#' + this.focusedElementId).focus();
	},

	changeAccount: function(account){

		if(this.model.get('account') !== account){
			this.model.changeAccount(account);
			this.render();
		}
	},

	updateAccount: function(e){
		this.model.updateAccount(e.currentTarget.value);
		if(parseInt(config.general.analytics_event)){
			ga('send', 'event', config.name, 'click', e.currentTarget.name);
		}
		preloaderStart();
	},

	loginAccount: function(e){
		$('#login_model').modal('hide');
		$('body').removeClass('modal-open');
		$('.modal-backdrop').fadeOut();

		var json = $('#login_form').serializeObject();

		this.model.loginAccount(json);

		if(parseInt(config.general.analytics_event)){
			ga('send', 'event', config.name, 'click', 'login');
		}
		preloaderStart();
		return false;
	},

});