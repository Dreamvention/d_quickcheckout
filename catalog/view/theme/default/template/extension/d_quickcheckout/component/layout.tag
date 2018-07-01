<layout>
    <layout_setting if={getState().edit} ></layout_setting>

    <div if={getState().layout.pages}>
        
        <div class="process page-nav clearfix">
            <page_nav 
            each={ page_id, i in page_ids}
            if={ getLayout().pages[page_id] && getLayout().pages[page_id].display && getLayout().pages[page_id].deleted != "1" && (getLayout().pages[page_id].display == '1' || getState().edit) }
            page_id={ getLayout().pages[page_id].id } 
            page={getLayout().pages[page_id]}
            sort_order={getLayout().pages[page_id].sort_order}
            id="page_nav_{page_id}"
            status={ getSession().page_id == page_id ? '1' : (i < parent.getCurrentPageIndex() ) ? '2' : '0'}
            class="{ getSession().page_id == page_id ? 'active' : (i < parent.getCurrentPageIndex() ) ? 'complete' : 'disabled'} process-page page-nav-item" ></page_nav>

            <page_nav_add if={riot.util.tags.selectTags().search('"page_nav_add"') && getState().edit}></page_nav_add>
        </div>

        <div class="tab-content pages clearfix">
            <page 
            each={ page_id, i in page_ids }
            no-reorder
            id="{page_id}" 
            page_id="{page_id}" 
            page={getLayout().pages[page_id]}
            class="tab-pane page { getSession().page_id == page_id ? 'active' : ''}">
                
            </page>
        </div>

    </div>

    <div if={!getState().layout.pages}> 
        <a class="btn btn-default" onclick="{addPage}">{getLanguage().general.text_add_page}</a>

        <ul class="nav nav-tabs page-nav">
            <li class="active"><a class="page-nav-item" data-toggle="tab" href="#page_0">{getLanguage().general.text_page_1}</a></li>
        </ul>

        <page id="page_0" page_id="0" class="tab-pane active page"></page>
    </div>

    <script>

        var tag = this; 

        this.page_ids = this.store.getPageIds();

        getCurrentPageIndex(){
            return this.store.getPageIds().indexOf(getSession().page_id);
        }  


        this.store.hideSpinner();
       
        this.on('updated', function(){
            this.page_ids = this.store.getPageIds();
        })

       
    </script>
</layout>