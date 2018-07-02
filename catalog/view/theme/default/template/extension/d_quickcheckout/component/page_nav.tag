<page_nav>

    <page_nav_setting if={riot.util.tags.selectTags().search('"page_nav_setting"') > 0 && getState().edit} setting_id="page_nav_setting_{opts.page_id}" page_id="{opts.page_id}" page="{opts.page}"></page_nav_setting>

    <pro_label if={ riot.util.tags.selectTags().search('"page_nav_setting"') < 0 && getState().edit}></pro_label>


    <a class="process-page-edit {(opts.page.display == '1') ? '' : 'process-page-hidden'}" onclick={pageOpen} if={getState().edit} >
        <div class="text">{opts.page.text}</div>
        <div class="description">{opts.page.description}</div>
        <span class="label label-warning {(opts.page.display == '1') ? 'hidden' : ''}">{getLanguage().general.text_hidden}<span></div>
    </a>

    <div if={ !getState().edit }>
        <div class="process-page-label">{opts.page.text}</div>
        <div class="process-page-progress">
            <div class="process-page-progress-bar"></div>
        </div>
        <a onclick={(opts.status == '2') ? pageOpen : ''} class="process-page-dot"></a>
        <div class="process-page-description">{opts.page.description}</div>
    </div>

    <script>
        this.setting_id = 'page_nav_setting_'+ opts.page_id;

        var tag = this;

        pageOpen(e){
            this.store.dispatch('page/open', { page_id: this.opts.page_id, sort_order: this.opts.sort_order });
        }

    </script>
</page_nav>