qc.Field = qc.Model.extend({
	defaults: '',

	initialize: function(){
		console.log("Field");
	},

	getFields: function() {
		return this.get("fields");
	},

	getField: function(id) {
		field = _.where(this.getFields(), { "id": id });
		return field[0];
	}

	
});
