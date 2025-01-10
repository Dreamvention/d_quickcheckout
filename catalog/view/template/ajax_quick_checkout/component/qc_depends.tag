<qc_depends>
    <div class="ve-input-group mb-4">
        <select class="ve-input" onchange="{changeAccount}">
            <option
            each={account in accountOptions}
            value={account.id}
            selected={accountModel === account.id}
            >{account.text}</option>
        </select>
    </div>
    <div class="ve-input-group">
        <select class="ve-input depend-id" >
            <option
            each={field in getState().config[accountModel][parent.opts.step].fields}
            value={field.id}
            >{stripTags(getLanguage()[parent.opts.step][field.text])}</option>
        </select>
        <a class="ve-btn ve-btn--primary" onclick={addDepend}>{getLanguage().general.text_add}</a>
    </div>
    <div each={depend, depend_id in getState().config[accountModel][parent.opts.step].fields[parent.opts.field_id].depends || ({}) }>
        <qc_depend_setting
            depend_id={depend_id}
            if={depend}
            depend={depend}
            account={accountModel}
            field_id={ parent.opts.field_id }
            step={parent.opts.step}
            edit="{parent.opts.edit}"></qc_depend_setting>
    </div>

    <script>
        this.mixin({store:ajax_quick_checkout_store, accountModel: 'guest', accountOptions: ['guest', 'register', 'logged'].map(a => ({text: getLanguage().general['text_'+a], id: a}))});
        var tag = this;
        addDepend(e){
            var depend_id = tag.root.querySelector('.depend-id').value;
            tag.store.dispatch('field/addDepend', {'account': tag.accountModel, 'step_id': tag.opts.step, 'field_id': tag.opts.field_id, 'depend_id': depend_id});
        }
        changeAccount(e) {
            tag.accountModel = e.target.value;
        }
    </script>
</qc_depends>