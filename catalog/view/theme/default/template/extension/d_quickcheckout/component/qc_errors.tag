<qc_errors>
    <div class="input-group">
        <select class="form-control error-type" >
            <option
            each={error_type in getState().error_types}
            value={error_type}
            >{getLanguage().general['text_'+error_type]}</option>
        </select>
        <div class="input-group-btn">
            <a class="btn btn-default" onclick={addError}>{getLanguage().general.text_add}</a>
        </div>
    </div>

    <div each={error, error_id in opts.errors }>
        <qc_error_setting 
            if={error}
            error_id={error_id} 
            error={error} 
            field_id={ parent.opts.field_id } 
            step={parent.opts.step} 
            edit="{parent.opts.edit}"></qc_error_setting>
    </div>

    <script>
        this.mixin({store:d_quickcheckout_store});
        var tag = this;
        addError(e){
            var error_type = $(tag.root).find('.error-type').val();
            this.store.dispatch('field/addError', {'step_id': this.opts.step, 'field_id': this.opts.field_id, 'error_type': error_type});
        }
    </script>
</qc_errors>