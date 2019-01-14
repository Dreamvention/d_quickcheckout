<qc_field_heading>
    <qc_heading_setting if={riot.util.tags.selectTags().search('"qc_heading_setting"') && getState().edit} field_id="{opts.field_id}" field="{opts.field}" step="{opts.step}" ondelete="{opts.ondelete}"></qc_heading_setting>

    <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_heading_setting"') < 0 && getState().edit}></qc_pro_label>

    <div if={ (opts.field.display == 1) } class="d-vis clearboth field-sortable">
        <div class="col-full">
            <h4 class="ve-h4">
            { getLanguage()[opts.step][opts.field.text] }</h4>
            
        </div>
        <hr/>
    </div>

    <div class="no-display" if={ (opts.field.display != 1 && getState().edit && typeof opts.field.display !== 'undefined') }>
        <label class="col-full" >{ getLanguage()[opts.step][opts.field.text] } <div class="ve-pull-right"><span class="ve-badge ve-badge--warning">{getLanguage().general.text_hidden}<span></div></label>
    </div>

    <script>
        this.mixin({store:d_quickcheckout_store});
        this.setting_id = opts.step +'_'+ opts.field_id +'_setting';

        var tag = this;
        
    </script>
</qc_field_heading>
