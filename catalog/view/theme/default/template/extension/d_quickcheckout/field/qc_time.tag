<qc_field_time>

    <qc_time_setting if={riot.util.tags.selectTags().search('"qc_time_setting"') && getState().edit} field_id="{opts.field_id}" field="{opts.field}" step="{opts.step}" ondelete="{opts.ondelete}"></qc_time_setting>

    <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_time_setting"') < 0 && getState().edit}></qc_pro_label>

    <div if={ isVisible() } class="field-sortable d-vis  ve-clearfix  { (opts.error && isRequired()) ? 've-field--error' : ''}">
        <label class="{ (getStyle() == 'list') ? 'col-half' : 'col-full'} ve-label" for="{ opts.step }_{ opts.field_id }">
            { getLanguage()[opts.step][opts.field.text] }
            <span if={ (opts.field.require == 1) } class="require">*</span>
            <span data-balloon-pos="up" aria-label="{ getLanguage()[parent.opts.step][opts.field.tooltip] }" if={ getLanguage()[opts.step][opts.field.tooltip] }><i class="fa fa-question-circle"></i></span>
        </label>

        <div class="{ (getStyle() == 'list') ? 'col-half' : 'col-full'}">
            <div if={!getState().edit } class="ve-input-group">
                <label type="button" class="ve-btn d-vis ve-btn--default {isMobile() ? 've-hidden' : ''}" for="{ opts.step }_{ opts.field.id }"><i class="fa fa-calendar"></i></label>
                <input
                    type="text"
                    id="{ opts.step }_{ opts.field.id }"
                    name="{ opts.step }[{ opts.field.id }]"
                    class="ve-input d-vis { (opts.field.mask) ?  'qc-mask': '' } { opts.field.type } validate { isRequired() ? 'qc-required' : 'qc-not-required'} { opts.field.id }"
                    value="{ opts.riotValue }"
                    no-reorder
                    autocomplete="{ opts.field.autocomplete }"
                    qc-mask="{ opts.field.mask }"
                    data-date-format="{opts.field.format}"
                    placeholder={ getLanguage()[opts.step][opts.field.placeholder] }
                    onchange={change} />
            </div>
            <div if={getState().edit } class="ve-input-group">
                <label class="ve-btn d-vis ve-btn--default"><i class="fa fa-calendar"></i></label>
                <input 
                    class="ve-input"
                    type="text"
                    placeholder={ getLanguage()[opts.step][opts.field.placeholder] }
                    disabled=true
                    />
            </div>
            <div if={opts.error && isRequired()} class="ve-help ve-text-danger">{ getLanguage()[opts.step][opts.error] }</div>
        </div>
    </div>
    
    <div class="no-display" if={ (!isVisible() && getState().edit && typeof opts.field.display !== 'undefined') }>
        <label class="col-full" >{ getLanguage()[opts.step][opts.field.text] } <div class="ve-pull-right"><span class="ve-badge ve-badge--warning">{getLanguage().general.text_hidden}<span></div></label>
    </div>

    <script>
        this.mixin({store:d_quickcheckout_store});
        this.setting_id = opts.step +'_'+ opts.field_id +'_setting';

        var tag = this;

        /*getValue(){
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
        }*/

        getStyle(){
            var field = tag.store.getState().config.guest[tag.opts.step].fields[tag.opts.field_id];
            return field.style;
        }

        isVisible(){
            var field = tag.store.getConfig()[tag.opts.step].fields[tag.opts.field_id];

            for (var depend_field_id in field.depends) {
                
                var field_value = tag.store.getSession()[tag.opts.step][depend_field_id];

                for (var depend_field_value_id in field.depends[depend_field_id]){
                    var depend_field_value = field.depends[depend_field_id][depend_field_value_id].value;

                    if(depend_field_value == field_value){
                        return (field.depends[depend_field_id][depend_field_value_id].display == 1);
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
                    var depend_field_value = field.depends[depend_field_id][depend_field_value_id].value;

                    if(depend_field_value == field_value){
                        return (field.depends[depend_field_id][depend_field_value_id].require == 1);
                    }
                }

            }

            return tag.store.getConfig()[tag.opts.step].fields[tag.opts.field_id].require == 1;
        }

        change(e){
            error = this.store.validate(dv_cash(e.currentTarget).val(), this.opts.field.errors);
            this.store.dispatch(this.opts.step+'/error', { 'field_id' : this.opts.field_id, 'error': error});
            this.store.dispatch(this.opts.step+'/update', serializeJSON(e.currentTarget));
        }

        initMask(){
            if(this.opts.field.mask && document.getElementById(this.opts.step + '_' + this.opts.field_id)){
                IMask(document.getElementById(this.opts.step + '_' + this.opts.field_id), this.opts.field.mask);
            }
        }

        initTime(){    
            var locale = this.store.getSession().language.split('-');
            locale = locale[0];        
            d_quickcheckout_flatpickr('#' + this.opts.step + '_' + this.opts.field_id, {
                locale: locale,
                enableTime: true,
                noCalendar: true,
                time_24hr: true,
                onChange: function(selectedTime, dateTime, e) {
                    error = tag.store.validate(dv_cash(e.input).val(), tag.opts.field.errors);
                    tag.store.dispatch(tag.opts.step+'/error', { 'field_id' : tag.opts.field_id, 'error': error});
                    tag.store.dispatch(tag.opts.step+'/update', serializeJSON(e.input));
                }
            });
        }

        this.on('mount', function(){
            this.initTime();
            this.initMask();
        });
    </script>
</qc_field_time>
