/**
 *   Row Model
 */

(function() {

    this.subscribe('row/add', function(data) {
        var state = this.getState();
        var row_id = 'row' + this.rand();
        var layout = {};
        var page = dv_cash.extend(true, {}, state.layout.pages[state.session['page_id']]);
        this.flattenLayout(page, 'children', layout);

        layout[data.parent_path + '_' + row_id] = {
            'id': row_id,
            'path': data.parent_path + '_' + row_id,
            'parent': data.parent,
            'sort_order': data.sort_order,
            'children': {},
            'type': 'row'
        };


        var col_id = 'col' + this.rand();
        layout[data.parent_path + '_' + row_id + '_' + col_id] = {
            'id': col_id,
            'path': data.parent_path + '_' + row_id + '_' + col_id,
            'parent': row_id,
            'sort_order': 0,
            'children': {},
            'size': 12,
            'type': 'col'

        };

        page = this.unflattenLayout(layout, state.session['page_id']);
        this.updateState(['layout', 'pages', state.session['page_id'], 'children'], page, false);

    });

    this.subscribe('row/remove', function(data) {
        var layout = {};
        var page = dv_cash.extend(true, {}, state.layout.pages[state.session['page_id']]);
        this.flattenLayout(page, 'children', layout);
        var oldLayout = JSON.parse(JSON.stringify(layout));
        
        layout = this.rowRemove(data, layout);
        
        if (d_quickcheckout_lodash.isEqual(oldLayout, layout)) return;

        page = this.unflattenLayout(layout, state.session['page_id']);
        this.updateState(['layout', 'pages', state.session['page_id'], 'children'], page, false);
    });

    this.rowRemove = function(data, layout) {

        delete layout[data.path]
        return layout;
    };

})(qc);