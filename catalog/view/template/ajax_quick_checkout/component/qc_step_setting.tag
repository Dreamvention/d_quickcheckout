<qc_step_setting>
    <form class="step-setting">
        <div class="ve-btn-group ve-btn-group--sm" data-toggle="buttons">
            <label class="ve-btn ve-btn--default handle-sortable" id="{ opts.field_id }">
                <i class="fas fa-arrows-alt"></i>
            </label>
            <a class="ve-btn ve-btn--default " onclick="{toggleSetting}" >
                <i class="fas fa-cog"></i>
            </a>

            <yield/>

            <a class="ve-btn ve-btn--default " onclick="{removeStep}" >
                <i class="fa fa-times"></i>
            </a>
        </div>
    </form>

    <script>
        this.mixin({store:ajax_quick_checkout_store});
        var tag = this;
        toggleSetting(e){
            if(document.getElementById(this.opts.setting_id).classList.contains('show')){
                this.store.hideSetting()
            }else{
                this.store.showSetting(this.opts.setting_id);
            }
        }
        
        removeStep(e){
            var element = e.currentTarget;
            var parentNodes = [];
            while ((element = element.parentNode) && element.nodeType !== 9) {
                if (element.nodeType === 1 && element.getAttribute && element.getAttribute('data-name') == this.opts.step) {
                    parentNodes.push(element);
                }
            }
            var step_id = parentNodes[0].id;
            this.store.dispatch('step/remove', {step_id : step_id} );
            tag.store.hideSetting();
        }
    </script>
</qc_step_setting>