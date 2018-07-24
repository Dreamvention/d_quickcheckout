/**
*   Account Model
*/

(function(){

    this.subscribe('account/update', function(data) {
        this.send('extension/d_quickcheckout/account/update', data, function(json) {
            this.setState(json);
            this.dispatch('account/updated', json);
            
        }.bind(this));
    });

    this.subscribe('account/edit', function(data) {
        this.setState(data);
    });

})(qc);
