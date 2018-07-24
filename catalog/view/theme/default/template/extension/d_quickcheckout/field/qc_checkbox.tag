<qc_field_checkbox>

    <qc_checkbox_setting if={riot.util.tags.selectTags().search('"qc_checkbox_setting"') && getState().edit} field_id="{opts.field_id}" field="{opts.field}" step="{opts.step}" ondelete="{opts.ondelete}"></qc_checkbox_setting>

    <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_checkbox_setting"') < 0 && getState().edit}></qc_pro_label>

    <div if={ (opts.field.display == 1) } class="field-sortable form-group clearfix { (opts.error && opts.field.require == 1) ? 'has-error' : ''}">
        <form class="col-xs-12">
            <div class="qc-checkbox {opts.riotValue == 1 ? 'qc-checkbox-selected' : '' }">
                <label for="{ opts.step }_{ opts.field.id }" class="control-label" >
                    <input
                        type="hidden"
                        name="{ opts.step }[{ opts.field.id }]"
                        value="0" />
                    <input
                        type="checkbox"
                        id="{ opts.step }_{ opts.field.id }"
                        ref="input"
                        name="{ opts.step }[{ opts.field.id }]"
                        class="validate { (opts.field.require) ? 'required' : 'not-required'}"
                        value="1"
                        no-reorder
                        checked={ opts.riotValue == 1 }
                        { (opts.field.require) ? 'required' : ''}
                        onchange={change} />
                    <span { (opts.field.tooltip) ? 'data-toggle="tooltip"' : '' } title="{ opts.field.tooltip }">
                        <qc_raw content="{ getLanguage()[opts.step][opts.field.text] }"></qc_raw>
                        <span if={ (opts.field.require == 1) } class="require">*</span>
                        <i class="fa fa-question-circle" ref="tooltip" data-placement="top" title="{ getLanguage()[opts.step][opts.field.tooltip] } " if={ getLanguage()[opts.step][opts.field.tooltip] }></i>
                    </span>
                </label>
            </div>
        </form>
        <div class="col-md-12 error" if={opts.error && opts.field.require == 1}>
            <div class="text-danger">{ getLanguage()[opts.step][opts.error] }</div>
        </div>
    </div>
    <div class="no-display" if={ (opts.field.display != 1 && getState().edit && typeof opts.field.display !== 'undefined') }>
        <label class="col-md-12" ><raw content="{ getLanguage()[opts.step][opts.field.text] }"/><div class="pull-right"><span class="label label-warning">{getLanguage().general.text_hidden}<span></div></label>
    </div>
    <script>
        this.mixin({store:d_quickcheckout_store});
        this.setting_id = opts.step +'_'+ opts.field_id +'_setting';

        var tag = this;

        edit(e){
            this.store.dispatch(this.opts.step+'/edit', $('#'+ tag.setting_id).find('form').serializeJSON());
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