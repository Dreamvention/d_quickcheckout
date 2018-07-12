<qc_continue>
    <div class="step">

        <qc_continue_setting if={riot.util.tags.selectTags().search('"qc_continue_setting"') && getState().edit} step="{opts.step}"></qc_continue_setting>

        <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_continue_setting"') < 0 && getState().edit}></qc_pro_label>

        <!-- Step -->
        <div show={(getConfig().continue.display == 1)} class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    { getLanguage().continue.heading_title } 
                </h4>
                <h5 if={getLanguage().continue.text_description}>{  getLanguage().continue.text_description } </h5>
            </div>
            <div class="panel-body">
                <a if={prev == 1} class="btn btn-default btn-lg pull-left page-link" onclick={prevPage}>{getLanguage().continue.text_prev}</a>
                <button if={next == 1} disabled={getSession().confirm.loading == 1} class="btn btn-primary btn-lg pull-right page-link" onclick={nextPage}>{getLanguage().continue.text_next}</button>
            </div>
            
        </div>

        <!-- Hidden Step -->
        <div show={(getConfig().continue.display != 1 && getState().edit)}>
            <div class="panel panel-default" style="opacity: 0.5">
                <div class="panel-heading"> 
                    { getLanguage().continue.heading_title } 
                    <div class="pull-right"><span class="label label-warning">{getLanguage().general.text_hidden}<span></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        this.mixin({store:d_quickcheckout_store});

        var tag = this;

        tag.prev = this.store.getSession().pages[0] != this.store.getSession().page_id && getConfig().continue.buttons.prev.display == 1;
        tag.next = this.store.getSession().pages[this.store.getSession().pages.length-1] != this.store.getSession().page_id;

        nextPage(e){
            this.store.dispatch('continue/next');
        }

        prevPage(e){
            this.store.dispatch('continue/prev');
        }

        this.on("update", function(){
            tag.prev = this.store.getSession().pages[0] != this.store.getSession().page_id && getConfig().continue.buttons.prev.display == 1;
            tag.next = this.store.getSession().pages[this.store.getSession().pages.length-1] != this.store.getSession().page_id;
        });

    </script>
</qc_continue>