/**
 *   Continue Model
 */

(function() {


    this.subscribe('continue/next', function(data) {
        clearTimeout(this.continue_timer);
        var current_page_id = this.getSession().page_id;
        var data = { page_id: current_page_id }

        this.continue_timer = setTimeout(function() {
            this.send('extension/d_quickcheckout/continue/update', data, function(json) {
                this.setState(json);
                this.setChange(this.getState());
                if (current_page_id == this.getSession().page_id) {
                    this.goToError();
                } else {
                    this.goToPageNav();
                }

            }.bind(this));
        }, 11);

    });

    this.subscribe('continue/prev', function() {
        var pages = this.getSession().pages;
        var current_page_id = this.getSession().page_id;

        var prev_page_index = pages.indexOf(current_page_id) - 1;
        var prev_page_id = pages[prev_page_index];

        if (prev_page_id) {
            var state = { 'session': { 'page_id': prev_page_id } };
            this.setState(state);
            //to avoid page unsync
            this.send('extension/module/d_quickcheckout/update', state, function(json) {}.bind(this));
        }
    });

    this.subscribe('continue/edit', function(data) {
        this.setState(data);
    });


})(qc);