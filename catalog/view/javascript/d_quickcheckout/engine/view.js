 qc.View = Backbone.View.extend({
	defaults: '',

	initialize: function(){
        
        
		console.log("Engine View start");
		//qc.event.bind("changeAccount", this.changeAccount, this);
	},

	focusedElementId: $(':focus').attr('id'),

	events: {
        'focus input': "updateFocus"
    },

	changeAccount: function(account){

		if(this.model.get('account') !== account){
			this.model.changeAccount(account);
			this.render();
		}
	},

	isJson: function(str) {
		try {
			JSON.parse(str);
		} catch (e) {
			return false;
		}
		return true;
	},

	
});