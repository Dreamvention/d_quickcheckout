<qc_confirm>
    <div class="step">

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
                <a onclick={ confirm } disabled={getSession().confirm.loading == 1} class="ve-btn d-vis ve-btn--primary ve-btn--hg ve-pull-right {(getSession().confirm.loading == 1)? 've-btn--loading' : ''} ">{getLanguage().confirm.button_confirm}</a>
            </div>
        </div>

        <!-- style clear -->
        <div if={ getConfig().confirm.display == 1 && getState().config.guest.confirm.style == 'clear' } class="ve-mb-3 ve-clearfix">
                <p class="ve-p" if={getLanguage().confirm.text_description}>{  getLanguage().confirm.text_description } </p>
                <a onclick={ confirm } disabled={getSession().confirm.loading == 1} class="ve-btn d-vis ve-btn--primary ve-btn--hg ve-pull-right {(getSession().confirm.loading == 1)? 've-btn--loading' : ''} ">{getLanguage().confirm.button_confirm}</a>
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

        confirm(){
            this.store.dispatch('confirm/confirm');
            return false;
        }
    </script>
</qc_confirm>
