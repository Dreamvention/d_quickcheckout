/**
*   Continue Model
*/

(function(){


    this.subscribe('continue/next', function(data) {
        clearTimeout(this.continue_timer);

        var data = { page_id: this.getSession().page_id }

        this.continue_timer = setTimeout(function(){
            this.send('extension/d_quickcheckout/continue/update', data, function(json){

                this.setState(json);
                this.setChange(this.getState());

            }.bind(this));
        }, 11);
        
    });

    this.subscribe('continue/edit', function(data) {
        this.setState(data);
    });


})(qc);
