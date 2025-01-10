/**
 *   Custom Model
 */

(function() {

    this.subscribe('custom/update', function(data) {
        const version = this.getState().opencart_version;
        if (version > '4.0.1.1') {
            var url = 'extension/ajax_quick_checkout/ajax_quick_checkout/custom.update';
        } else {
            var url = 'extension/ajax_quick_checkout/ajax_quick_checkout/custom|update';
        }

        clearTimeout(this.payment_address_timer);

        this.setState({ 'session': data }, false);
        var data_to_update = {
            session: {
                custom: this.getSession().custom,
            }
        };

        this.payment_address_timer = setTimeout(function() {
            this.send(url, data_to_update, function(json) {
                this.setState(json);
                this.setChange(this.getState());
            }.bind(this));
        }, 10);

    });

    this.subscribe('custom/edit', function(data) {
        this.setState(data);
    });

})(qc);