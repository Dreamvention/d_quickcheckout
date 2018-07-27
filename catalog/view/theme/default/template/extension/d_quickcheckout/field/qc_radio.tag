<qc_field_radio>

    <qc_radio_setting if={riot.util.tags.selectTags().search('"qc_radio_setting"') && getState().edit} field_id="{opts.field_id}" field="{opts.field}" step="{opts.step}" ondelete="{opts.ondelete}"></qc_radio_setting>

    <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_radio_setting"') < 0 && getState().edit}></qc_pro_label>
    
    <div if={ (opts.field.display == 1) } class="field-sortable form-group clearfix { (opts.error && opts.field.require == 1) ? 'has-error' : ''}">
        <label class="{ (getStyle() == 'list') ? 'col-md-6' : 'col-md-12'} control-label" for="{ opts.step }_{ opts.field.id }">
            { getLanguage()[opts.step][opts.field.text] }
            <span if={ (opts.field.require == 1) } class="require">*</span>
            <i class="fa fa-question-circle" ref="tooltip" data-placement="top" title="{ getLanguage()[opts.step][opts.field.tooltip] } " if={ getLanguage()[opts.step][opts.field.tooltip] }></i>
        </label>
        <div class="{ (getStyle() == 'list') ? 'col-md-6' : 'col-md-12'}" >
            <div each={option in opts.field.options} class="radio-input">
                <label for="{ parent.opts.step }_{ parent.opts.field.id }_{ parent.opts.field.type }_{ option.value }" class="qc-radio { (parent.opts.riotValue == option.value) ? 'qc-radio-selected': '' }">
                    <input
                        type="radio"
                        ref="input"
                        id="{ parent.opts.step }_{ parent.opts.field.id }_{ parent.opts.field.type }_{ option.value }"
                        name="{ parent.opts.step }[{ parent.opts.field.id }]"
                        class="validate { (parent.opts.field.require) ? 'required' : 'not-required'}"
                        value="{ option.value }"
                        no-reorder
                        checked={ (parent.opts.riotValue == option.value) }
                        autocomplete="true"
                        onclick={change}/>
                     { option.name }
                </label>
            </div>
        </div>
        <div class="col-md-12 error" if={opts.error && opts.field.require == 1}>
            <div class="text-danger">{opts.error}</div>
        </div>
    </div>

    <div class="no-display" if={ (opts.field.display != 1 && getState().edit && typeof opts.field.display !== 'undefined') }>
        <label class="col-md-12" >{ getLanguage()[opts.step][opts.field.text] } <div class="pull-right"><span class="label label-warning">{getLanguage().general.text_hidden}<span></div></label>
    </div>

    <script>
        this.mixin({store:d_quickcheckout_store});
        this.setting_id = opts.step +'_'+ opts.field_id +'_setting';

        var tag = this;

        getStyle(){
            var field = tag.store.getConfig()[tag.opts.step].fields[tag.opts.field_id];

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
            this.store.dispatch(this.opts.step+'/update', $(e.currentTarget).serializeJSON());
        }

        initTooltip(){
            $(this.refs.tooltip).tooltip('destroy')
            setTimeout(function(){
                $(this.refs.tooltip).tooltip();
            }.bind(this), 300)
        }

        $(tag.root).on('click', '.qc-radio', function(){
            $(tag.root).find('.qc-radio').removeClass('qc-radio-selected');
            $(this).addClass('qc-radio-selected');
        })

        this.on('mount', function(){
            this.initTooltip();

        })

        this.on('updated', function(){
            this.initTooltip();
            
        })
    </script>
</qc_field_radio>
