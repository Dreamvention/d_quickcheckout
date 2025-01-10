<qc_col>
    <virtual if={getState().edit}>
        <qc_setting if={getState().edit} setting_id="{opts.col_id}_setting" title="Blocks">
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
            each={item_id in sortItems(opts.col.children)}
            if={opts.col.children[item_id]}
            id={item_id}
            sort_order={parent.opts.col.children[item_id].sort_order}
            class="{(parent.opts.col.children[item_id].children) ? '' : 'gr-item'}"
            data-name="{parent.opts.col.children[item_id].name}">
                <div if={(parent.opts.col.children[item_id].type == 'item')} data-is={'qc_'+parent.opts.col.children[item_id].name}></div>
                <qc_row if={(parent.opts.col.children[item_id].type != 'item')} 
                    id={item_id}
                    row={parent.opts.col.children[item_id]}
                    row_id={item_id}
                    class="gr">
                </qc_row>
            </div>

            <div 
            if={store.countItems(opts.col.children) < 1}>
                
            </div>
        </div>
        <div class="gr-control gr-col-control">
            <a class="gr-btn gr-add-item gr-btn-block" onclick={showColSettings}><i class="fa fa-plus"></i> Add block</a> 
        </div>
    </virtual>
    <virtual if={!getState().edit}>
        <div 
        each={item_id in sortItems(opts.col.children)}
        if={opts.col.children[item_id]}
        id={item_id}
        data-name="{parent.opts.col.children[item_id].name}">
            <div if={(parent.opts.col.children[item_id].type == 'item')} data-is={'qc_'+parent.opts.col.children[item_id].name}></div>
            <qc_row if={(parent.opts.col.children[item_id].type != 'item')} 
                id={item_id}
                row={parent.opts.col.children[item_id]}
                row_id={item_id}
                class="qc-row gr">
            </qc_row>
        </div>
    </virtual>
    <script>
        this.mixin({store:ajax_quick_checkout_store});
        var tag = this;
        var state = this.store.getState();

        addStep(e){
            var sort_order = tag.store.countItems(tag.opts.col.children);
            tag.store.dispatch('step/add', { name: e.currentTarget.getAttribute('data-name'), parent: tag.opts.col_id, sort_order: sort_order });
            tag.store.hideSetting();
        }

        showColSettings(){
            if(document.getElementById(opts.col_id+'_setting').classList.contains('show')){
                tag.store.hideSetting();
                tag.store.updateState(['session', 'col_id'], 0);
            }else{
                tag.store.showSetting(opts.col_id+'_setting');
                tag.store.updateState(['session', 'col_id'], tag.opts.col_id);
            }
        }

        removeCol(){
            var sort_order = tag.store.countItems(tag.parent.opts.row.children);
            tag.store.dispatch('col/remove', {col_id: tag.opts.col_id, sort_order: sort_order});
        }

        addSubRow(){
            var sort_order = tag.store.countItems(tag.opts.col.children);
            tag.store.dispatch('row/add', {parent : tag.opts.col.id, sort_order: sort_order});
        }

        this.step_move_timer = null;
        
        this.on('mount', function(){

            if(getState().edit){
                tag.root.classList.add('gr-sortable');
                var items = Array.from(tag.root.childNodes).filter(function(el) {
                    if (el.classList && el.classList.contains('gr-col-content')) {
                        return true;
                    }
                    return false;
                });
                d_quickcheckout_sortable(items, {
                    handle: 'div:not(.ui-resizable-handle)',
                    acceptFrom: '.gr-col-content',
                    placeholderClass: 'gr-placeholder'
                }).forEach(item => {
                    item.addEventListener('sortupdate', function (event) {
                        tag.store.dispatch('step/move', {item_id : event.detail.item.id, col_id: tag.opts.col.id, row_id: tag.opts.col.parent});
                    });
                });

                var options = {
                    gridRows: 12,
                    cellMin: 2,
                    handles: 'e'
                }

                if(!tag.root.classList.contains('gr-resizable')){
                    var $parent = tag.root.parentNode;
                    var cellWidth = ($parent.clientWidth/options.gridRows);

                    //fix width of hidden div bug
                    if(cellWidth > 0){
                        tag.store.updateState(['cellWidth'], cellWidth);
                    }else{
                        var state = tag.store.getState();
                        cellWidth = state.cellWidth;
                    }
                    var cellMin = cellWidth * options.cellMin;

                    tag.root.classList.add('gr-resizable');
                    tag.root.insertAdjacentHTML('beforeend', '<div class="ui-resizable-handle ui-resizable-e"></div>');

                    d_quickcheckout_interact(tag.root).resizable({
                        preserveAspectRatio: false,
                        edges: { left: false, right: true, bottom: false, top: false }
                    })
                    .on('resizemove', function (event) {
                        var target = event.target;

                        // update the element's style
                        
                        var width = Math.round(event.rect.width/cellWidth);
                        Array.from(target.classList).forEach(function(className){
                            var matches = className.match (/(^|\s)qc-col-md-\S+/g);
                            if (matches?.length) {
                                matches.forEach(function(classMatch) {
                                    target.classList.remove(classMatch);
                                });
                            }  
                        });
                        target.classList.add('qc-col-md-'+width);
                        
                        tag.store.dispatch('col/resize', {item_id : target.id, width: width, col_id: tag.opts.col_id, row_id: tag.opts.parent});
                    });
                }
            }
        });
        
    </script>
</qc_col>