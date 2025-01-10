/**
*   Stepgkfg Model
*/

(function(){

    this.data_pending = [];

    this.subscribe('step/add', function(data){
        var state = this.getState();
        var item_id = 'item'+this.rand();
        var layout = {};
        var page = {}
        var page = d_quickcheckout_lodash.merge(page, state.layout.pages[state.session['page_id']]);
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
        document.getElementById(data.step_id).remove();
        this.setLayoutAction('stepRemove', data);
    });

    this.stepRemove = function(data, layout){
        delete layout[data.step_id];
        return layout;
    }

    this.subscribe('step/move', function(data) {

        this.setLayoutAction('stepMove', data);
    });

    this.stepMove = function(data, layout){

        layout[data.item_id].parent = data.col_id;

        document.getElementById(data.col_id)?.childNodes.forEach(function(el) {
            if(el?.classList && el.classList.contains('gr-col-content')){
                el?.childNodes?.forEach(function(child, child_id){
                    if(layout[child.id]){
                        layout[child.id].sort_order = child_id;
                    }
                });
            }
        });

        return layout;
    }
})(qc);