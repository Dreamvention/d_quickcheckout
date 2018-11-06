<qc_page>
    <div id="page_{opts.page_id}" class="tab-pane page">
        <virtual if={getState().edit}>
            <div if={hasPayment()} class="alert alert-warning">{getLanguage().general.text_no_payment_step}</div>
            <div class="grid gr gr-active" data-grid-id={opts.page_id}>
                <div class="gr-grid-content" if={opts.page}>
                    <div 
                    each={ row, row_id in opts.page.children }
                    no-reorder
                    id={row_id}
                    row={row}
                    row_id={row_id}
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
            each={ row, row_id in opts.page.children }
            no-reorder
            id={row_id}
            row={row}
            row_id={row_id}
            data-is="qc_row"
            ></div>
        </virtual>
    </div>
    <script>
        this.mixin({store:d_quickcheckout_store});
        var tag = this;
        var state = this.store.getState();

        addRow(){
            var sort_order = tag.store.countItems(opts.page.children);
            tag.store.dispatch('row/add', {parent : tag.opts.page_id, sort_order: sort_order});
        }

        this.on('mount', function(){
            this.store.hideSpinner();
        })
        
    </script>
</qc_page>