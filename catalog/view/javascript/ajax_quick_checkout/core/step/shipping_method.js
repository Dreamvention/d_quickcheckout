/**
*   ShippingMethod Model
*/

(function(){

    this.subscribe('shipping_method/update', function(data) {
        const version = this.getState().opencart_version;
        if (version > '4.0.1.1') {
            var url = 'extension/ajax_quick_checkout/ajax_quick_checkout/shipping_method.update';
        } else {
            var url = 'extension/ajax_quick_checkout/ajax_quick_checkout/shipping_method|update';
        }
        this.send(url, data, function(json) {
            if (json?.session?.shipping_methods !== undefined) {
                this.updateState(['session', 'shipping_methods'], json?.session?.shipping_methods, false);
            }
            if (json?.session?.payment_methods !== undefined) {
                this.updateState(['session', 'payment_methods'], json?.session?.payment_methods, false);
            }
            this.setState(json);
        }.bind(this));
    });

    this.subscribe('shipping_method/edit', function(data) {
        this.setState(data);
    });

})(qc);
