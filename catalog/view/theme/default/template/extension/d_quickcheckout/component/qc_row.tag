<qc_row class="gr gr-row gr-has-controls">
    <virtual if={getState().edit}>
        <div class="gr-control gr-row-control">
            <a class="gr-btn gr-add-col" onclick={addCol}><i class="fa fa-clone"></i> Add column</a> 
            <a class="gr-btn gr-remove-row" onclick={removeRow}><i class="fa fa-times"></i></a>
            <span class="gr-label">ROW</span>
        </div>
        <div class="gr-row-border-top"></div>
        <div class="gr-row-border-right"></div>
        <div class="gr-row-border-bottom"></div>
        <div class="gr-row-border-left"></div>
        <div class="gr-row-content qc-row">
            <div 
                each={ child in getChildrens() }
                id={child.path}
                col={child}
                col_id={child.id}
                key="id"
                class="qc-col-sm-{ child.size } gr gr-col"
                no-reorder
                data-is="qc_col">
            </div>
        </div>
    </virtual>
    <div if={!getState().edit} class="qc-row">
        <div 
        each={ child in sortLayoutChildrens(opts.row.children) }
        id={child.path}
        col={child}
        col_id={child.id}
        key="id"
        class="qc-col-sm-{ child.size }"
        data-is="qc_col">
        </div>
    </div>

    <script>
        this.mixin({store:d_quickcheckout_store});
        var tag = this;
        var state = this.store.getState();
        var row = opts.row;

        getChildrens(){
            var newRow = false;
            var pages = tag.store.getState().layout.pages;
            for (i in row.pagePath) {
                if (!newRow) {
                    newRow = tag.store.getState().layout.pages[row.pagePath[i]];
                } else {
                    newRow = newRow.children[row.pagePath[i]];
                }
            }
            row = { ...newRow, pagePath: row.pagePath};
            return tag.store.sortLayoutChildrens(row.children).map(function (c) {
                return {...c, pagePath: [...row.pagePath, c.id]}
            });
        }

        addCol(){
            var sort_order = tag.store.countItems(tag.opts.row.children);
            tag.store.dispatch('col/add', {parent_path: tag.opts.row.path, parent : tag.opts.row.id, sort_order: sort_order});
            tag.update();
        }

        removeRow(){
            tag.store.dispatch('row/remove', {path: tag.opts.row.path, row_id: tag.opts.row_id});
            tag.parent.update();
        }
    </script>
</qc_row>