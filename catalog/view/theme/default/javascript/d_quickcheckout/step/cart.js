/**
 *   Cart Model
 */

(function() {

    this.subscribe('cart/update', function(data) {

        clearTimeout(this.cart_timer);

        this.cart_timer = setTimeout(function() {
            this.send('extension/d_quickcheckout/cart/update', data, function(json) {
                if (json?.session?.payment_methods !== undefined) {
                    this.updateState(['session', 'payment_methods'], json['session']['payment_methods'], false);
                }
                if (json?.session?.shipping_methods !== undefined) {
                    this.updateState(['session', 'shipping_methods'], json['session']['shipping_methods'], false);
                }
                this.setState(json);
                this.dispatch('setting/updateCommon', json);
            }.bind(this));
        }, 500);
    });

    this.subscribe('cart/update_option', function(data) {

        clearTimeout(this.cart_timer);

        this.cart_timer = setTimeout(function() {
            this.send('extension/d_quickcheckout/cart/update', data, function(json) {
                this.setState(json);
                this.updateState(['session', 'cart'],json.session.cart);
                this.dispatch('setting/updateCommon', json);
            }.bind(this));
        }, 10);
    });

    this.subscribe('cart/edit', function(data) {
        this.setState(data);
    });
})(qc);
