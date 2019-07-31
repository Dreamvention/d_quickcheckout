/**
 *   PaymentAddress Model
 */

(function() {


    this.subscribe('payment_address/update', function(data) {
        clearTimeout(this.payment_address_timer);

        this.setState({ 'session': data });
        var difference = this.getChange();

        this.payment_address_timer = setTimeout(function() {
            this.send('extension/d_quickcheckout/payment_address/update', difference, function(json) {
                this.setState(json);
                this.setChange(this.getState());
            }.bind(this));
        }, 10);

    });

    this.subscribe('payment_address/error', function(data) {
        var state = { 'errors': { 'payment_address': {} } }
        state['errors']['payment_address'][data.field_id] = data.error;

        this.setState(state);
    });

    this.subscribe('payment_address/edit', function(data) {
        this.setState(data);
    });


})(qc);