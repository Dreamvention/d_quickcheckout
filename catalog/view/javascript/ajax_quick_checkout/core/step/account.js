/**
 *   Account Model
 */

(function() {

    this.subscribe('account/update', function(data) {
        this.setState({ 'session': data }, false);
        var difference = this.getChange();
        const version = this.getState().opencart_version;
        if (version > '4.0.1.1') {
            var url = 'extension/ajax_quick_checkout/ajax_quick_checkout/account.update';
        } else {
            var url = 'extension/ajax_quick_checkout/ajax_quick_checkout/account|update';
        }
        
        this.send(url, difference, function(json) {
            this.setState(json);
            this.dispatch('account/updated', json);
            this.setChange(this.getState());
        }.bind(this));
    });

    //REFACTOR
    this.subscribe('account/login', function(data) {
        this.setState({ 'session': data }, false);
        var difference = this.getChange();
        const version = this.getState().opencart_version;
        if (version > '4.0.1.1') {
            var url = 'extension/ajax_quick_checkout/ajax_quick_checkout/account.update';
        } else {
            var url = 'extension/ajax_quick_checkout/ajax_quick_checkout/account|update';
        }
        this.send(url, { 'session': data }, function(json) {
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