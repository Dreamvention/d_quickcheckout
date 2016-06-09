qc.Model = Backbone.NestedModel.extend({
	defaults: '',

	initialize: function(){
		console.log("Engine Model start");
	},

	isJson: function(str) {
		try {
			JSON.parse(str);
		} catch (e) {
			return false;
		}
		return true;
	},

	updateForm: function(data){
		//stop preloader
		preloaderStop();

		if(parseInt(config.general.debug)){
			console.log(data);
		}

		qc.event.trigger('update', data);

		if(data.redirect){
			window.location = data.redirect;
		}

		if(data.account){
			//console.log('updateForm: changeAccount');
			qc.event.trigger('changeAccount', data.account);
		}

		if(data.login_error){
			//console.log('updateForm: login_error');
			qc.login.set('error', data.login_error);
		}

		if(data.error){
			//console.log('updateForm: error');
			alert(data.error);
		}



	},



});
