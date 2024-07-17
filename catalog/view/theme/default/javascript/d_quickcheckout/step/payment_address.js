/**
 *   PaymentAddress Model
 */

(function() {


    this.subscribe('payment_address/update', function(data) {
        /*show/hide shipping address immediately*/
        if (data.payment_address.shipping_address && this.getState().session.has_shipping) {

            if (data.payment_address.shipping_address == 1) {
                this.updateState(['config', this.getAccount(), 'shipping_address', 'display'], 0, false);
            } else {
                this.updateState(['config', 'guest', 'shipping_address', 'display'], 1, false);
                this.updateState(['config', 'logged', 'shipping_address', 'display'], 1, false);
                this.updateState(['config', 'register', 'shipping_address', 'display'], 1, false);
            }
        } else if (!this.getState().session.has_shipping) {
            this.updateState(['config', this.getAccount(), 'shipping_address', 'display'], 0, false);
        }
        /* end show/hide shipping address immediately*/

        clearTimeout(this.payment_address_timer);

        this.setState({ 'session': data }, false);
        var data_to_update = {
            session: {
                payment_address: this.getSession().payment_address,
            }
        };
        this.payment_address_timer = setTimeout(function() {
            this.send('extension/d_quickcheckout/payment_address/update', data_to_update, function(json) {
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

    this.subscribe('payment_address/error', function(data) {
        var state = { 'errors': { 'payment_address': {} } }
        state['errors']['payment_address'][data.field_id] = data.error;
        this.setState(state);
    });

    this.subscribe('payment_address/edit', function(data) {
        this.setState(data);
    });

    this.subscribe('payment_address/upload_file', function(data) {
        dv_cash('#button-payment-custom-field' + data.id).prop('disabled', true);

        var form_data = new FormData();

        for ( var key in data ) {
            form_data.append(key, data[key]);
        }

        axios.post('index.php?route=tool/upload', form_data).then(function(response){
            var json = response.data;
            dv_cash('#button-payment-custom-field' + data.id).parent().find('.text-danger').remove();

            if (json['error']) {
                dv_cash('#button-payment-custom-field' + data.id).parent().find(dv_cash('#' + data.step + data.field)).after('<div class="text-danger">' + json['error'] + '</div>');
            }

            if (json['success']) {
                alert(json['success']);

                dv_cash('#button-payment-custom-field' + data.id).parent().find(dv_cash('#' + data.step + data.field)).val(json['code']);
                var state = { 'payment_address': {} }
                state['payment_address'][data.field] = json['code'];

                this.dispatch('payment_address/update', state);
            }
            dv_cash('#button-payment-custom-field' + data.id).prop('disabled', false);
        }).catch(function(error) {
            dv_cash('#button-payment-custom-field' + data.id).prop('disabled', false);
            console.error(error)
        });
    });

})(qc);