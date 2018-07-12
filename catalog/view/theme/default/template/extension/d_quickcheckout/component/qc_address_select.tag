<qc_address_select>
    <h4></h4>
    <ul class="list-unstyled">
        <li>
            <label for="{ opts.step }_address_id">
                <input
                type="radio"
                id="{ opts.step }_address_id"
                value=""
                checked={opts.address_id != '0'}
                onclick={switchToAddress}/>
                <span>{getLanguage().general.text_address_existing}</span>
                <div>
                    <select
                        class="form-control address-select"
                        onchange={change}
                        name="{opts.step }[address_id]">
                        <option 
                        each={address in getSession().addresses } 
                        value={ address.address_id}
                        if={address}>
                            {address.firstname} {address.lastname} {address.company} {address.address_1} {address.address_2} {address.city}, {address.zone} {address.postcode} {address.country}
                        </option>
                    </select>
                </div>
            </label>
        </li>
        <li>
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
        </li>
    </ul>

    <script>
        this.mixin({store:d_quickcheckout_store});
        var tag = this;

        change(e){
            this.store.dispatch(this.opts.step+'/update', $(e.currentTarget).serializeJSON());
        }
        switchToAddress(e){
            console.log( $(tag.root).find('.address-select').serializeJSON())
            this.store.dispatch(this.opts.step+'/update', $(tag.root).find('.address-select').serializeJSON());
        }
    </script>
</qc_address_select>
