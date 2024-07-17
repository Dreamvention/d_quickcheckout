/**
 *   ShippingAddress Model
 */

(function() {

    this.subscribe('shipping_address/update', function(data) {
        clearTimeout(this.shipping_address_timer);

        this.setState({ 'session': data }, false);

        var data_to_update = {
            session: {
                shipping_address: this.getSession().shipping_address,
            }
        };

        this.shipping_address_timer = setTimeout(function() {
            this.send('extension/d_quickcheckout/shipping_address/update', data_to_update, function(json) {
                if (json?.session?.payment_methods !== undefined) {
                    this.updateState(['session', 'payment_methods'], json['session']['payment_methods'], false);
                }
                if (json?.session?.shipping_methods !== undefined) {
                    this.updateState(['session', 'shipping_methods'], json['session']['shipping_methods'], false);
                }
                this.setState(json);
                this.setChange(this.getState());
            }.bind(this));
        }, 20);

    });

    this.subscribe('shipping_address/error', function(data) {
        var state = { 'errors': { 'shipping_address': {} } }
        state['errors']['shipping_address'][data.field_id] = data.error;
        this.setState(state);
    });

    /*show/hide shipping address immediately*/
    /* this.subscribe('payment_address/update', function(data) {
        var state = this.getState();
        if (data.payment_address.shipping_address && state.session.has_shipping) {
            if (data.payment_address.shipping_address == 1) {
                this.updateState(['config', this.getAccount(), 'shipping_address', 'display'], 0);
            } else {
                this.updateState(['config', this.getAccount(), 'shipping_address', 'display'], 1);
            }
        } else if (!state.session.has_shipping) {
            this.updateState(['config', this.getAccount(), 'shipping_address', 'display'], 0);
        }
    }); */

    this.subscribe('shipping_address/edit', function(data) {
        this.setState(data);
    });

})(qc);