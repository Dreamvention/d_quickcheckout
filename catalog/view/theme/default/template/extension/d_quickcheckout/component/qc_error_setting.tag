<qc_error_setting>
    <hr/>
    <div class="ve-well ve-well--danger">
            <a class="ve-btn ve-btn--danger ve-btn--sm ve-pull-right" onclick={removeError} >{getLanguage().general.text_remove}</a>
   
        
        <virtual each={value, condition in opts.error}>
            <div  if={condition != 'text'}   class="ve-field">
                <h3 class="ve-h3">{getLanguage().general['text_'+condition]}</h3>
            </div>

            <div if={condition == 'text'}  class="ve-field">
                <label class="ve-label">{getLanguage().general['entry_text']}</label>
                <input onchange="{parent.opts.edit}" type="text" class="ve-input" name="language[{parent.parent.opts.step}][{value}]" value={ getLanguage()[parent.opts.step][value] } />

                <input type="hidden" class="ve-input" name="config[{getAccount()}][{parent.opts.step}][fields][{parent.opts.field_id }][errors][{parent.opts.error_id}][text]" value={ value } />
            </div>

            <div if={condition == 'min_length'} class="ve-field">
                <label class="ve-label">{getLanguage().general['entry_min_length']}</label>
                <input onchange="{parent.opts.edit}" type="text" class="ve-input" name="config[{getAccount()}][{parent.opts.step}][fields][{parent.opts.field_id }][errors][{parent.opts.error_id}][min_length]" value={ value } />
            </div>

            <div if={condition == 'max_length'} class="ve-field">
                <label class="ve-label">{getLanguage().general['entry_max_length']}</label>
                <input onchange="{parent.opts.edit}" type="text" class="ve-input" name="config[{getAccount()}][{parent.opts.step}][fields][{parent.opts.field_id }][errors][{parent.opts.error_id}][max_length]" value={ value } />
            </div>

            <div if={condition == 'compare_to'} class="ve-field">
                <label class="ve-label">{getLanguage().general['entry_compare_to']}</label>
                <select onchange="{parent.opts.edit}" type="text" class="ve-input" name="config[{getAccount()}][{parent.opts.step}][fields][{parent.opts.field_id }][errors][{parent.opts.error_id}][compare_to]" value={ value } >
                    <option>{ getLanguage().general.text_select }</option>
                    <option
                        each={field in getConfig().payment_address.fields}
                        value="{parent.parent.opts.step}.{field.id}"
                        selected={(value == parent.parent.opts.step+"."+field.id)}
                        >{stripTags(getLanguage()[parent.parent.opts.step][field.text])} ({getLanguage().payment_address.heading_title})</option>
                    <option
                        each={field in getConfig().shipping_address.fields}
                        value="{parent.parent.opts.step}.{field.id}"
                        selected={(value == parent.parent.opts.step+"."+field.id)}
                        >{stripTags(getLanguage()[parent.parent.opts.step][field.text])} ({getLanguage().shipping_address.heading_title})</option>
                    <option
                        each={field in getConfig().custom.fields}
                        value="{parent.parent.opts.step}.{field.id}"
                        selected={(value == parent.parent.opts.step+"."+field.id)}
                        >{stripTags(getLanguage()[parent.parent.opts.step][field.text])} ({getLanguage().custom.heading_title})</option>
                </select>
            </div>

            <div if={condition == 'not_empty'} class="ve-field">
                <input type="hidden" class="ve-input" name="config[{getAccount()}][{parent.opts.step}][fields][{parent.opts.field_id }][errors][{parent.opts.error_id}][not_empty]" value={ value } />
            </div>
            
            <div if={condition == 'checked'} class="ve-field">
                <input type="hidden" class="ve-input" name="config[{getAccount()}][{parent.opts.step}][fields][{parent.opts.field_id }][errors][{parent.opts.error_id}][checked]" value={ value } />
            </div>

            <div if={condition == 'regex'} class="ve-field">
                <label>{getLanguage().general['entry_regex']}</label>
                <input onchange="{parent.opts.edit}" type="text" class="ve-input" name="config[{getAccount()}][{parent.opts.step}][fields][{parent.opts.field_id }][errors][{parent.opts.error_id}][regex]" value={ value } />
            </div>

            <div if={condition == 'telephone'} class="ve-field">
                <input type="hidden" class="ve-input" name="config[{getAccount()}][{parent.opts.step}][fields][{parent.opts.field_id }][errors][{parent.opts.error_id}][telephone]" value={ value } />
            </div>

            <div if={condition == 'email_exists'}>
                <input type="hidden" class="ve-input" name="config[{getAccount()}][{parent.opts.step}][fields][{parent.opts.field_id }][errors][{parent.opts.error_id}][email_exists]" value={ value } />
            </div>
        </div>
    </virtual>
    <script>
        this.mixin({store:d_quickcheckout_store});
        var tag = this;
        removeError(e){
            var error_id = tag.opts.error_id
            this.store.dispatch('field/removeError', {step_id : this.opts.step, field_id: this.opts.field_id , error_id: error_id } );
        }
    </script>
</qc_error_setting>