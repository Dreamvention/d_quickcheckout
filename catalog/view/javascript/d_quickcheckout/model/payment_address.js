qc.PaymentAddress = qc.Model.extend({
    defaults: {
        'payment_address': '',
        'config': '',
        'account': '',
        'addresses': '',
    },

    initialize: function(){
        this.set('config', config.account[this.get('account')].payment_address);
    },

    changeAccount: function(account){
        this.set('account', account);
        this.set('config', config.account[this.get('account')].payment_address);
    },

    changeAddress: function(address_id){
        this.set('payment_address.address_id', address_id);

        if( address_id != 'new'){
            this.set('payment_address.shipping_address', 0);
        }
        var json = this.toJSON();
        var that = this;
        $.post('index.php?route=extension/d_quickcheckout/payment_address/update', { 'payment_address' :  json.payment_address }, function(data) {
            that.updateForm(data);

        }, 'json').error(
        );
    },

    updateField: function(name, value){
        clearTimeout(qc.payment_address_waiting);
        this.set(name, value);
        var that = this;
        var json = this.toJSON();
        qc.payment_address_waiting = setTimeout(function () {
            $.post('index.php?route=extension/d_quickcheckout/payment_address/update', { 'payment_address' : json.payment_address }, function(data) {
                that.updateForm(data);
            }, 'json').error();
        }, 500);

    },
    validate: function(attrs,options){
        var errors = [];
        if(typeof(this.get('payment_address.'+options.key)) !== 'undefined' && this.get('payment_address.'+options.key).length > 0 && typeof(attrs.payment_address[options.key]) !== 'undefined' && attrs.payment_address[options.key].length == 0){
            console.log('trying to set an empty value key:'+ options.key +" value "+ this.get('payment_address.'+options.key));
            errors.push({field:'payment_address',key:options.key,value:this.get('payment_address.'+options.key)});
            return errors.length > 0 ? errors : false;
        }


    },
    handleError:function (model,error){
        var that = this;
        _.each(error,function(element){
            console.log('write an old value');
            that.model.set(element.field + "." + element.key,element.value);
        });
    }

});
