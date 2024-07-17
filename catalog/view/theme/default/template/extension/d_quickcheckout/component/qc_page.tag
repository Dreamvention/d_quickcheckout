<qc_page>
    <div id="page_{opts.page_id}" class="tab-pane page">
        <virtual if={getState().edit}>
            <div if={hasPayment()} class="alert alert-warning">{getLanguage().general.text_no_payment_step}</div>
            <div class="grid gr gr-active" data-grid-id={opts.page_id}>
                <div class="gr-grid-content" if={opts.page}>
                    <div 
                    each={ child, index in getChildrens() }
                    no-reorder
                    id={child.id}
                    row={child}
                    row_id={child.id}
                    data-is="qc_row"
                    ></div>
                </div>
                <div class="gr-grid-control">
                    <a class="gr-btn gr-add-row gr-new-row" onclick={addRow}><i class="fa fa-plus"></i> Add Row</a>
                </div>
            </div>
        </virtual>
        <virtual if={!getState().edit}>
            <div 
            each={ child, index in sortLayoutChildrens(opts.page.children)}
            no-reorder
            id={child.id}
            row={child}
            row_id={child.id}
            data-is="qc_row"
            ></div>
        </virtual>
    </div>
    <script>
        this.mixin({store:d_quickcheckout_store});
        var tag = this;
        var state = this.store.getState();
        var page = opts.page;

        getChildrens(){
            //so that does not update full riot
            var newPage = false;
            var pages = tag.store.getState().layout.pages;
            for (i in page.pagePath) {
                if (!newPage) {
                    newPage = tag.store.getState().layout.pages[page.pagePath[i]];
                } else {
                    newPage = newRow.children[page.pagePath[i]];
                }
            }
            page = { ...newPage, pagePath: page.pagePath};
            return tag.store.sortLayoutChildrens(page.children).map(function (c) {
                return {...c, pagePath: [...page.pagePath, c.id]}
            });
        }

        addRow(){
            var sort_order = tag.store.countItems(opts.page.children);
            tag.store.dispatch('row/add', {parent : tag.opts.page_id, sort_order: sort_order});
            this.update();
        }

        this.on('mount', function(){
            this.store.hideSpinner();
        })
        
    </script>
</qc_page>