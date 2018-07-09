<setting>

    <div class="qc qc-setting" id="{opts.setting_id}">
        <div class="qc-setting-header">
            <h2>{opts.title} {getLanguage().general.text_settings}</h2>
            <a class="layout" onclick={toggle}>
                <i class="fa fa-minus-square" ></i>
            </a>
            <a class="close" onclick={close}>
                <i class="fa fa-times" ></i>
            </a>
        </div>
        <div class="qc-setting-content">
            
            <form class="form-setting" ref="form">
                <yield/>
            </form>
            
        </div>
        <div class="qc-setting-footer" >
            <a onclick={save}>{getLanguage().general.text_update}</a>
        </div>
    </div>

    <script>
        var tag = this;

        save(){
            this.store.dispatch('setting/save', $('.form-setting').serializeJSON());
        }

        close(){
            this.store.hideSetting();
        }

        toggle(){
            this.store.toggleSetting();
        }

        changeAccount(e){
            this.store.dispatch('account/update', { account: $(e.currentTarget).find('input').val()});
        }

        this.on('mount', function(){
            $(this.root).find('.qc-setting').appendTo('body');
        })
    </script>
</setting>