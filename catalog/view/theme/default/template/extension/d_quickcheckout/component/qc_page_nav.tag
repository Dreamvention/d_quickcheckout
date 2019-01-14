<qc_page_nav>

    <qc_page_nav_setting if={riot.util.tags.selectTags().search('"qc_page_nav_setting"') > 0 && getState().edit} page_id="{opts.page_id}" page="{opts.page}"></qc_page_nav_setting>

    <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_page_nav_setting"') < 0 && getState().edit}></qc_pro_label>


    <a class="process-page-edit {(opts.page.display == '1') ? '' : 'process-page-hidden'}" onclick={pageOpen} if={getState().edit} >
        <div class="text">{opts.page.text}</div>
        <div class="description">{opts.page.description}</div>
        <span class="qc-badge ve-badge ve-badge--warning {(opts.page.display == '1') ? 've-hidden' : ''}">{getLanguage().general.text_hidden}<span></div>
    </a>

    <a if={ !getState().edit } onclick={(opts.status == '2') ? pageOpen : ''}>
        <div class="process-page-label">{opts.page.text}</div>
        <div class="process-page-progress ve-hidden--sm">
            <div class="process-page-progress-bar"></div>
        </div>
        <div class="process-page-dot ve-hidden--sm"></div>
        <div class="process-page-description">{opts.page.description}</div>
    </a>

    <script>
        this.mixin({store:d_quickcheckout_store});
        this.setting_id = 'qc_page_nav_setting_'+ opts.page_id;

        var tag = this;

        pageOpen(e){
            this.store.dispatch('page/open', { page_id: this.opts.page_id, sort_order: this.opts.sort_order });
        }

    </script>
</qc_page_nav>