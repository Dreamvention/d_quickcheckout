<qc_confirm>
    <div class="step">

        <qc_confirm_setting if={riot.util.tags.selectTags().search('"qc_confirm_setting"') && getState().edit} step="{opts.step}"></qc_confirm_setting>

        <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_confirm_setting"') < 0 && getState().edit}></qc_pro_label>

        <!-- Step -->
        <div class="panel panel-default" show={ getConfig().confirm.display == 1 }>
            <div class="panel-heading">
                <h4 class="panel-title">
                    <span class="icon">
                        <i class="{ getConfig().confirm.icon }"></i>
                    </span>
                    { getLanguage().confirm.heading_title }
                </h4>
                <h5 if={getLanguage().confirm.text_description}>{  getLanguage().confirm.text_description } </h5>
            </div>
            <div class="panel-body">
                <a onclick={ confirm } disabled={getSession().confirm.loading == 1} class="btn btn-primary btn-lg">{getLanguage().confirm.button_confirm}</a>
            </div>
        </div>

        <!-- Hidden Step -->
        <div show={(getConfig().confirm.display != 1 && getState().edit)}>
            <div class="panel panel-default" style="opacity: 0.5">
                <div class="panel-heading">{ getLanguage().confirm.heading_title } <div class="pull-right"><span class="label label-warning">{getLanguage().general.text_hidden}<span></div></div>
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
