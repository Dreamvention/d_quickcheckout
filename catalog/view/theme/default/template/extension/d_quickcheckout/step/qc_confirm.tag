<qc_confirm>
    <div class="step qc-confirm">

        <qc_confirm_setting if={riot.util.tags.selectTags().search('"qc_confirm_setting"') && getState().edit} step="{opts.step}"></qc_confirm_setting>

        <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_confirm_setting"') < 0 && getState().edit}></qc_pro_label>

        <!-- Step -->
        <!-- style card -->
        <div class="ve-card" if={ getConfig().confirm.display == 1 && getState().config.guest.confirm.style == 'card'}>
            <div class="ve-card__header">
                <h4 class="ve-h4">
                    <span if={ getConfig().confirm.icon } class="icon">
                        <i class="{ getConfig().confirm.icon }"></i>
                    </span>
                    { getLanguage().confirm.heading_title }
                </h4>
                <p class="ve-p" if={getLanguage().confirm.text_description}>{  getLanguage().confirm.text_description } </p>
            </div>
            <div class="ve-card__section">
                <button if={prev == 1} class="ve-btn d-vis ve-btn--default ve-btn--lg ve-pull-left qc-page-link" onclick={prevPage}>{getLanguage().confirm.text_prev}</button>
                <button if={next == 1} disabled={getSession().confirm.loading == 1} class="ve-btn d-vis ve-btn--primary ve-btn--hg ve-pull-right qc-page-link {(getSession().confirm.loading == 1)? 've-btn--loading' : ''}" onclick={nextPage}>{getLanguage().confirm.text_next}</button>
                <button if={confirm == 1} onclick={ confirmCheckout } disabled={getSession().confirm.loading == 1} class="ve-btn d-vis ve-btn--primary ve-btn--hg ve-pull-right {(getSession().confirm.loading == 1)? 've-btn--loading' : ''} ">{getLanguage().confirm.button_confirm}</button>
            </div>
        </div>

        <!-- style clear -->
        <div if={ getConfig().confirm.display == 1 && getState().config.guest.confirm.style == 'clear' } class="ve-mb-3 ve-clearfix">
            <p class="ve-p" if={getLanguage().confirm.text_description}>{  getLanguage().confirm.text_description } </p>
            <button if={prev == 1} class="ve-btn d-vis ve-btn--default ve-btn--lg ve-pull-left qc-page-link" onclick={prevPage}>{getLanguage().confirm.text_prev}</button>
            <button if={next == 1} disabled={getSession().confirm.loading == 1} class="ve-btn d-vis ve-btn--primary ve-btn--hg ve-pull-right qc-page-link {(getSession().confirm.loading == 1)? 've-btn--loading' : ''}" onclick={nextPage}>{getLanguage().confirm.text_next}</button>
            <button if={confirm == 1} onclick={ confirmCheckout } disabled={getSession().confirm.loading == 1} class="ve-btn d-vis ve-btn--primary ve-btn--hg ve-pull-right {(getSession().confirm.loading == 1)? 've-btn--loading' : ''} ">{getLanguage().confirm.button_confirm}</button>
        </div>

        <!-- Hidden Step -->
        <div show={(getConfig().confirm.display != 1 && getState().edit)}>
            <div class="ve-card" style="opacity: 0.5">
                <div class="ve-card__header">{ getLanguage().confirm.heading_title } <div class="ve-pull-right"><span class="ve-badge ve-badge--warning">{getLanguage().general.text_hidden}<span></div></div>
            </div>
        </div>
    </div>
    <script>
        this.mixin({store:d_quickcheckout_store});

        var tag = this;
        var pages = this.store.getSession().pages.filter(page => page != false );

        tag.prev = pages[0] != this.store.getSession().page_id && this.store.getState().config.guest.confirm.buttons.prev.display == 1;
        tag.next = pages[pages.length-1] != this.store.getSession().page_id;
        tag.confirm = pages[pages.length-1] == this.store.getSession().page_id;

        confirmCheckout(){
            this.store.dispatch('confirm/confirm');
            return false;
        }
        
        nextPage(e){
            this.store.dispatch('confirm/next');
        }

        prevPage(e){
            this.store.dispatch('confirm/prev');
        }

        this.on("update", function(){
            tag.prev = pages[0] != this.store.getSession().page_id && this.store.getState().config.guest.confirm.buttons.prev.display == 1;
            tag.next = pages[pages.length-1] != this.store.getSession().page_id;
            tag.confirm = pages[pages.length-1] == this.store.getSession().page_id;
        });
    </script>
</qc_confirm>
