<qc_depends>
    <div class="ve-input-group">
        <select class="ve-input depend-id" >
            <option
            each={field in getConfig()[opts.step].fields}
            value={field.id}
            >{stripTags(getLanguage()[parent.opts.step][field.text])}</option>
        </select>
        <a class="ve-btn ve-btn--primary" onclick={addDepend}>{getLanguage().general.text_add}</a>
    </div>
    <div each={depend, depend_id in opts.depends }>
        <qc_depend_setting 
            depend_id={depend_id} 
            if={depend}
            depend={depend} 
            field_id={ parent.opts.field_id } 
            step={parent.opts.step} 
            edit="{parent.opts.edit}"></qc_depend_setting>
    </div>
    
    <script>
        this.mixin({store:d_quickcheckout_store});
        var tag = this;

        addDepend(e){
            var depend_id = $(tag.root).find('.depend-id').val();
            tag.store.dispatch('field/addDepend', {'step_id': tag.opts.step, 'field_id': tag.opts.field_id, 'depend_id': depend_id});
        }
    </script>
</qc_depends>