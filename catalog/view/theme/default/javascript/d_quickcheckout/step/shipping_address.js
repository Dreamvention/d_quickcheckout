/**
*   ShippingAddress Model
*/

(function(){

    this.subscribe('shipping_address/update', function(data) {
        clearTimeout(this.payment_address_timer);

        this.setState({'session': data});
        var difference = this.getChange();

        this.payment_address_timer = setTimeout(function(){
            this.send('extension/d_quickcheckout/shipping_address/update', difference.session, function(json) {
                this.setState(json);
                this.setChange(this.getState());
            }.bind(this));
        }, 10);
        
    });

    //show/hide shipping address immediately
    this.subscribe('payment_address/update', function(data) {
        if(data.payment_address.shipping_address){
            if(data.payment_address.shipping_address == 1){
                this.updateState(['config', this.getAccount(), 'shipping_address', 'display'], 0);
            }else{
                this.updateState(['config', this.getAccount(), 'shipping_address', 'display'], 1);
            }
        }
    });

    this.subscribe('shipping_address/edit', function(data) {
        this.setState(data);
    });

})(qc);
