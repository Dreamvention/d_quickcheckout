/**
*   Confirm Model
*/

(function(){
    this.subscribe('confirm/confirm', function(data) {
        this.updateState(['session','confirm', 'loading'], true);
        setTimeout(function(){
            this.send('extension/d_quickcheckout/confirm/update', data, function(json){
                this.setState(json);
                if(!json.session.confirm.checkout){
                    this.goToError();
                    
                }else{
                    this.updateState(['session','confirm', 'checkout'], false);

                    this.dispatch('setting/updateCommon', json);

                    this.setState(json);
                    this.setChange(this.getState());
                    
                    $( document ).ajaxComplete(function() {
                        setTimeout(function(){
                            var href = $(json.session.confirm.trigger, $('#payment')).attr('href');
                            if(href != '' && href != undefined) {
                                document.location.href = href;
                            }else{
                                $(json.session.confirm.trigger, $('#payment')).click();
                            }
                            $( document ).unbind('ajaxComplete');
                        },100);
                    })
                }
            }.bind(this));
        },500);
    });

    this.subscribe('confirm/edit', function(data) {
        this.setState(data);
    });

})(qc);
