/**
 *   PaymentAddress Model
 */

(function() {


    this.subscribe('payment_address/update', function(data) {
        const version = this.getState().opencart_version;
        if (version > '4.0.1.1') {
            var url = 'extension/ajax_quick_checkout/ajax_quick_checkout/payment_address.update';
        } else {
            var url = 'extension/ajax_quick_checkout/ajax_quick_checkout/payment_address|update';
        }
        //show/hide shipping address immediately
        if (data.payment_address?.shipping_address && this.getState().session.has_shipping) {

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
        // end show/hide shipping address immediately

        clearTimeout(this.payment_address_timer);

        this.setState({ 'session': data }, false);
        var data_to_update = {
            session: {
                payment_address: this.getSession().payment_address,
            }
        };
        this.payment_address_timer = setTimeout(function() {
            this.send(url, data_to_update, function(json) {
                //console.log(json);
                if (json?.session?.shipping_methods !== undefined) {
                    this.updateState(['session', 'shipping_methods'], json?.session?.shipping_methods, false);
                }
                if (json?.session?.payment_methods !== undefined) {
                    this.updateState(['session', 'payment_methods'], json?.session?.payment_methods, false);
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
        document.getElementById('button-payment-custom-field' + data.id).disabled = true;
        
        axios.post('index.php?route=tool/upload', data).then(function(response){
            document.getElementById('button-payment-custom-field' + data.id).parentNode.querySelector('.text-danger').remove();
            var json = response.data;
            if (json['error']) {
                document.getElementById('button-payment-custom-field' + data.id).parentNode.querySelector('#' + data.step + data.field).after('<div class="text-danger">' + json['error'] + '</div>');
            }
            if (json['success']) {
                alert(json['success']);
                document.getElementById('button-payment-custom-field' + data.id).parentNode.querySelector('#' + data.step + data.field).value = json['code'];
                var state = { 'payment_address': {} }
                state['payment_address'][data.field] = json['code'];

                this.dispatch('payment_address/update', state);

                document.getElementById('button-payment-custom-field' + data.id).disabled = false;
            }
        }).catch(function(error) {
            console.error(error)
        });
    });

})(qc);