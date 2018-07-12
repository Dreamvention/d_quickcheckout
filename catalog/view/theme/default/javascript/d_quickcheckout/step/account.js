/**
*   Account Model
*/

(function(){

    this.subscribe('account/update', function(data) {
        this.send('extension/d_quickcheckout/account/update', data, function(json) {
            this.setState(json);
            this.dispatch('account/updated', json);
            this.dispatch('account/updateAccountLoginText', json['text_account_login']);
            this.dispatch('cart/updateCartTotalText', json['cart_total_text']);
            
        }.bind(this));
    });

    this.subscribe('account/edit', function(data) {
        this.setState(data);
    });

    this.subscribe('account/updateAccountLoginText', function(text) {
        if(text){
            $('#top-links > ul > li:nth-child(2)').html(text);
        }
    });

})(qc);
