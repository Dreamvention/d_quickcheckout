/**
 *   Account Model
 */

(function() {

    this.subscribe('account/update', function(data) {
        this.setState({ 'session': data }, false);
        var difference = this.getChange();
        this.send('extension/d_quickcheckout/account/update', difference, function(json) {
            if (json?.session?.payment_methods !== undefined) {
                this.updateState(['session', 'payment_methods'], json['session']['payment_methods'], false);
            }
            if (json?.session?.shipping_methods !== undefined) {
                this.updateState(['session', 'shipping_methods'], json['session']['shipping_methods'], false);
            }
            this.setState(json);
            this.dispatch('account/updated', json);
            this.setChange(this.getState());
        }.bind(this));
    });

    /*REFACTOR*/
    this.subscribe('account/login', function(data) {
        this.setState({ 'session': data }, false);
        
        this.send('extension/d_quickcheckout/account/update', { 'session': data }, function(json) {
            if (json?.session?.payment_methods !== undefined) {
                this.updateState(['session', 'payment_methods'], json['session']['payment_methods'], false);
            }
            if (json?.session?.shipping_methods !== undefined) {
                this.updateState(['session', 'shipping_methods'], json['session']['shipping_methods'], false);
            }
            this.setState(json);
            this.dispatch('account/updated', json);
            this.dispatch('setting/updateCommon', json);
            this.setChange(this.getState());
        }.bind(this));
    });

    this.subscribe('account/edit', function(data) {
        this.setState(data);
    });

})(qc);