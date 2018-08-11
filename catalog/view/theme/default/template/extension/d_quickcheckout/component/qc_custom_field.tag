<qc_custom_field>
    <div class="qc qc-setting" id="{opts.setting_id}" >
        <div class="qc-setting-header">
            <h2>{opts.title} {getLanguage().general.text_settings}</h2>
            <a class="close" onclick={close}>
                <i class="fa fa-times" ></i>
            </a>
        </div>
        <div class="qc-setting-content">
            <form class="form-setting" ref="form">
                <h3 >select custom field</h3>
                <div 
                    each={option in getState().custom_fields }
                    class="panel panel-default add-custom-field"
                    if={option && validateLocation(option.location) }
                    onclick={addCustomField}
                    custom_field_id={option.custom_field_id}>
                    <div class="panel-heading"><i class="fa fa-plus"></i> { option.name } </div>
                    
                </div>
                <yield/>
            </form>
        </div>
        <div class="qc-setting-footer" >
            <a onclick={save}>{getLanguage().general.text_update}</a>
        </div>
    </div>

    <button class="btn btn-primary btn-block custom-field-create" onclick={toggleSetting}>Add custom field</button>

    <script>
        this.mixin({store:d_quickcheckout_store});
        var tag = this;

        var custom_fields = [];

        validateLocation(location){
            if(tag.opts.location_account && location == 'account'){
                return true;
            }
            if(tag.opts.location_address && location == 'address'){
                return true;
            }
            return false
        }

        toggleSetting(e){
            if($('#'+ this.opts.setting_id).hasClass('show')){
                tag.store.hideSetting()
            }else{
                tag.store.showSetting(tag.opts.setting_id);
            }

            tag.store.getCustomField();
        }

        close(){
            tag.store.hideSetting();
        }

        addCustomField(e){
            var custom_field_id = $(e.currentTarget).attr('custom_field_id');
            var step = tag.opts.step;

            tag.store.addCustomField(step, custom_field_id);

            tag.opts.onchange();
        }

        editCheckbox(e){
            var checkbox = $(e.currentTarget).find('input[type=checkbox]');
            checkbox.prop("checked", !checkbox.prop("checked"));
            tag.store.dispatch(tag.opts.step+'/edit', $(tag.root).find('.field-setting').serializeJSON());
        }

        this.on('mount', function(){
            $(this.root).find('.qc-setting').appendTo('body');
        })
    </script>
</qc_custom_field>