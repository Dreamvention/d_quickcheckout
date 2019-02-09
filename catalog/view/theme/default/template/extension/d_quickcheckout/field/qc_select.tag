<qc_field_select>

    <qc_select_setting if={riot.util.tags.selectTags().search('"qc_select_setting"') && getState().edit} field_id="{opts.field_id}" field="{opts.field}" step="{opts.step}" ondelete="{opts.ondelete}"></qc_select_setting>

    <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_select_setting"') < 0 && getState().edit}></qc_pro_label>

    <div if={ isVisible() }  class="field-sortable d-vis ve-clearfix { (opts.error && isRequired()) ? 've-field--error' : ''}">
        <label class="{ (getStyle() == 'list') ? 'col-half' : 'col-full'} ve-label" for="{ opts.step }_{ opts.field.id }">
            { getLanguage()[opts.step][opts.field.text] } 
            <span if={ isRequired() } class="require">*</span>
            <i class="fa fa-question-circle" ref="tooltip" data-placement="top" title="{ getLanguage()[opts.step][opts.field.tooltip] } " if={ getLanguage()[opts.step][opts.field.tooltip] }></i>
        </label>
        <div class="{ (getStyle() == 'list') ? 'col-half' : 'col-full'} qc-select">
            <select
                if={!getState().edit }
                id="{ opts.step }_{ opts.field.id }"
                name="{ opts.step }[{ opts.field.id }]"
                ref="input"
                class="ve-input d-vis qc-select{ (getState().config.guest[opts.step].fields[opts.field_id].search == 1) ? 'selectpicker' : ''} { (opts.field.require) ? 'qc-required' : 'qc-not-required'} { opts.field.id }"
                required="{ opts.field.require }"
                autocomplete="{ opts.field.autocomplete }"
                onchange={change} >
                <option value="" selected={ isEmpty(opts.riotValue) ? true : null} >{ getLanguage()[opts.step][opts.field.placeholder] }</option>
                <option
                    each={option in opts.field.options }
                    if={option}
                    value={ option.value }
                    selected={ (option.value == parent.opts.riotValue) ? true : null} >
                    { option.name } 
                </option>
            </select>
            <i class="qc-select-placeholder">{ getName() } </i>

            <select
                if={getState().edit }
                disabled=disabled
                class="ve-input d-vis  { (opts.field.require) ? 'qc-required' : 'qc-not-required'} { opts.field.id }"
                no-reorder>
                <option if={ opts.field.custom !=1 } value="" selected={ opts.riotValue == 0} >{ getLanguage()[opts.step][opts.field.placeholder] }</option>
                <option
                    each={option in opts.field.options }
                    if={option}
                    value={ option.value }
                    selected={ option.value == parent.opts.riotValue} >
                    { option.name } 
                </option>
            </select>
            <div if={opts.error && opts.field.require == 1} class="ve-help ve-text-danger">{getLanguage()[opts.step][opts.error]}</div>
        </div>
    </div>

    <div class="no-display" if={ (!isVisible() && getState().edit && typeof opts.field.display !== 'undefined') }>
        <label class="col-full" >{ getLanguage()[opts.step][opts.field.text] } <div class="ve-pull-right"><span class="ve-badge ve-badge--warning">{getLanguage().general.text_hidden}<span></div></label>
    </div>

    <script>
        this.mixin({store:d_quickcheckout_store});
        this.setting_id = opts.step +'_'+ opts.field_id +'_setting';

        var tag = this;

        getValue(){
            return this.store.getSession()[tag.opts.step][tag.opts.field_id];
        }

        getName(){
            var result = opts.field.options.filter(function(item){
                if(item.value == tag.tag_value){
                    return item.name
                }
            })
            if(result[0]){
                return result[0].name;
            }
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

        getStyle(){
            var field = tag.store.getState().config.guest[tag.opts.step].fields[tag.opts.field_id];
            return field.style;
        }

        isVisible(){
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
            error = this.store.validate($(e.currentTarget).val(), this.opts.field.errors);
            this.store.dispatch(this.opts.step+'/error', { 'field_id' : this.opts.field_id, 'error': error});

            this.store.dispatch(this.opts.step+'/update', $(e.currentTarget).serializeJSON());
        }

        initTooltip(){
            $(this.refs.tooltip).tooltip('destroy')
            setTimeout(function(){
                $(this.refs.tooltip).tooltip();
            }.bind(this), 300)
        }

        this.on('mount', function(){
            this.initTooltip();
            if(this.store.getState().config.guest[tag.opts.step].fields[tag.opts.field_id].search == 1){
                $(tag.root).find('.selectpicker').selectpicker({
                    style: 've-input ve-selectpicker',
                    size: 12,
                    liveSearch: true,
                    noneSelectedText: ''
                });
            }
        })

        this.on('update', function(){
            this.initTooltip();
            if(this.store.getState().config.guest[tag.opts.step].fields[tag.opts.field_id].search == 1){
                setTimeout(function(){
                    $(tag.root).find('.selectpicker').val(tag.tag_value).selectpicker('refresh');
                },1)
            }
        })

    </script>
</qc_field_select>