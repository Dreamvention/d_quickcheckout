
qc.ShippingAddress = qc.Model.extend({
    defaults: {
        'shipping_address': '',
        'config': '',
        'account': '',
        'addresses': '',
        'show_shipping_address': '',
    },

    initialize: function(){
        this.set('config', config.account[this.get('account')].shipping_address);
    },

    changeAccount: function(account){
        this.set('account', account);
        this.set('config', config.account[this.get('account')].shipping_address);
    },

    changeAddress: function(address_id){
        this.set('shipping_address.address_id', address_id);

        var json = this.toJSON();
        var that = this;
        $.post('index.php?route=extension/d_quickcheckout/shipping_address/update', { 'shipping_address' :  json.shipping_address }, function(data) {
            that.updateForm(data);

        }, 'json').error(
        );
    },

    updateField: function(name, value){
        clearTimeout(qc.shipping_address_waiting);
        this.set(name, value);
        var json = this.toJSON();
        var that = this;
        qc.shipping_address_waiting = setTimeout(function () {
            $.post('index.php?route=extension/d_quickcheckout/shipping_address/update', { 'shipping_address' : json.shipping_address }, function(data) {
                that.updateForm(data);

            }, 'json').error(
            );
        }, 500);

    },
    validate: function(attrs, options){
        var errors = [];
        if(typeof(this.get('shipping_address.'+options.key)) !== 'undefined' && this.get('shipping_address.'+options.key).length > 0 && typeof(attrs.shipping_address[options.key]) !== 'undefined' && attrs.shipping_address[options.key].length == 0){
            console.log('trying to set an empty value key:'+ options.key +" value "+ this.get('shipping_address.'+options.key));
            errors.push({field:'shipping_address',key:options.key,value:this.get('shipping_address.'+options.key)});
            return errors.length > 0 ? errors : false;
        }
    },
    handleError:function (model, error){
        var that = this;
        _.each(error,function(element){
            console.log('write an old value');
            that.model.set(element.field + "." + element.key,element.value);
        });
    }


});
