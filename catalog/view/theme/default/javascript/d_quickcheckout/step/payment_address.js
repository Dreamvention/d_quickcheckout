/**
 *   PaymentAddress Model
 */

(function() {


    this.subscribe('payment_address/update', function(data) {
       
         //show/hide shipping address immediately
        if (data.payment_address.shipping_address && this.getState().session.has_shipping) {
       
            if (data.payment_address.shipping_address == 1) {
                this.updateState(['config', this.getAccount(), 'shipping_address', 'display'], 0, false);
            } else {
                this.updateState(['config', 'guest', 'shipping_address', 'display'], 1, false);
                this.updateState(['config','logged', 'shipping_address', 'display'], 1, false);
                this.updateState(['config', 'register', 'shipping_address', 'display'], 1, false);
            }
        } else if (!this.getState().session.has_shipping) {
            this.updateState(['config', this.getAccount(), 'shipping_address', 'display'], 0, false);
        }
        // end show/hide shipping address immediately

        clearTimeout(this.payment_address_timer);

        this.setState({ 'session': data }, false);
        var difference = this.getChange();
       
        this.payment_address_timer = setTimeout(function() {
            this.send('extension/d_quickcheckout/payment_address/update', difference, function(json) {
                this.setState(json);
                this.setChange(this.getState());
            }.bind(this));
        }, 20);

    });

    this.subscribe('payment_address/error', function(data) {
        var state = { 'errors': { 'payment_address': {} } }
        state['errors']['payment_address'][data.field_id] = data.error;
        this.setState(state);
    });

    this.subscribe('payment_address/edit', function(data) {
        this.setState(data);
    });

    this.subscribe('payment_address/upload_file', function(data) {
        
        $.ajax({
            url: 'index.php?route=tool/upload',
            type: 'post',
            dataType: 'json',
            data: data.FormData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#button-payment-custom-field'+ data.id ).button('loading');
            },
            complete: function() {
                $('#button-payment-custom-field'+ data.id ).button('reset');
            },
            success: function(json) {
                $('#button-payment-custom-field'+ data.id ).parent().find('.text-danger').remove();

                if (json['error']) {
                    $('#button-payment-custom-field'+ data.id ).parent().find($('#'+data.step+data.field)).after('<div class="text-danger">' + json['error'] + '</div>');
                }

                if (json['success']) {
                    alert(json['success']);

                    $('#button-payment-custom-field'+ data.id ).parent().find($('#'+data.step+data.field)).val(json['code']);
                    var state =  { 'payment_address': {} } 
                    state['payment_address'][data.field] = json['code'];
                
                    this.dispatch('payment_address/update', state);
                }
            }.bind(this),
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

})(qc);