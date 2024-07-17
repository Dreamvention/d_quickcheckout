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
            if(this.store.getState().displaySetting[this.opts.setting_id]){
                this.store.hideSetting()
            }else{
                this.store.showSetting(this.opts.setting_id);
            }
            this.parent.update();
        }

        toggleDependency(e){
            if(this.store.getState().displaySetting[this.opts.setting_id]){
                this.store.hideSetting();
                this.parent.update();
            }else{
                var tab = this.opts.setting_id+'_advanced';
                this.store.showSetting(this.opts.setting_id);
                this.parent.update();
                dv_cash('.qc-tab').removeClass('active');
                dv_cash('a[href="#' + tab + '"]').parent('.qc-tab').addClass('active');
                dv_cash('#'+tab).parents('.qc-setting-tab-content').find('.qc-setting-tab-pane').removeClass('in').removeClass('active').hide();
                dv_cash('#'+tab).addClass('in').addClass('active').show();
            }
        }

        editCheckbox(e){
            var checkbox = dv_cash(e.currentTarget).find('input[type=checkbox]');
            checkbox.prop("checked", !checkbox.prop("checked"));
            this.store.dispatch(this.opts.step+'/edit', serializeJSON(Array.from(dv_cash(tag.root).find('.qc-field-setting'))));
        }

        deleteField(e){
            this.store.deleteCustomField(this.opts.step, this.opts.field_id);
            this.store.hideSetting();
            this.parent.update();
            this.opts.ondelete();
            this.store.initFieldSortable(this.opts.step);
        }
    </script>
</qc_field_setting>