/**
*   Col Model
*/

(function(){

    this.subscribe('col/add', function(data){
        var state = this.getState();
        var col_id = 'col'+this.rand();
        var layout = {};
        var page = $.extend(true, {}, state.layout.pages[state.session['page_id']]);
        this.flattenLayout(page, 'children', layout);

        layout[col_id] = {
            'id': col_id,
            'parent': data.parent,
            'sort_order': data.sort_order,
            'children': {},
            'size': 12,
            'type': 'col'
        }

        page = this.unflattenLayout(layout, state.session['page_id']);
        this.updateState(['layout', 'pages', state.session['page_id'], 'children'], page);

    })

    this.subscribe('col/remove', function(data){
        var state = this.getState();
        var layout = {};
        var page = $.extend(true, {}, state.layout.pages[state.session['page_id']]);
        this.flattenLayout(page, 'children', layout);

        if(data.sort_order < 2){
            delete layout[layout[data.col_id].parent]
        }
        delete layout[data.col_id]

        page = this.unflattenLayout(layout, state.session['page_id']);
        this.updateState(['layout', 'pages', state.session['page_id'], 'children'], page);

    })

    this.subscribe('col/resize', function(data) {
        clearTimeout(this.step_resize_timer);

        this.step_resize_timer = setTimeout(function(){

            var state = this.getState();
            var layout = {};
            var page = $.extend(true, {}, state.layout.pages[state.session['page_id']]);
            this.flattenLayout(page, 'children', layout);

            layout[data.item_id].size = data.width;

            page = this.unflattenLayout(layout, state.session['page_id']);
            this.updateState(['layout', 'pages', state.session['page_id'], 'children'], page);

            

        }, 1000);
    });

})(qc);