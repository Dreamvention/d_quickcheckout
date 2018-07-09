/**
*   Stepgkfg Model
*/

(function(){

    this.data_pending = [];

    this.subscribe('step/add', function(data){
        var state = this.getState();
        var item_id = 'item'+this.rand();
        var layout = {};
        var page = $.extend(true, {}, state.layout.pages[state.session['page_id']]);
        this.flattenLayout(page, 'children', layout);

        layout[item_id] = {
            'id': item_id,
            'parent': data.parent,
            'sort_order': data.sort_order,
            'name': data.name,
            'type': 'item'
        }

        page = this.unflattenLayout(layout, state.session['page_id']);
        this.updateState(['layout', 'pages', state.session['page_id'], 'children'], page);
    })

    this.subscribe('step/remove', function(data) {
        var step_id = data.step_id

        var state = this.getState();
        var layout = {};
        var page = $.extend(true, {}, state.layout.pages[state.session['page_id']]);
        this.flattenLayout(page, 'children', layout);

        delete layout[step_id];

        page = this.unflattenLayout(layout, state.session['page_id']);
        this.updateState(['layout', 'pages', state.session['page_id'], 'children'], page);
    });

    this.subscribe('step/move', function(data) {
        this.data_pending.push(data);
        clearTimeout(this.step_move_timer);

        this.step_move_timer = setTimeout(function(){

            var state = this.getState();
            var layout = {};
            var page = $.extend(true, {}, state.layout.pages[state.session['page_id']]);
            this.flattenLayout(page, 'children', layout);

            for( i in this.data_pending){
                var data = this.data_pending[i];
                layout[data.item_id].parent = data.col_id;

            }
            

            for( i in this.data_pending){
                var data = this.data_pending[i];
                console.log(data)
                $('#'+data.col_id).children('.gr-col-content').children('*').each(function(child_id, child){
                    if(layout[child.id]){
                        layout[child.id].sort_order = child_id;
                    }
                });
            }

            this.data_pending = [];

            page = this.unflattenLayout(layout, state.session['page_id']);
            this.updateState(['layout', 'pages', state.session['page_id'], 'children'], page);

        }, 5000);
    });
})(qc);