<qc_field_setting>
    <form class="qc-field-setting">
        <div class="ve-btn-group ve-btn-group--sm" data-toggle="buttons">
            <label class="ve-btn ve-btn--default handle-sortable" id="{ opts.field_id }">
                <i class="fas fa-arrows-alt"></i>
            </label>
            <a class="ve-btn ve-btn--default " onclick="{toggleSetting}" >
                <i class="fas fa-cog"></i>
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
        this.mixin({store:ajax_quick_checkout_store});
        var tag = this;

        toggleSetting(e){
            if(document.getElementById(tag.opts.setting_id).classList.contains('show')){
                this.store.hideSetting()
            }else{
                this.store.showSetting(this.opts.setting_id);
            }
        }

        toggleDependency(e){
            if(document.getElementById(this.opts.setting_id).classList.contains('show')){
                this.store.hideSetting()
            }else{
                var tab = this.opts.setting_id+'_advanced';
                this.store.showSetting(this.opts.setting_id);
                var tabTrigger = new bootstrap.Tab(document.querySelector('.setting-tabs a[href="#' + tab + '"]'));
                tabTrigger.show();
            }
        }

        editCheckbox(e){
            e.currentTarget.querySelector('input[type=checkbox]').checked = !e.currentTarget.querySelector('input[type=checkbox]').checked;
            this.store.dispatch(this.opts.step+'/edit', serializeJSON(tag.root.getElementsByClassName('qc-field-setting')));
        }

        deleteField(e){
            this.store.deleteCustomField(this.opts.step, this.opts.field_id);
            this.store.hideSetting();
            this.opts.ondelete();
        }
    </script>
</qc_field_setting>