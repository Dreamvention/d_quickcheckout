<qc_layout_setting>
    <link rel="stylesheet" href="{'catalog/view/theme/default/stylesheet/d_quickcheckout/skin/'+getLayout().skin+'/'+getLayout().skin+'.css?'+rand()}">
    
    <qc_setting 
    if={getState().edit} 
    setting_id={setting_id}
    title={getLanguage().general.text_general}>

    <ul class="qc-setting-tabs ve-tab ve-tab--block">
            <li class="qc-tab ve-tab__item active">
                <a href="#{ opts.setting_id }_general" id="{ opts.setting_id }_home_tab" role="tab" data-toggle="tab" aria-controls="{ setting_id }_general" aria-expanded="true">{getLanguage().general.text_general}</a>
            </li>
            <li class="qc-tab ve-tab__item">
                <a href="#{ opts.setting_id }_error" id="{ opts.setting_id }_error_tab" role="tab" data-toggle="tab" aria-controls="{ opts.setting_id }_error" aria-expanded="true">{getLanguage().general.text_css}</a>
            </li>
            <li class="qc-tab ve-tab__item">
                <a href="#{ opts.setting_id }_advanced" id="{ opts.setting_id }_advanced_tab" role="tab" data-toggle="tab" aria-controls="{ opts.setting_id }_advanced" aria-expanded="true">{getLanguage().general.text_script}</a>
            </li>
            <!--  <li class="ve-tab__item">
                <a href="#{ opts.setting_id }_design" id="{ opts.setting_id }_design_tab" role="tab" data-toggle="tab" aria-controls="{ opts.setting_id }_design" aria-expanded="true">{getLanguage().general.text_design}</a>
            </li>  -->
        </ul>
        <div class="qc-setting-tab-content"> 
            <div class="qc-setting-tab-pane fade in active" role="tabpanel" id="{ opts.setting_id }_general" aria-labelledby="{ opts.setting_id }_general_tab"> 
                <div class="ve-editor__setting__content__section">
                    <div class="ve-field">
                        <label class="ve-label">{getLanguage().general.text_display} {getLanguage().general.text_header_footer}</label>
                        <div>
                            <qc_switcher 
                            onclick="{parent.edit}" 
                            name="layout[header_footer]" 
                            data-label-text="Enabled" 
                            value={ getLayout().header_footer } />
                        </div>
                    </div>

                    <div class="ve-field">
                        <label class="ve-label">{getLanguage().general.text_loading}</label>
                        <div class="ve-input-group">
                            <span class="ve-input-group__addon">
                                <img src="{getLanguage().general.img}">
                            </span>
                            <input onchange="{parent.edit}" type="text" class="ve-input" name="language[general][text_loading]" value={ getLanguage().general.text_loading } />
                        </div>
                    </div>

                    <div class="ve-field">
                        <label class="ve-label"> {getLanguage().general.text_layout}</label><br/>
                        <select
                            class="ve-input"
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

                    <div class="ve-field">
                        <label class="ve-label"> {getLanguage().general.text_skin}</label><br/>
                        <select
                            class="ve-input"
                            onchange="{parent.changeSkin}" >
                            <option
                                each={skin in getState().skins }
                                if={skin}
                                value={ skin }
                                selected={ skin == getLayout().skin} >
                                { skin } 
                            </option>
                        </select>
                    </div>
                </div>
                <hr class="ve-hr">
                <div class="ve-editor__setting__content__section">
                    <div class="ve-field">
                        <label class="ve-label"> {getLanguage().general.text_reset}</label><br/>
                        <a class="ve-btn ve-btn--danger" onclick={parent.resetState}>{getLanguage().general.text_reset}</a>
                    </div>
                </div>
            </div>
            <div class="qc-setting-tab-pane fade" role="tabpanel" id="{ opts.setting_id }_error" aria-labelledby="{ opts.setting_id }_error_tab">
                <div class="ve-editor__setting__content__section">
                    <label>Input your custom CSS here </label>
                    <textarea name="layout[css]" class="ve-input" rows="10" onchange="{parent.edit}" >{getLayout().css}</textarea>
                </div>
            </div> 

            <div class="qc-setting-tab-pane fade" role="tabpanel" id="{ opts.setting_id }_advanced" aria-labelledby="{ opts.setting_id }_design_tab">
                <div class="ve-editor__setting__content__section">
                    <label>Input your custom JavaScript here </label>
                    <textarea name="layout[js]" class="ve-input" rows="10" onchange="{parent.edit}" >{getLayout().js}</textarea>
                </div>
            </div>
        </div>
    </qc_setting>

    
    <div class="qc-editor ve-editor__menu" if={getState().edit}>
        <div class="ve-editor__menu__title">
            <h1 class="ve-h1">{getLanguage().general.text_editor_title} {getSession().setting_name}</h1>
        </div>
        <div class="ve-editor__menu__control qc-editor-pro" if={ !getState().pro }>
            <qc_pro_label></qc_pro_label>
        </div>
        <div class="ve-editor__menu__control" if={Object.keys(getState().languages).length  > 1}>
            <div class="ve-btn-group" data-toggle="buttons">
                <label each={language, language_id in getState().languages} class="ve-btn ve-btn--lg ve-btn--primary { getSession().language == language_id ?  'active' : '' }" onclick={changeLanguage}>
                    <input class="ve-input" type="radio" name="language" value="{language_id}" id="{language_id}" autocomplete="off" checked={ getSession().language == language_id }> {language.name}
                </label>
            </div>
        </div>
        <div class="ve-editor__menu__control">
            <a class="ve-btn ve-btn--lg ve-btn--primary" onclick={toggleSetting}><i class="fa fa-cog"></i></a>
            <a class="ve-btn ve-btn--lg ve-btn--success" onclick={saveState}>{getLanguage().general.text_update}</a>
            <a class="ve-btn ve-btn--lg ve-btn--danger" href="{this.store.getState().close}" target="_parent"><i class="fa fa-times fa-close"></i></a>
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