<qc_field_tel>

    <qc_tel_setting if={riot.util.tags.selectTags().search('"qc_tel_setting"') && getState().edit} field_id="{opts.field_id}" field="{opts.field}" step="{opts.step}" ondelete="{opts.ondelete}"></qc_tel_setting>

    <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_tel_setting"') < 0 && getState().edit}></qc_pro_label>

    <div if={ isVisible() } class="field-sortable d-vis  ve-clearfix  { (opts.error && opts.field.require == 1) ? 've-field--error' : ''}">
        <label class="{ (getStyle() == 'list') ? 'col-half' : 'col-full'} ve-label" for="{ opts.step }_{ opts.field.id }">
            { getLanguage()[opts.step][opts.field.text] } 
            <span if={ (opts.field.require == 1) } class="require">*</span>
            <span data-balloon-pos="up" aria-label="{ getLanguage()[opts.step][opts.field.tooltip] } " if={ getLanguage()[opts.step][opts.field.tooltip] }><i class="fa fa-question-circle"></i></span>
        </label>
        <div class="{ (getStyle() == 'list') ? 'col-half' : 'col-full'}">
            <input
                if={!getState().edit }
                type="tel"
                ref="input"
                id="{ opts.step }_{ opts.field.id }"
                name="{ opts.step }[{ opts.field.id }]"
                class="ve-input d-vis { (opts.field.mask) ? 'qc-mask': '' } { (opts.field.require) ? 'qc-required' : 'qc-not-required'} { opts.field.id }"
                value="{ opts.riotValue }"
                autocomplete="{ opts.field.autocomplete }"
                no-reorder
                placeholder={ getLanguage()[opts.step][opts.field.placeholder] }
                qc-mask="{ (opts.field.mask) ? opts.field.mask : '' }"
                onchange={ change }
                />
            <input
                each={error, error_id in opts.field.errors}
                type="hidden"
                class="{ opts.step }-{ opts.field.id }-iso2"
                if={ error.telephone && opts.field.validation == 1 }
                type="tel"
                ref="input"
                name="{ opts.step }[telephone_iso2][{ opts.field.id }]"
                value="{ error.telephone }"
                />

            <input if={getState().edit } 
                class="ve-input"
                type="text"
                placeholder={ getLanguage()[opts.step][opts.field.placeholder] }
                disabled=true
                />
            <div if={opts.error && isRequired()} class="ve-help ve-text-danger">{getLanguage()[opts.step][opts.error]}</div>
        </div>
    </div>

    <div class="no-display" if={ (opts.field.display != 1 && getState().edit && typeof opts.field.display !== 'undefined') }>
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
            if(this.opts.field.validation == '1' && !this.store.getState().edit){
                var iso2 = document.getElementsByClassName(this.opts.step + '-' + this.opts.field.id + '-iso2').length ? document.getElementsByClassName(this.opts.step + '-' + this.opts.field.id + '-iso2')[0] : null;
                if (iso2) iso2.value = intlTelInput(e.currentTarget).getSelectedCountryData()?.iso2 ? intlTelInput(e.currentTarget).getSelectedCountryData().iso2 : ''; 
                
                e.currentTarget.value = intlTelInput(e.currentTarget).getNumber();
            
                if (e.currentTarget.value.trim()) {
                    if (intlTelInput(e.currentTarget).isValidNumber()) {
                        error = this.store.validate(e.currentTarget.value, this.opts.field.errors);
                    } else {
                        error = 'error_telephone_telephone';
                    }
                    this.store.dispatch(this.opts.step+'/error', { 'field_id' : this.opts.field_id, 'error': error });
                }
                
            }

            var data = serializeJSON(e.currentTarget);

            if (iso2) data = d_quickcheckout_lodash.merge(data, serializeJSON(iso2));

            
            this.store.dispatch(this.opts.step+'/update', data);
        }

        initIntlTelInput(){
            if(this.opts.field.validation == '1' && !this.store.getState().edit && document.getElementById(this.opts.step + '_' + this.opts.field_id)){
                var onlyCountries = [];
                if(this.opts.field.countries){
                    onlyCountries = this.opts.field.countries.split(',');
                }
                
                intlTelInput(document.getElementById(this.opts.step + '_' + this.opts.field_id), {
                  initialCountry: "auto",
                  nationalMode: false,
                  onlyCountries: onlyCountries,
                  geoIpLookup: function(callback) {
                    axios.get('https://ipinfo.io').then(function(resp) {
                        var countryCode = (resp?.data && resp.data?.country) ? resp.data.country : "";
                      callback(countryCode);
                    });
                  }
                });
            }
        }

        initMask(){
            if(this.opts.field.mask && document.getElementById(this.opts.step + '_' + this.opts.field_id)){
                IMask(document.getElementById(this.opts.step + '_' + this.opts.field_id), this.opts.field.mask);
            }
        }

        this.on('mount', function(){
            this.initIntlTelInput();
            this.initMask();
        });

        this.on('updated', function(){
            this.initIntlTelInput();
        });
        
    </script>
</qc_field_tel>
