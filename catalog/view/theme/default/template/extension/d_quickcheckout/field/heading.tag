<field_heading>
    <heading_setting if={riot.util.tags.selectTags().search('"heading_setting"') && getState().edit} field_id="{opts.field_id}" field="{opts.field}" step="{opts.step}"></heading_setting>

    <pro_label if={ riot.util.tags.selectTags().search('"heading_setting"') < 0 && getState().edit}></pro_label>

    <div if={ (opts.field.display == 1) } class="form-group clearboth field-sortable">
        <div class="col-md-12">
            <h4><i class="fa fa-book"></i>
            { getLanguage()[opts.step][opts.field.text] }</h4>
            
        </div>
        <br/>
        <hr/>
    </div>

    <div class="no-display" if={ (opts.field.display != 1 && getState().edit && typeof opts.field.display !== 'undefined') }>
        <label class="col-md-12" >{ getLanguage()[opts.step][opts.field.text] } <div class="pull-right"><span class="label label-warning">{getLanguage().general.text_hidden}<span></div></label>
    </div>

    <script>
        this.setting_id = opts.step +'_'+ opts.field_id +'_setting';

        var tag = this;
    </script>
</field_heading>
