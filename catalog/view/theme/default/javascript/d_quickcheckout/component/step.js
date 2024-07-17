/**
*   Stepgkfg Model
*/

(function(){

    this.data_pending = [];

    this.subscribe('step/add', function(data){
        var state = this.getState();
        var item_id = 'item'+this.rand();
        var layout = {};
        var page = dv_cash.extend(true, {}, state.layout.pages[state.session['page_id']]);
        this.flattenLayout(page, 'children', layout);

        layout[item_id] = {
            'id': item_id,
            'path': data.parent_path + '_' + item_id,
            'parent': data.parent,
            'sort_order': data.sort_order,
            'name': data.name,
            'type': 'item'
        }

        page = this.unflattenLayout(layout, state.session['page_id']);
        this.updateState(['layout', 'pages', state.session['page_id'], 'children'], page, false);
    });

    this.subscribe('step/remove', function(data) {
        var layout = {};
        var page = dv_cash.extend(true, {}, state.layout.pages[state.session['page_id']]);
        this.flattenLayout(page, 'children', layout);
        var oldLayout = JSON.parse(JSON.stringify(layout));
        
        layout = this.stepRemove(data, layout);

        if (d_quickcheckout_lodash.isEqual(oldLayout, layout)) return;

        page = this.unflattenLayout(layout, state.session['page_id']);
        this.updateState(['layout', 'pages', state.session['page_id'], 'children'], page, false);
    });

    this.stepRemove = function(data, layout){
        delete layout[data.step_id];
        return layout;
    };

    this.subscribe('step/move', function(data) {
        var layout = {};
        var page = dv_cash.extend(true, {}, state.layout.pages[state.session['page_id']]);
        this.flattenLayout(page, 'children', layout);
        var oldLayout = JSON.parse(JSON.stringify(layout));
        
        layout = this.stepMove(data, layout);
        
        if (d_quickcheckout_lodash.isEqual(oldLayout, layout)) return;

        page = this.unflattenLayout(layout, state.session['page_id']);
        this.updateState(['layout', 'pages', state.session['page_id'], 'children'], page, true);
    });

    this.stepMove = function(data, layout){
        var path_parts = data.item_id.split('_');
        var item_id_id = path_parts[path_parts.length - 1];
        var item_id = data.parent_path + '_' + item_id_id;
        if (item_id != data.item_id && !layout[item_id] && layout[data.item_id]) {
            layout[item_id] = JSON.parse(JSON.stringify(layout[data.item_id]));
            delete layout[data.item_id];
        }
        layout[item_id].path = item_id;
        layout[item_id].parent = data.col_id;
        dv_cash('#'+data.parent_path).children('.gr-col-content').children('*').each(function(child_id, child){
            if(layout[child.id]){
                layout[child.id].sort_order = child_id;
            } else if (data.item_id == child.id) {
                layout[item_id].sort_order = child_id;
            }
        });

        path_parts.pop();
        var oldParentId = path_parts.join('_');
        var step = document.getElementById(data.item_id);
        
        if (step && oldParentId != data.parent_path && data.event?.detail?.origin?.itemsBeforeUpdate?.find((e) => e?.id === data.item_id)) {
            var indexEl = data.event?.detail?.origin?.itemsBeforeUpdate?.findIndex((e) => e?.id === data.item_id);
            var prevEl = data.event?.detail?.origin?.items[indexEl];
            if (!prevEl) {
                data.event?.detail?.origin?.container?.appendChild(step);
            } else {
                data.event?.detail?.origin?.container?.insertBefore(step, prevEl);
            }
        }
        
        return layout;
    };
})(qc);