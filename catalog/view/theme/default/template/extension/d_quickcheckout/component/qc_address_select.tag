<qc_address_select>
    <div class="ve-field">
        <label for="{ opts.step }_address_id">
            <input
            type="radio"
            id="{ opts.step }_address_id"
            value=""
            checked={opts.address_id != '0'}
            onclick={switchToAddress}/>
            <span>{getLanguage().general.text_address_existing}</span>
            <select
                class="ve-input address-select"
                onchange={change}
                name="{opts.step }[address_id]">
                <option 
                each={address in getSession().addresses } 
                value={ address.address_id}
                selected={address.address_id == opts.address_id}
                if={address}>
                    {address.firstname} {address.lastname} {address.company} {address.address_1} {address.address_2} {address.city}, {address.zone} {address.postcode} {address.country}
                </option>
            </select>
        </label>
    </div>
    <div class="ve-field {(opts.address_id == '0') ? 've-mb-3' : ''}">
        <label for="{ opts.step }_address_id_0">
            <input
            type="radio"
            id="{ opts.step }_address_id_0"
            name="{ opts.step }[address_id]"
            value="0"
            checked={opts.address_id == '0'}
            onclick={change}/>
            <span>{getLanguage().general.text_address_new}</span>
        </label>
    </div>

    <script>
        this.mixin({store:d_quickcheckout_store});
        var tag = this;

        change(e){
            this.store.dispatch(this.opts.step+'/update', serializeJSON(e.currentTarget));
        }
        switchToAddress(e){
            this.store.dispatch(this.opts.step+'/update', serializeJSON(Array.from(dv_cash(tag.root).find('.address-select'))));
        }
    </script>
</qc_address_select>
