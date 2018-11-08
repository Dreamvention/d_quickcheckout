/**
 *   Custom Model
 */

(function() {

    this.subscribe('custom/update', function(data) {

        clearTimeout(this.payment_address_timer);

        this.setState({ 'session': data });
        var difference = this.getChange();

        this.payment_address_timer = setTimeout(function() {
            this.send('extension/d_quickcheckout/custom/update', difference, function(json) {
                this.setState(json);
                this.setChange(this.getState());
            }.bind(this));
        }, 10);

    });

    this.subscribe('custom/edit', function(data) {
        this.setState(data);
    });

})(qc);