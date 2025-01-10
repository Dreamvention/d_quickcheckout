/**
 *   Cart Model
 */

(function() {

    this.subscribe('cart/update', function(data) {

        clearTimeout(this.cart_timer);
        const version = this.getState().opencart_version;
        if (version > '4.0.1.1') {
            var url = 'extension/ajax_quick_checkout/ajax_quick_checkout/cart.update';
        } else {
            var url = 'extension/ajax_quick_checkout/ajax_quick_checkout/cart|update';
        }
        this.cart_timer = setTimeout(function() {
            this.send(url, data, function(json) {
                if (version > '4.0.1.1') {
                    this.get('extension/ajax_quick_checkout/ajax_quick_checkout/cart.get_cart_data', function(data) {
                        json = d_quickcheckout_lodash.merge(json, data);
                        this.setState(json);
                        this.dispatch('setting/updateCommon', json);
                    });
                } else {
                    this.setState(json);
                    this.dispatch('setting/updateCommon', json);
                }
            }.bind(this));
        }, 500);
    });

    this.subscribe('cart/update_option', function(data) {

        clearTimeout(this.cart_timer);
        const version = this.getState().opencart_version;
        if (version > '4.0.1.1') {
            var url = 'extension/ajax_quick_checkout/ajax_quick_checkout/cart.update';
        } else {
            var url = 'extension/ajax_quick_checkout/ajax_quick_checkout/cart|update';
        }
        this.cart_timer = setTimeout(function() {
            this.send(url, data, function(json) {
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
