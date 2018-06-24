/**
*   Cart Model
*/

(function(){

    this.subscribe('cart/update', function(data) {
        clearTimeout(this.cart_timer);
        
        this.cart_timer = setTimeout(function(){
            this.send('extension/d_quickcheckout/cart/update', data, function(json) {
                this.setState(json);                
                this.updateCartTotalText(json['cart_total_text']);
            }.bind(this));
        }, 500);
    });

    this.subscribe('cart/edit', function(data) {
        this.setState(data);
    });

    this.updateCartTotalText = function(text){
        $('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + text + '</span>');
    }

})(qc);
