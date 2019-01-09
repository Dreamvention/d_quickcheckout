<qc_address_radio>
    <p class="qc-title">{getLanguage().general.text_address_existing}</p>
    <div class="ve-field" each={address in getSession().addresses } if={address}>
        <label class="ve-radio {(parent.opts.address_id == address.address_id) ? 've-radio--selected':''}" for="{ parent.opts.step }_address_id_{address.address_id}">
        <input
          type="radio"
          class="ve-input"
          id="{ parent.opts.step }_address_id_{address.address_id}"
          name="{ parent.opts.step }[address_id]"
          value={ address.address_id}
          checked={parent.opts.address_id == address.address_id}
          onclick={change}/>
          <i></i>
        <span>{address.firstname} {address.lastname} {address.company} {address.address_1} {address.address_2} {address.city}, {address.zone} {address.postcode} {address.country}</span>
      </label>
    </div>
    <div class="ve-field {(opts.address_id == '0') ? 've-mb-3' : ''}">
        <label class="ve-radio {(opts.address_id == '0') ? 've-radio--selected':''}"  for="{ opts.step }_address_id_0">
        <input
          type="radio"
          class="ve-input"
          id="{ opts.step }_address_id_0"
          name="{ opts.step }[address_id]"
          value="0"
          checked={opts.address_id == '0'}
          onclick={change}/>
          <i></i>
        <span>{getLanguage().general.text_address_new}</span>
      </label>
    </div>
    
    <script>
        this.mixin({store:d_quickcheckout_store});
        change(e){
            this.store.dispatch(this.opts.step+'/update', $(e.currentTarget).serializeJSON());
        }
    </script>
</qc_address_radio>
