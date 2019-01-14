<qc_field_label>
    <qc_label_setting if={riot.util.tags.selectTags().search('"qc_label_setting"') && getState().edit} field_id="{opts.field_id}" field="{opts.field}" step="{opts.step}" ondelete="{opts.ondelete}"></qc_label_setting>
    
    <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_label_setting"') < 0 && getState().edit}></qc_pro_label>

    <div if={ (opts.field.display == 1) } class="d-vis clearfix field-sortable">
        <div class="col-full">
            <label class="ve-label" for="{ opts.step }_{ opts.field.id }">
                { getLanguage()[opts.step][opts.field.text] }
            </label>
            <p id="{ opts.step }_{ opts.field.id }" class="label-text" />
                { opts[opts.step][opts.field.id] }
            </p>
        </div>
    </div>

    <div class="no-display" if={ (opts.field.display != 1 && getState().edit && typeof opts.field.display !== 'undefined') }>
        <label class="col-full" >{ getLanguage()[opts.step][opts.field.text] } <div class="ve-pull-right"><span class="ve-badge ve-badge--warning">{getLanguage().general.text_hidden}<span></div></label>
    </div>

    <script>
        this.mixin({store:d_quickcheckout_store});
        this.setting_id = opts.step +'_'+ opts.field_id +'_setting';

        var tag = this;
    </script>
</qc_field_label>
