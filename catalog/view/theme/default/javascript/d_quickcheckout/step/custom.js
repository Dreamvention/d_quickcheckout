/**
 *   Custom Model
 */

(function() {

    this.subscribe('custom/update', function(data) {

        clearTimeout(this.custom_timer);

        this.setState({ 'session': data }, false);
        var data_to_update = {
            session: {
                custom: this.getSession().custom,
            }
        };

        this.custom_timer = setTimeout(function() {
            this.send('extension/d_quickcheckout/custom/update', data_to_update, function(json) {
                this.setState(json);
                this.setChange(this.getState());
            }.bind(this));
        }, 10);

    });

    this.subscribe('custom/error', function(data) {
        var state = { 'errors': { 'custom': {} } }
        state['errors']['custom'][data.field_id] = data.error;
        this.setState(state);
    });

    this.subscribe('custom/edit', function(data) {
        this.setState(data);
    });

})(qc);