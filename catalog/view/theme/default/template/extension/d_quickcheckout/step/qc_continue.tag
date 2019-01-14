<qc_continue>
    <div class="step">

        <qc_continue_setting if={riot.util.tags.selectTags().search('"qc_continue_setting"') && getState().edit} step="{opts.step}"></qc_continue_setting>

        <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_continue_setting"') < 0 && getState().edit}></qc_pro_label>

        <!-- Step -->
        <!-- style card -->
        <div if={(getConfig().continue.display == 1 && getState().config.guest.continue.style == 'card')} class="ve-card">
            <div class="ve-card__header">
                <h4 class="ve-h4">
                    <span if={ getConfig().continue.icon } class="icon">
                        <i class="{ getConfig().continue.icon }"></i>
                    </span>
                    { getLanguage().continue.heading_title } 
                </h4>
                <p class="ve-p" if={getLanguage().continue.text_description}>{  getLanguage().continue.text_description } </p>
            </div>
            <div class="ve-card__section">
                <a if={prev == 1} class="ve-btn d-vis ve-btn--default ve-btn--lg ve-pull-left qc-page-link" onclick={prevPage}>{getLanguage().continue.text_prev}</a>
                <button if={next == 1} disabled={getSession().confirm.loading == 1} class="ve-btn d-vis ve-btn--primary ve-btn--lg ve-pull-right qc-page-link" onclick={nextPage}>{getLanguage().continue.text_next}</button>
            </div>
        </div>

        <!-- style clear -->
        <div if={ getConfig().continue.display == 1 && getState().config.guest.continue.style == 'clear' } class="ve-mb-3 ve-clearfix">
            <p class="ve-p" if={getLanguage().continue.text_description}>{  getLanguage().continue.text_description } </p>
            <a if={prev == 1} class="ve-btn d-vis ve-btn--default ve-btn--lg ve-pull-left qc-page-link" onclick={prevPage}>{getLanguage().continue.text_prev}</a>
            <button if={next == 1} disabled={getSession().confirm.loading == 1} class="ve-btn d-vis ve-btn--primary ve-btn--hg ve-pull-right qc-page-link" onclick={nextPage}>{getLanguage().continue.text_next}</button>
        </div>


        <!-- Hidden Step -->
        <div show={(getConfig().continue.display != 1 && getState().edit)}>
            <div class="ve-card" style="opacity: 0.5">
                <div class="ve-card__header"> 
                    { getLanguage().continue.heading_title } 
                    <div class="ve-pull-right"><span class="ve-badge ve-badge--warning">{getLanguage().general.text_hidden}<span></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        this.mixin({store:d_quickcheckout_store});

        var tag = this;

        tag.prev = this.store.getSession().pages[0] != this.store.getSession().page_id && this.store.getState().config.guest.continue.buttons.prev.display == 1;
        tag.next = this.store.getSession().pages[this.store.getSession().pages.length-1] != this.store.getSession().page_id;

        nextPage(e){
            this.store.dispatch('continue/next');
        }

        prevPage(e){
            this.store.dispatch('continue/prev');
        }

        this.on("update", function(){
            tag.prev = this.store.getSession().pages[0] != this.store.getSession().page_id && this.store.getState().config.guest.continue.buttons.prev.display == 1;
            tag.next = this.store.getSession().pages[this.store.getSession().pages.length-1] != this.store.getSession().page_id;
        });

    </script>
</qc_continue>