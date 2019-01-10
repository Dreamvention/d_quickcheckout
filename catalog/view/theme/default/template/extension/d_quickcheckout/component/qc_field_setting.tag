<qc_field_setting>
    <form class="qc-field-setting">
        <div class="ve-btn-group ve-btn-group--sm" data-toggle="buttons">
            <label class="ve-btn ve-btn--default handle-sortable" id="{ opts.field_id }">
                <i class="fa fa-arrows"></i>
            </label>
            <a class="ve-btn ve-btn--default " onclick="{toggleSetting}" >
                <i class="fa fa-gear"></i>
            </a>

            <label if={(isEmpty(parent.opts.field.depends))} class="ve-btn ve-btn--default { opts.display == 1 ? 'active' : '' }" onclick="{editCheckbox}">
                <input name="config[{getAccount()}][{opts.step}][fields][{ opts.field_id }][display]" type="hidden"  value="0">
                <input name="config[{getAccount()}][{opts.step}][fields][{ opts.field_id }][display]" type="checkbox" value="1" checked={ (opts.display == 1) }>
                <i class="fa fa-eye"></i>
            </label>

            <yield/>

            <a if={(!isEmpty(parent.opts.field.depends))} class="ve-btn ve-btn--default " onclick="{toggleDependency}" >
                <i class="fa fa-link"></i> {isEmpty(parent.opts.field.depends)}
            </a>

            <label if={opts.delete} class="ve-btn ve-btn--default" onclick="{deleteField}">
                <i class="fa fa-times"></i>
            </label>

        </div>
    </form>

    <script>
        this.mixin({store:d_quickcheckout_store});
        var tag = this;

        toggleSetting(e){
            if($('#'+ this.opts.setting_id).hasClass('show')){
                this.store.hideSetting()
            }else{
                this.store.showSetting(this.opts.setting_id);
            }
        }

        toggleDependency(e){
            if($('#'+ this.opts.setting_id).hasClass('show')){
                this.store.hideSetting()
            }else{
                var tab = this.opts.setting_id+'_advanced';
                this.store.showSetting(this.opts.setting_id);
                $('.setting-tabs a[href="#' + tab + '"]').tab('show');
            }
        }

        editCheckbox(e){
            var checkbox = $(e.currentTarget).find('input[type=checkbox]');
            checkbox.prop("checked", !checkbox.prop("checked"));
            this.store.dispatch(this.opts.step+'/edit', $(tag.root).find('.qc-field-setting').serializeJSON());
        }

        deleteField(e){
            this.store.deleteCustomField(this.opts.step, this.opts.field_id);
            this.store.hideSetting();
            this.opts.ondelete();
        }
    </script>
</qc_field_setting>