<qc_field_datetime>

    <qc_datetime_setting if={riot.util.tags.selectTags().search('"qc_datetime_setting"') && getState().edit} field_id="{opts.field_id}" field="{opts.field}" step="{opts.step}" ondelete="{opts.ondelete}"></qc_datetime_setting>

    <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_datetime_setting"') < 0 && getState().edit}></qc_pro_label>

    <div if={ (opts.field.display == 1) } class="field-sortable d-vis ve-clearfix { (opts.error && opts.field.require == 1) ? 've-field--error' : ''}">
        <label class="{ (getStyle() == 'list') ? 'col-half' : 'col-full'} ve-label" for="{ opts.step }_{ opts.field_id }">
            { getLanguage()[opts.step][opts.field.text] }
            <span if={ (opts.field.require == 1) } class="require">*</span>
            <i class="fa fa-question-circle" ref="tooltip" data-placement="top" title="{ getLanguage()[parent.opts.step][opts.field.tooltip] } " if={ getLanguage()[opts.step][opts.field.tooltip] }></i>
        </label>
        
        <div class="{ (getStyle() == 'list') ? 'col-half' : 'col-full'}">
            <div if={!getState().edit } class="veinput-group">
            <label type="button" class="ve-btn d-vis ve-btn-default {isMobile() ? 've-hidden' : ''}" for="{ opts.step }_{ opts.field.id }"><i class="fa fa-calendar"></i></label>
                <input
                    type="text"
                    id="{ opts.step }_{ opts.field.id }"
                    name="{ opts.step }[{ opts.field.id }]"
                    class="ve-input d-vis { (opts.field.mask) ?  'qc-mask': '' } { opts.field.type } validate { (opts.field.require) ? 'qc-required' : 'qc-not-required'} { opts.field.id }"
                    value="{ opts.riotValue }"
                    no-reorder
                    autocomplete="{ opts.field.autocomplete }"
                    qc-mask="{ opts.field.mask }"
                    data-date-format="{opts.field.format}"
                    placeholder={ getLanguage()[opts.step][opts.field.placeholder] }
                    onchange={change} >
            </div>
            <div if={getState().edit } class="ve-input-group">
                <label class="ve-btn d-vis ve-btn-default"><i class="fa fa-calendar"></i></label>
                <input 
                    class="ve-input"
                    type="text"
                    placeholder={ getLanguage()[opts.step][opts.field.placeholder] }
                    disabled=true
                    />
            </div>
            <div if={opts.error && opts.field.require == 1} class="ve-help ve-text-danger">{ getLanguage()[opts.step][opts.error] }</div>
        </div>
    </div>
    
    <div class="no-display" if={ (opts.field.display != 1 && getState().edit && typeof opts.field.display !== 'undefined') }>
        <label class="col-full" >{ getLanguage()[opts.step][opts.field.text] } <div class="ve-pull-right"><span class="ve-badge ve-badge--warning">{getLanguage().general.text_hidden}<span></div></label>
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
            $('#' + this.opts.step + '_' + this.opts.field_id).focusout();
        }

        initDateTime(){
            $('#' + this.opts.step + '_' + this.opts.field_id).datetimepicker({
                language: this.store.getSession().language,
                pickDate: true,
                pickTime: true
            }).on('dp.change', function(e){
                error = this.store.validate($(e.currentTarget).val(), this.opts.field.errors);
                this.store.dispatch(this.opts.step+'/error', { 'field_id' : this.opts.field_id, 'error': error});
                this.store.dispatch(this.opts.step+'/update', $(e.currentTarget).serializeJSON());
            }.bind(this));
        }

        initMask(){
            if(this.opts.field.mask){
                $('#' + this.opts.step + '_' + this.opts.field_id).mask(this.opts.field.mask);
            }else{
                $('#' + this.opts.step + '_' + this.opts.field_id).unmask();
            }
        }

        initTooltip(){
            $(this.refs.tooltip).tooltip('destroy')
            setTimeout(function(){
                $(this.refs.tooltip).tooltip();
            }.bind(this), 300)
        }

        this.on('mount', function(){
            this.initDateTime();
            this.initMask();
            this.initTooltip();

        })

        this.on('updated', function(){
            this.initDateTime();
            this.initMask();
            this.initTooltip();
            
        })
    </script>
</qc_field_datetime>
