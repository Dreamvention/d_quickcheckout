/**
*   Confirm Model
*/

(function(){

    this.subscribe('confirm/confirm', function(data) {
        this.updateState(['session','confirm', 'loading'], true);
        setTimeout(function(){
            this.send('extension/d_quickcheckout/confirm/update', data, function(state){
                this.setState(state);
                if(!state.session.confirm.checkout){
                    console.log('AQC:Sorry, you have errors');
                    setTimeout(function(){
                        $('html,body').animate({ scrollTop: $(".has-error").offset().top-60}, 'slow');
                    },10);
                    
                }else{
                    this.updateState(['session','confirm', 'checkout'], false);
                    $( document ).ajaxComplete(function() {
                        setTimeout(function(){
                            var href = $(state.session.confirm.trigger, $('#payment')).attr('href');
                            if(href != '' && href != undefined) {
                                document.location.href = href;
                            }else{
                                $(state.session.confirm.trigger, $('#payment')).click();
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
