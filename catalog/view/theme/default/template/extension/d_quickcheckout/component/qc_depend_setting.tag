<qc_depend_setting>
    <hr/>
    <div>
        <div class="ve-btn ve-btn--danger ve-btn--sm ve-pull-right" onclick={removeDepend} data-depend_id={opts.depend_id}>{getLanguage().general.text_remove}</div>
        <h3>{stripTags(getLanguage()[parent.opts.step][field.text])} ({field.id}) </h3>
    </div>
    <br/>
    <div class="depend-values">
        <div 
            each={depend_value, depend_value_id in opts.depend}
            class="ve-well ve-well--primary">
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_value}</label>
                <div class="ve-input-group">
                    <input 
                    if={ parent.field.type != 'radio' && parent.field.type != 'select' && parent.field.type != 'checkbox'}
                    onchange="{parent.opts.edit}" 
                    type="text" 
                    class="ve-input" 
                    name="config[{getAccount()}][{parent.opts.step}][fields][{ parent.opts.field_id }][depends][{parent.opts.depend_id}][{depend_value_id}][value]" 
                    value={ depend_value.value } />

                    <select
                        if={parent.field.type== 'radio' || parent.field.type == 'select'}
                        name="config[{getAccount()}][{parent.opts.step}][fields][{ parent.opts.field_id }][depends][{parent.opts.depend_id}][{depend_value_id}][value]"
                        class="ve-input"
                        onchange={parent.opts.edit}>
                        <option value="">{getLanguage().general.text_select}</option>
                        <option
                            each={option in parent.getField().options }
                            if={option}
                            value={ option.value }
                            selected={ option.value == parent.depend_value.value} >
                            { option.name }
                        </option>
                    </select>

                    <select
                        if={parent.field.type == 'checkbox'}
                        name="config[{getAccount()}][{parent.opts.step}][fields][{ parent.opts.field_id }][depends][{parent.opts.depend_id}][{depend_value_id}][value]"
                        class="ve-input"
                        onchange={parent.opts.edit}>
                        <option value="0" selected={ depend_value.value == 0}>{getLanguage().general.text_not_checked}</option>
                        <option value="1" selected={ depend_value.value == 1}>{getLanguage().general.text_checked}</option>
                        
                    </select>
                    <div class="ve-btn ve-btn--danger" onclick={parent.removeDependValue} data-depend_id={parent.opts.depend_id}  data-depend_value_id={depend_value_id}><i class="fa fa-times"></i></div>
                </div>
            </div>
                
            <div class="row">
                <div class="ve-field col-md-6">
                    <label class="ve-label">{getLanguage().general.text_display}</label>
                    <div>
                        <qc_switcher onclick="{parent.opts.edit}" name="config[{getAccount()}][{parent.opts.step}][fields][{ parent.opts.field_id }][depends][{parent.opts.depend_id}][{depend_value_id}][display]" data-label-text="Enabled" value="{ depend_value.display }" />
                    </div>
                </div>

                <div class="ve-field col-md-6">
                    <label class="ve-label">{getLanguage().general.text_require}</label>
                    <div>
                        <qc_switcher onclick="{parent.opts.edit}" name="config[{getAccount()}][{parent.opts.step}][fields][{ parent.opts.field_id }][depends][{parent.opts.depend_id}][{depend_value_id}][require]" data-label-text="Enabled" value="{ depend_value.require }" />
                    </div>
                </div>
            </div>
        </div>
        <div class="ve-btn ve-btn--primary ve-btn--block ve-btn--sm" onclick={addDependValue} data-depend_id={opts.depend_id}>{getLanguage().general.text_add}</div>
        
    </div>
    <script>
        this.mixin({store:d_quickcheckout_store});
        var tag = this;

        getField(){
            return this.store.getConfig()[tag.opts.step].fields[tag.opts.depend_id];
        }

        tag.field = this.getField();

        removeDepend(e){
            var depend_id = $(e.currentTarget).data('depend_id');
            tag.store.dispatch('field/removeDepend', {step_id : tag.opts.step, field_id: tag.opts.field_id , depend_id: depend_id } );
        }

        addDependValue(e){
            var depend_id = $(e.currentTarget).data('depend_id');
            tag.store.dispatch('field/addDependValue', {
                step_id : tag.opts.step, 
                field_id: tag.opts.field_id, 
                depend_id: depend_id 
            } );
        }

        removeDependValue(e){
            var depend_id = $(e.currentTarget).data('depend_id');
            var depend_value_id = $(e.currentTarget).data('depend_value_id');
            tag.store.dispatch('field/removeDependValue', {
                step_id : tag.opts.step, 
                field_id: tag.opts.field_id, 
                depend_id: depend_id,
                depend_value_id: depend_value_id
            } );
        }

        
    </script>
</qc_depend_setting>