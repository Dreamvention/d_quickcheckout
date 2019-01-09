/**
 *   Row Model
 */

(function() {

    this.subscribe('row/add', function(data) {
        var state = this.getState();
        var row_id = 'row' + this.rand();
        var layout = {};
        var page = $.extend(true, {}, state.layout.pages[state.session['page_id']]);
        this.flattenLayout(page, 'children', layout);

        layout[row_id] = {
            'id': row_id,
            'parent': data.parent,
            'sort_order': data.sort_order,
            'children': {},
            'type': 'row'
        }


        var col_id = 'col' + this.rand();
        layout[col_id] = {
            'id': col_id,
            'parent': row_id,
            'sort_order': 0,
            'children': {},
            'size': 12,
            'type': 'col'

        }

        page = this.unflattenLayout(layout, state.session['page_id']);
        this.updateState(['layout', 'pages', state.session['page_id'], 'children'], page);

    })

    this.subscribe('row/remove', function(data) {
        $('#' + data.row_id).hide();
        this.setLayoutAction('rowRemove', data);
    })

    this.rowRemove = function(data, layout) {

        delete layout[data.row_id]
        return layout;
    }

})(qc);