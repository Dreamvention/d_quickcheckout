/**
 *   Account Model
 */

(function() {

    this.subscribe('account/update', function(data) {
        this.setState({ 'session': data });
        var difference = this.getChange();
        this.send('extension/d_quickcheckout/account/update', difference, function(json) {
            this.setState(json);
            this.dispatch('account/updated', json);
            this.setChange(this.getState());
        }.bind(this));
    });

    //REFACTOR
    this.subscribe('account/login', function(data) {
        this.setState({ 'session': data });
        var difference = this.getChange();
        this.send('extension/d_quickcheckout/account/update', { 'session': data }, function(json) {
            this.setState(json);
            this.dispatch('account/updated', json);
            this.dispatch('setting/updateCommon', json);
            this.setChange(this.getState());
        }.bind(this));
    });

    this.subscribe('account/edit', function(data) {
        this.setState(data);
    });

})(qc);