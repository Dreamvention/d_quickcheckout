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

                //in further need add payment and shipping methods content(architecture) due deep-merge can't properly merge with empty content
                if(getSession().payment_address.shipping_address == 1){ 
                    this.updateState(['session', 'shipping_methods'],json.session.shipping_methods)
                    this.updateState(['session', 'payment_methods'],json.session.payment_methods)
                }else{
                   this.updateState(['session', 'payment_methods'],json.session.payment_methods);
               }
               //
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