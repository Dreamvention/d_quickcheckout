/**
*   Col Model
*/

(function(){

    this.subscribe('col/add', function(data){
        var state = this.getState();
        var col_id = 'col'+this.rand();
        var layout = {};
        var page = dv_cash.extend(true, {}, state.layout.pages[state.session['page_id']]);
        this.flattenLayout(page, 'children', layout);

        layout[data.parent_path + '_' + col_id] = {
            'id': col_id,
            'path': data.parent_path + '_' + col_id,
            'parent': data.parent,
            'sort_order': data.sort_order,
            'children': {},
            'size': 12,
            'type': 'col'
        }

        page = this.unflattenLayout(layout, state.session['page_id']);
        this.updateState(['layout', 'pages', state.session['page_id'], 'children'], page, false);

    });

    this.subscribe('col/remove', function(data){
        var state = this.getState();
        var layout = {};
        var page = dv_cash.extend(true, {}, state.layout.pages[state.session['page_id']]);
        this.flattenLayout(page, 'children', layout);
        var oldLayout = JSON.parse(JSON.stringify(layout));

        delete layout[data.path];

        if (d_quickcheckout_lodash.isEqual(oldLayout, layout)) return;

        page = this.unflattenLayout(layout, state.session['page_id']);
        this.updateState(['layout', 'pages', state.session['page_id'], 'children'], page, false);

    });

    this.subscribe('col/resize', function(data) {
        var state = this.getState();
        var layout = {};
        var page = dv_cash.extend(true, {}, state.layout.pages[state.session['page_id']]);
        this.flattenLayout(page, 'children', layout);
        if (layout[data.item_id].size == data.width) return;
        layout[data.item_id].size = data.width;

        page = this.unflattenLayout(layout, state.session['page_id']);
        this.updateState(['layout', 'pages', state.session['page_id'], 'children'], page);
    });

})(qc);