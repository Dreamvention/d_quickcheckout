/**
*   ShippingMethod Model
*/

(function(){

    this.subscribe('shipping_method/update', function(data) {
        this.send('extension/d_quickcheckout/shipping_method/update', data, function(json) {
            this.setState(json);
        }.bind(this));
    });

    this.subscribe('shipping_method/edit', function(data) {
        this.setState(data);
    });

})(qc);
