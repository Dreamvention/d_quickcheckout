/**
*	PaymentMethod Model
*/

(function(){

	this.subscribe('payment_method/update', function(data) {
		this.send('extension/d_quickcheckout/payment_method/update', data, function(json) {
			this.setState(json);
		}.bind(this));
	});

    this.subscribe('payment_method/edit', function(data) {
        this.setState(data);
    });

})(qc);
