<qc_field_checkbox>
    <qc_checkbox_setting if={riot.util.tags.selectTags().search('"qc_checkbox_setting"') && getState().edit} field_id="{opts.field_id}" field="{opts.field}" step="{opts.step}" ondelete="{opts.ondelete}"></qc_checkbox_setting>

    <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_checkbox_setting"') < 0 && getState().edit}></qc_pro_label>

    <div if={ isVisible() } class="field-sortable d-vis ve-clearfix { (opts.error && opts.field.require == 1) ? 've-field--error' : ''}">
        <form class="col-full">
            <label for="{ opts.step }_{ opts.field.id }" class="ve-checkbox {opts.riotValue == 1 ? 'qc-checkbox-selected' : '' }" >
                <input
                    type="hidden"
                    name="{ opts.step }[{ opts.field.id }]"
                    value="0" />
                <input
                    type="checkbox"
                    id="{ opts.step }_{ opts.field.id }"
                    ref="input"
                    name="{ opts.step }[{ opts.field.id }]"
                    class="ve-input validate { (opts.field.require) ? 'qc-required' : 'qc-not-required'}"
                    value="1"
                    no-reorder
                    checked={ opts.riotValue == 1 }
                    { (opts.field.require) ? 'qc-required' : ''}
                    onchange={change} />
                <i></i>
                <span { (opts.field.tooltip) ? 'data-toggle="tooltip"' : '' } title="{ opts.field.tooltip }">
                    <qc_raw content="{ getLanguage()[opts.step][opts.field.text] }"></qc_raw>
                    <span if={ (opts.field.require == 1) } class="require">*</span>
                    <i class="fa fa-question-circle" ref="tooltip" data-placement="top" title="{ getLanguage()[opts.step][opts.field.tooltip] } " if={ getLanguage()[opts.step][opts.field.tooltip] }></i>
                </span>
            </label>
            <div if={opts.error && opts.field.require == 1} class="ve-help ve-text-danger">{ getLanguage()[opts.step][opts.error] }</div>
        </form>
    </div>
    <div class="no-display" if={ (!isVisible() && getState().edit && typeof opts.field.display !== 'undefined') }>
        <label class="col-full" ><qc_raw content="{ getLanguage()[opts.step][opts.field.text] }"></qc_raw><div class="ve-pull-right"><span class="ve-badge ve-badge--warning">{getLanguage().general.text_hidden}<span></div></label>
    </div>
    <script>
        this.mixin({store:d_quickcheckout_store});
        this.setting_id = opts.step +'_'+ opts.field_id +'_setting';

        var tag = this;

        getValue(){
            return this.store.getSession()[tag.opts.step][tag.opts.field_id];
        }

        getTagError(){
            if(this.store.isEmpty(this.store.getError()[tag.opts.step])){ 
                return '' ;
            }
            return this.store.getError()[tag.opts.step][tag.opts.field_id];
        }

        getTagConfig(){
            return JSON.stringify(this.store.getConfig()[tag.opts.step].fields[tag.opts.field_id]);
        }
        
        tag.tag_value = this.getValue();
        tag.tag_error = this.getTagError();
        tag.tag_config = this.getTagConfig();

        shouldUpdate(){
            if(this.store.getState().edit){
                return true;
            }
            if(tag.tag_value == this.getValue() && tag.tag_error == this.getTagError() && tag.tag_config == this.getTagConfig()) {
                return false;
            }else{
                tag.tag_value = this.getValue();
                tag.tag_error = this.getTagError();
                tag.tag_config = this.getTagConfig();
                return true;
            }
        }

        edit(e){
            this.store.dispatch(this.opts.step+'/edit', $('#'+ tag.setting_id).find('form').serializeJSON());
        }

        isVisible(){
            if( !this.store.getState().edit
            && tag.opts.step == 'payment_address' 
            && tag.opts.field_id == 'shipping_address'
            && this.store.getConfig().shipping_address.display == '0'
            && !this.store.getSession().has_shipping){
                return false;
            }

            var field = tag.store.getConfig()[tag.opts.step].fields[tag.opts.field_id];

            for (var depend_field_id in field.depends) {
                
                var field_value = tag.store.getSession()[tag.opts.step][depend_field_id];

                for (var depend_field_value_id in field.depends[depend_field_id]){
                    var depend_field_value = field.depends[depend_field_id][depend_field_value_id].value

                    if(depend_field_value == field_value){
                        return (field.depends[depend_field_id][depend_field_value_id].display == 1)
                    }
                }

            }

            return tag.store.getConfig()[tag.opts.step].fields[tag.opts.field_id].display == 1;
        }



        isRequired(){
            var field = tag.store.getConfig()[tag.opts.step].fields[tag.opts.field_id];

            for (var depend_field_id in field.depends) {
                
                var field_value = tag.store.getSession()[tag.opts.step][depend_field_id];

                for (var depend_field_value_id in field.depends[depend_field_id]){
                    var depend_field_value = field.depends[depend_field_id][depend_field_value_id].value

                    if(depend_field_value == field_value){
                        return (field.depends[depend_field_id][depend_field_value_id].require == 1)
                    }
                }

            }

            return tag.store.getConfig()[tag.opts.step].fields[tag.opts.field_id].require == 1;
        }

        change(e){
            error = this.store.validate($(e.currentTarget).prop('checked'), this.opts.field.errors);
            this.store.dispatch(this.opts.step+'/error', { 'field_id' : this.opts.field_id, 'error': error});
            this.store.dispatch(this.opts.step+'/update', $(e.currentTarget).parents('form').serializeJSON());
        }

        initTooltip(){
            $(this.refs.tooltip).tooltip('destroy')
            setTimeout(function(){
                $(this.refs.tooltip).tooltip();
            }.bind(this), 300)
        }

        this.on('mount', function(){
            this.initTooltip();

        })

        this.on('updated', function(){
            this.initTooltip();
        })
    </script>
</qc_field_checkbox>