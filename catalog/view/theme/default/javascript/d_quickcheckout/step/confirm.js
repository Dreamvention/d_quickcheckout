/**
*   Confirm Model
*/

(function(){

    this.subscribe('confirm/confirm', function(data) {

        this.send('extension/d_quickcheckout/confirm/update', data, function(state){
            this.setState(state);
            if(!state.session.confirm.checkout){
                console.log('sorry, you have errors');
                setTimeout(function(){
                    $('html,body').animate({ scrollTop: $(".has-error").offset().top-60}, 'slow');
                },10);
                
            }else{
                var href = $(state.session.confirm.trigger, $('#payment')).attr('href');
                if(href != '' && href != undefined) {
                    document.location.href = href;
                }else{
                    $(state.session.confirm.trigger, $('#payment')).click();
                }
            }
        }.bind(this));
    });

    this.subscribe('confirm/edit', function(data) {
        this.setState(data);
    });

})(qc);
