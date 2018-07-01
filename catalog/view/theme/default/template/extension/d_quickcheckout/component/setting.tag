<setting>

    <div class="setting" id="{opts.setting_id}">
        <div class="setting-header">
            <h2>{opts.title} {getLanguage().general.text_settings}</h2>
            <a class="layout" onclick={toggle}>
                <i class="fa fa-minus-square" ></i>
            </a>
            <a class="close" onclick={close}>
                <i class="fa fa-times" ></i>
            </a>
        </div>
        <div class="setting-content">
            
            <form class="form-setting" ref="form">
                <yield/>
            </form>
            
        </div>
        <div class="setting-footer" >
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

        //NEEDS REFACTOR
        this.on('mount', function(){
            $('.setting').appendTo('body');
        })
    </script>
</setting>