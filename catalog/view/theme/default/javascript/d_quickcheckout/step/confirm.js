/**
 *   Confirm Model
 */

(function() {
    this.subscribe('confirm/confirm', function(data) {
        this.updateState(['session', 'confirm', 'loading'], true);
        setTimeout(function() {
            this.send('extension/d_quickcheckout/confirm/update', data, function(json) {
                this.setState(json);
                if (!json.session.confirm.checkout) {
                    this.goToError();

                } else {
                    this.updateState(['session', 'confirm', 'checkout'], false);

                    this.dispatch('setting/updateCommon', json);

                    this.setState(json);
                    this.setChange(this.getState());

                    $(document).ajaxComplete(function() {
                        this.loading(true);
                        setTimeout(function() {
                            var href = $(json.session.confirm.trigger, $('#payment')).attr('href');
                            if (href != '' && href != undefined) {
                                document.location.href = href;
                            } else {
                                $(json.session.confirm.trigger, $('#payment')).click();
                            }
                            $(document).unbind('ajaxComplete');
                        }, 100);
                    }.bind(this))
                }
            }.bind(this));
        }, 500);
    });

    this.subscribe('confirm/next', function(data) {
        clearTimeout(this.continue_timer);
        setTimeout(function() {
            var current_page_id = this.getSession().page_id;
            var data = { page_id: current_page_id }

            this.continue_timer = setTimeout(function() {
                this.send('extension/d_quickcheckout/confirm/update', data, function(json) {
                    this.setState(json);
                    this.setChange(this.getState());
                    if (current_page_id == this.getSession().page_id) {
                        this.goToError();
                    } else {
                        this.goToPageNav();
                    }

                }.bind(this));
            }, 11);

        }, 500);

    });

    this.subscribe('confirm/prev', function() {
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

    this.subscribe('confirm/edit', function(data) {
        this.setState(data);
    });

})(qc);