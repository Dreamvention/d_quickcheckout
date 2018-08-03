<qc_layout_setting>
    <qc_setting 
    if={getState().edit} 
    setting_id={setting_id}
    title={getLanguage().general.text_general} >
        <div class="form-group">
            <label class="control-label">{getLanguage().general.text_display} {getLanguage().general.text_header_footer}</label>
            <div>
                <qc_switcher 
                onclick="{parent.edit}" 
                name="layout[header_footer]" 
                data-label-text="Enabled" 
                value={ getLayout().header_footer } />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{getLanguage().general.text_display} {getLanguage().general.text_breadcrumb}</label>
            <div>
                <qc_switcher 
                onclick="{parent.edit}" 
                name="layout[breadcrumb]" 
                data-label-text="Enabled" 
                value={ getLayout().breadcrumb } />
            </div>
        </div>

        <div class="form-group">
            <label class="control-label"> {getLanguage().general.text_layout}</label><br/>
            <select
                class="form-control"
                onchange="{parent.changeLayout}" >
                <option
                    each={layout in getState().layouts }
                    if={layout}
                    value={ layout }
                    selected={ layout == getLayout().codename} >
                    { layout } 
                </option>
            </select>
        </div>

        <div class="form-group">
            <label class="control-label"> {getLanguage().general.text_skin}</label><br/>
            <select
                class="form-control"
                onchange="{parent.changeSkin}" >
                <option
                    each={skin in getState().skins }
                    if={skin}
                    value={ skin }
                    selected={ skin == getSession().skin} >
                    { skin } 
                </option>
            </select>
        </div>


        <div class="form-group">
            <label class="control-label"> {getLanguage().general.text_reset}</label><br/>
            <a class="btn btn-danger" onclick={parent.resetState}>{getLanguage().general.text_reset}</a>
        </div>
    </qc_setting>

    <div class="qc qc-editor" if={getState().edit}>
        <div class="qc-editor-heading">
            <span>{getLanguage().general.text_editor_title} {getSession().setting_name}</span>
        </div>
        <div class="qc-editor-control">
            <a class="btn btn-lg btn-primary" onclick={toggleSetting}><i class="fa fa-cog"></i></a>
            <a class="btn btn-lg btn-success" onclick={saveState}>{getLanguage().general.text_update}</a>
            <a class="btn btn-lg btn-danger" href="{this.store.getState().close}" target="_parent"><i class="fa fa-times fa-close"></i></a>
        </div>
        <div class="qc-editor-language" if={Object.keys(getState().languages).length  > 1}>
            <div class="btn-group btn-group" data-toggle="buttons">
                <label each={language, language_id in getState().languages} class="btn btn-lg btn-primary { getSession().language == language_id ?  'active' : '' }" onclick={changeLanguage}>
                    <input type="radio" name="language" value="{language_id}" id="{language_id}" autocomplete="off" checked={ getSession().language == language_id }> {language.name}
                </label>
            </div>
        </div>
        <div class="qc-editor-pro" if={ !getState().pro }>
            <qc_pro_label></qc_pro_label>
        </div>
    </div>

    <script>
        this.mixin({store:d_quickcheckout_store});
        var state = this.store.getState();

        this.setting_id = 'layout_setting';
        this.skin = this.store.getSession().skin;

        toggleSetting(e){
            if($('#'+ this.setting_id).hasClass('show')){
                this.store.hideSetting()
            }else{
                this.store.showSetting(this.setting_id);
            }
        }

        edit(e){
            this.store.dispatch('setting/edit', $('#'+this.setting_id).find('form').serializeJSON());
        }

        saveState(e){
            this.store.dispatch('setting/save');
        }

        resetState(e){
            this.store.dispatch('setting/reset');
        }

        changeAccount(e){
            this.store.dispatch('account/update', { account: $(e.currentTarget).find('input').val()});
        }

        changeLanguage(e){
            this.store.dispatch('setting/changeLanguage', { language_id: $(e.currentTarget).find('input').val()});
        }

        changeLayout(e){
            this.store.dispatch('setting/changeLayout', { layout_codename: $(e.currentTarget).val()});
        }

        changeSkin(e){
            this.store.dispatch('setting/changeSkin', { skin_codename: $(e.currentTarget).val()});
        }

        //NEEDS REFACTOR
        this.on('mount', function(){
            $(this.root).find('.qc-editor').appendTo('body');
        })

        this.on('updated', function(){
            if(this.store.getState().edit){
                this.store.updateLayoutStyle();
            }
        })
    </script>

</qc_layout_setting>