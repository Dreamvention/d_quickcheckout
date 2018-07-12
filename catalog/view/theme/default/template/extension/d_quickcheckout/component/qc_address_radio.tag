<qc_address_radio>
    <h4>{getLanguage().general.text_address_existing}</h4>
    <ul class="list-unstyled">
      <li each={address in getSession().addresses } if={address}>
        <label for="{ parent.opts.step }_address_id_{address.address_id}">
        <input
          type="radio"
          id="{ parent.opts.step }_address_id_{address.address_id}"
          name="{ parent.opts.step }[address_id]"
          value={ address.address_id}
          checked={parent.opts.address_id == address.address_id}
          onclick={change}/>
        <span>{address.firstname} {address.lastname} {address.company} {address.address_1} {address.address_2} {address.city}, {address.zone} {address.postcode} {address.country}</span>
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
        change(e){
            this.store.dispatch(this.opts.step+'/update', $(e.currentTarget).serializeJSON());
        }
    </script>
</qc_address_radio>
