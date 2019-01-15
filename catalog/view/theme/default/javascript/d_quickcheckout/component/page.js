/**
 *   Page Model
 */

(function() {

    this.subscribe('page/sort', function() {
        var state = {};
        state.layout = this.getLayout();
        $('.page-nav > .page-nav-item').each(function(i) {
            state.layout.pages[$(this).attr('page_id')].sort_order = String(i);
        });
        this.setState(state);
    });

    this.subscribe('page/add', function() {
        var state = this.getState();

        var sort_order = Object.keys(state.layout.pages).length;
        var page_id = 'page' + this.rand();

        //refactor - clone objecta
        state.layout.pages[page_id] = {
            'id': page_id,
            'children': {},
            'deleted': 0,
            'sort_order': sort_order,
            'type': 'page',
            'display': 1,
            'text': 'New page',
            'description': 'New page description'
        }

        $('.page-nav > .page-nav-item').each(function(i) {
            if (state.layout.pages[$(this).attr('page_id')]) {
                state.layout.pages[$(this).attr('page_id')].sort_order = i;
            }
        });

        state.session.page_id = page_id;

        this.setState(state);

        state.session.pages.push(page_id);

        var pages = state.session.pages;
        pages = pages.filter(function(i) {
            return i !== false
        });

        this.updateState(['session', 'pages'], pages);

    });

    this.subscribe('page/remove', function(data) {
        var state = this.getState();
        state.layout.pages[data.page_id].deleted = 1;

        this.setState(state);

        var pages = state.session.pages;
        pages = pages.filter(function(i) {
            return i !== data.page_id && i !== false
        });

        this.updateState(['session', 'pages'], pages);


        var last_page = '';
        $.each(pages, function(i, e) {
            if (state.layout.pages[e]) {
                state.layout.pages[e].sort_order = i;
            }

            last_page = e;
        });

        if (data.page_id == state.session.page_id) {
            this.updateState(['session', 'page_id'], last_page);
        }
    });

    this.subscribe('page/open', function(data) {
        //pass page_id

        if (data.page_id) {
            var state = { 'session': { 'page_id': data.page_id } };
            this.setState(state);
            //to avoid page unsync
            this.send('extension/module/d_quickcheckout/update', state, function(json) {}.bind(this));
        }
    })

    this.getPageIds = function() {
        var pages_sorted = [];

        for (var key in this.getLayout().pages) pages_sorted.push(this.getLayout().pages[key]);
        pages_sorted.sort(function(a, b) { return parseInt(a.sort_order) - parseInt(b.sort_order) });

        var result = pages_sorted.map(function(page) {
            return page.id;
        });

        return result;
    }

    this.hasPayment = function() {
        var layoutString = JSON.stringify(this.getState().layout.pages);
        return (layoutString.indexOf('payment"') == -1);
    }

    this.goToPageNav = function() {
        if (this.isMobile()) {
            setTimeout(function() {
                $('html,body').animate({ scrollTop: $(".process-page").offset().top - 60 }, 'slow');
            }, 10);
        }
    }
})(qc);