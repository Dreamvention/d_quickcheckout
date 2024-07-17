<qc_custom_field>
    <qc_setting 
        if={getState().edit}
        setting_id={ opts.setting_id }
        title={ opts.title }>
        <div class="ve-editor__setting__content__section">
            
            <div class="ve-field">
                <p if={ parent.locationLimit() } class="ve-alert ve-alert--info">This step allows only { parent.locationLimit() } custom fields to be shown</p>
                <h3 class="ve-h3">Select custom field</h3>
                <div 
                    each={option in getState().custom_fields }
                    class="ve-card add-custom-field"
                    if={option && parent.validateLocation(option.location) }
                    onclick={parent.parent.addCustomField}
                    custom_field_id={option.custom_field_id}>
                    <div class="panel-heading"><i class="fa fa-plus"></i> { option.name } </div>
                    
                </div>
            </div>
            <div class="ve-field">
                <a class="ve-btn ve-btn--primary ve-btn--block" href="{getState().customFieldAdmin}" target="_blank">Create Custom Field</a>
            </div>
            <yield/>
        </div>
    </qc_setting>

    <button class="ve-btn ve-btn--primary ve-btn--block custom-field-create" onclick={toggleSetting}>Add custom field</button>

    <script>
        this.mixin({store:d_quickcheckout_store});
        var tag = this;

        var custom_fields = [];
        locationLimit(){
            if(tag.opts.location_account && tag.opts.location_address){
                return false;
            }
            if(tag.opts.location_account){;
                return 'account';
            }
            if(tag.opts.location_address){
                return 'address';
            }
        }

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
            if(this.store.getState().displaySetting[tag.opts.setting_id]){
                tag.store.hideSetting()
            }else{
                tag.store.showSetting(tag.opts.setting_id);
            }
            tag.store.getCustomField(undefined, function () {
                tag.update();
            });
        }

        close(){
            tag.store.hideSetting();
            tag.update();
        }

        addCustomField(e){
            var custom_field_id = dv_cash(e.currentTarget).attr('custom_field_id');
            var step = tag.opts.step;

            tag.store.addCustomField(step, custom_field_id);
            tag.parent.update();
            tag.opts.onchange();
            tag.store.initFieldSortable(step);
        }

        editCheckbox(e){
            var checkbox = dv_cash(e.currentTarget).find('input[type=checkbox]');
            checkbox.prop("checked", !checkbox.prop("checked"));
            tag.store.dispatch(tag.opts.step+'/edit', serializeJSON(Array.from(dv_cash(tag.root).find('.field-setting'))));
        }
    </script>
</qc_custom_field>