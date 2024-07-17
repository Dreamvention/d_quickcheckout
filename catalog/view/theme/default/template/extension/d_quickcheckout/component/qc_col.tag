<qc_col>
    <virtual if={getState().edit}>
        <qc_setting setting_id="{opts.col_id}_setting" title="Blocks">
            <div class="qc-setting-panels">
                <a each={ step, step_id in getState().steps } onclick={parent.parent.addStep} data-name={step}  class="qc-setting-panel add-step">
                    <div class="qc-setting-panel-content">
                        <div class="qc-setting-panel-heading"><img if={getLanguage()[step]} src="{getLanguage()[step]['image']}" /></div>
                        <div class="qc-setting-panel-body"> {getLanguage()[step] &&  getLanguage()[step]['heading_title']  ? getLanguage()[step]['heading_title'] : step }</div>
                    </div>
                </a>
            </div>
        </qc_setting>
        <div class="gr-control gr-col-control">
            <a class="gr-btn gr-add-row" onclick={addSubRow}><i class="fa fa-clone"></i> Add subrow</a> 
            <a class="gr-btn gr-remove-col" onclick={removeCol}><i class="fa fa-times"></i></a>
            <span class="gr-label">COLUMN</span>
        </div>
        <div class="gr-col-border-right"></div>
        <div class="gr-col-border-bottom"></div>
        <div class="gr-col-border-left"></div>
        <div class="gr-col-content ui-sortable">
            <div 
            each={child in getChildrens()}
            if={child}
            id={child.path}
            key="id"
            sort_order={child.sort_order}
            class="{(child.children) ? '' : 'gr-item'}"
            data-name="{child.name}">
                <div if={(child.type == 'item')} data-is={'qc_'+child.name}></div>
                <qc_row if={(child.type != 'item')} 
                    id={child.path}
                    row={child}
                    row_id={child.id}
                    class="gr">
                </qc_row>
            </div>

            <div 
            if={getChildrens().length < 1}>
                
            </div>
        </div>
        <div class="gr-control gr-col-control">
            <a class="gr-btn gr-add-item gr-btn-block" onclick={showColSettings}><i class="fa fa-plus"></i> Add block</a> 
        </div>
    </virtual>
    <virtual if={!getState().edit}>
        <div 
        each={child in sortLayoutChildrens(opts.col.children)}
        if={child}
        id={child.path}
        key="id"
        data-name="{child.name}">
            <div if={(child.type == 'item')} data-is={'qc_'+child.name}></div>
            <qc_row if={(child.type != 'item')} 
                id={child.path}
                row={child}
                row_id={child.id}
                class="qc-row gr">
            </qc_row>
        </div>
    </virtual>
    <script>
        this.mixin({store:d_quickcheckout_store});
        var tag = this;
        var state = this.store.getState();
        var col = opts.col;
        var sortUpdated = function (event) {
            tag.store.dispatch('step/move', {event: event, item_id : dv_cash(event.detail.item).attr('id'), parent_path: tag.opts.col.path, col_id: tag.opts.col.id, row_id: tag.opts.col.parent});
        };

        getChildrens(){
             //so that does not update full riot
            var newCol = false;
            var pages = tag.store.getState().layout.pages;
            for (i in col.pagePath) {
                if (!newCol) {
                    newCol = tag.store.getState().layout.pages[col.pagePath[i]];
                } else {
                    newCol = newCol.children[col.pagePath[i]];
                }
            }
            col = { ...newCol, pagePath: col.pagePath};
            return tag.store.sortLayoutChildrens(col.children).map(function (c) {
                return {...c, pagePath: [...col.pagePath, c.id]}
            });
        }

        addStep(e){
            var sort_order = tag.store.countItems(tag.opts.col.children);
            tag.store.dispatch('step/add', { name: dv_cash(e.currentTarget).data('name'),  parent_path: tag.opts.col.path, parent: tag.opts.col_id, sort_order: sort_order });
            tag.update();
            tag.store.hideSetting();
        }

        showColSettings(){
            if(dv_cash('#'+opts.col_id+'_setting').hasClass('show')){
                tag.store.hideSetting();
                //tag.store.updateState(['session', 'col_id'], 0);
            }else{
                tag.store.showSetting(opts.col_id+'_setting');
                //tag.store.updateState(['session', 'col_id'], tag.opts.col_id, false);
            }
            tag.update();
        }

        removeCol(){
            var sort_order = tag.store.countItems(tag.parent.opts.row.children);
            tag.store.dispatch('col/remove', {path: tag.opts.col.path, col_id: tag.opts.col_id, sort_order: sort_order});
            tag.parent.update();
            
        }

        addSubRow(){
            var sort_order = tag.store.countItems(tag.opts.col.children);
            tag.store.dispatch('row/add', {parent_path: tag.opts.col.path, parent : tag.opts.col.id, sort_order: sort_order});
            tag.update();
        }
        this.step_move_timer = null;
        
        this.on('mount', function(){

            if(getState().edit){
                dv_cash(tag.root).addClass('gr-sortable');
                var items = dv_cash(tag.root).children('.gr-col-content');
                
                d_quickcheckout_sortable(items, {
                    handle: '.handle-sortable',
                    acceptFrom: '.gr-col-content',
                    placeholderClass: 'gr-placeholder',
                }).forEach(item => {  
                    dv_cash(item).off();
                    dv_cash(item).on('sortupdate', sortUpdated);
                });

                var options = {
                    gridRows: 12,
                    cellMin: 2,
                    handles: 'e'
                }

                if(!dv_cash(tag.root).hasClass('gr-resizable')){
                    var $parent = dv_cash(tag.root).parent();
                    var cellWidth = ($parent.width()/options.gridRows);

                    //fix width of hidden div bug
                    if(cellWidth > 0){
                        tag.store.updateState(['cellWidth'], cellWidth, false);
                    }else{
                        var state = tag.store.getState();
                        cellWidth = state.cellWidth;
                    }
                    var cellMin = cellWidth * options.cellMin;

                    dv_cash(tag.root).addClass('gr-resizable');
                    dv_cash(tag.root).append('<div class="ui-resizable-handle ui-resizable-e"></div>');

                    d_quickcheckout_interact(tag.root).resizable({
                        preserveAspectRatio: false,
                        edges: { left: false, right: true, bottom: false, top: false }
                    })
                    .on('resizemove', function (event) {
                        var target = event.target;

                        // update the element's style
                        
                        var width = Math.round(event.rect.width/cellWidth);

                        if (width > 12) {
                            width = 12;
                        }
                        
                        var col = 1;

                        while (col <= 12) {
                            dv_cash(target).removeClass('qc-col-sm-'+col);
                            col++;
                        }
                        dv_cash(target).addClass('qc-col-sm-'+width);
                    })
                    .on('resizeend', function (event) {
                        var target = event.target;

                        // update the element's style
                        
                        var width = Math.round(event.rect.width/cellWidth);
                        if (width > 12) {
                            width = 12;
                        }
                        
                        tag.store.dispatch('col/resize', {item_id : dv_cash(target).prop('id'),  width: width, col_id: tag.opts.col_id, row_id: tag.opts.parent});
                    });
                }
            }
        });

        this.on('updated', function(){
            if(getState().edit){
                var items = dv_cash(tag.root).children('.gr-col-content');
                dv_cash(tag.root).off('sortupdate', dv_cash(tag.root).children('.gr-col-content'), sortUpdated);
                d_quickcheckout_sortable(items, 'destroy');
                d_quickcheckout_sortable(items, {
                    handle: '.handle-sortable',
                    acceptFrom: '.gr-col-content',
                    placeholderClass: 'gr-placeholder',
                }).forEach(item => {
                    dv_cash(item).off();
                    dv_cash(item).on('sortupdate', sortUpdated);
                });
            }
        });
        
    </script>
</qc_col>