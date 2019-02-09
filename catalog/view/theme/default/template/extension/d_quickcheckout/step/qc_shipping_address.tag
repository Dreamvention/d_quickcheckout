<qc_shipping_address>
    <div class="step qc-shipping-address">

        <qc_shipping_address_setting if={riot.util.tags.selectTags().search('"qc_shipping_address_setting"') && getState().edit} step="{opts.step}"></qc_shipping_address_setting>

        <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_shipping_address_setting"') < 0 && getState().edit}></qc_pro_label>

        <!-- Step -->
        <div class="ve-card" if={getConfig().shipping_address.display == 1 && getState().config.guest.shipping_address.style == 'card'}>
            <div class="ve-card__header">
                <h4 class="ve-h4">
                    <span if={ getConfig().shipping_address.icon } class="icon">
                        <i class="{ getConfig().shipping_address.icon }"></i>
                    </span>
                    { getLanguage().shipping_address.heading_title }
                </h4>
                <p class="ve-p" if={getLanguage().shipping_address.text_description}>{  getLanguage().shipping_address.text_description } </p>
            </div>

            <div class="ve-card__section">
                <qc_address_radio 
                if={getSession().addresses && getConfig().shipping_address.address_style == 'radio'} 
                step="shipping_address" 
                address_id={ getSession().shipping_address.address_id }></qc_address_radio>

                <qc_address_select 
                if={getSession().addresses && getConfig().shipping_address.address_style == 'select'} 
                step="shipping_address" 
                address_id={ getSession().shipping_address.address_id }></qc_address_select>

                <div 
                    if={getAccount() != 'logged' 
                    || (getAccount() == 'logged' 
                        && (getSession().shipping_address.address_id == '0' 
                        || !getSession().shipping_address.address_id)) }>
                    <form id="shipping_address_fields" class="shipping-address-fields qc-row">
                        <div
                            each={ field_id in fields}
                            if={getConfig().shipping_address.fields[field_id]}
                            class="qc-field ve-field {field_id} { (getState().config.guest.shipping_address.fields[field_id].style == 'col') ? 'qc-field-col' : 'qc-clearboth' }"
                            sort_order={ getConfig().shipping_address.fields[field_id].sort_order }
                            field_id={field_id}
                            step="shipping_address"
                            no-reorder
                            field={ getConfig().shipping_address.fields[field_id] }
                            value={ getSession().shipping_address[field_id] }
                            ondelete={updateFields}
                            error={ getError().shipping_address && getError().shipping_address[field_id] }
                            data-is={ getConfig().shipping_address.fields[field_id].type ? 'qc_field_' + getConfig().shipping_address.fields[field_id].type : '' }
                        ></div>
                    </form>
                    <div if={getState().edit} class="ve-mt-3">
                        <qc_custom_field setting_id="shipping_address_custom_field{rand()}" step="shipping_address" location_address="true" onchange={updateFields}></qc_custom_field>
                    </div>
                </div>
            </div>
        </div>


        <div class="ve-mb-3 ve-clearfix" if={getConfig().shipping_address.display == 1 && getState().config.guest.shipping_address.style == 'clear'}>
            <h4 class="ve-h4">
                <span if={ getConfig().shipping_address.icon } class="icon">
                    <i class="{ getConfig().shipping_address.icon }"></i>
                </span>
                { getLanguage().shipping_address.heading_title }
            </h4>
            <p class="ve-p" if={getLanguage().shipping_address.text_description}>{  getLanguage().shipping_address.text_description } </p>
        
            <qc_address_radio 
            if={getSession().addresses && getConfig().shipping_address.address_style == 'radio'} 
            step="shipping_address" 
            address_id={ getSession().shipping_address.address_id }></qc_address_radio>

            <qc_address_select 
            if={getSession().addresses && getConfig().shipping_address.address_style == 'select'} 
            step="shipping_address" 
            address_id={ getSession().shipping_address.address_id }></qc_address_select>

            <div 
                if={getAccount() != 'logged' 
                || (getAccount() == 'logged' 
                    && (getSession().shipping_address.address_id == '0' 
                    || !getSession().shipping_address.address_id)) }>
                <form id="shipping_address_fields" class="shipping-address-fields qc-row">
                    <div
                        each={ field_id in fields}
                        if={getConfig().shipping_address.fields[field_id]}
                        class="qc-field ve-field { (getState().config.guest.shipping_address.fields[field_id].style == 'col') ? 'qc-field-col' : 'qc-clearboth' }"
                        sort_order={ getConfig().shipping_address.fields[field_id].sort_order }
                        field_id={field_id}
                        step="shipping_address"
                        no-reorder
                        field={ getConfig().shipping_address.fields[field_id] }
                        value={ getSession().shipping_address[field_id] }
                        ondelete={updateFields}
                        error={ getError().shipping_address && getError().shipping_address[field_id] }
                        data-is={ getConfig().shipping_address.fields[field_id].type ? 'qc_field_' + getConfig().shipping_address.fields[field_id].type : '' }
                    ></div>
                </form>
                <div if={getState().edit} class="ve-mt-3">
                    <qc_custom_field setting_id="shipping_address_custom_field{rand()}" step="shipping_address" location_address="true" onchange={updateFields}></qc_custom_field>
                </div>
            </div>
        </div>

        <!-- Hidden Step -->
        <div show={(getConfig().shipping_address.display != 1 && getState().edit)}>
            <div class="ve-card" style="opacity: 0.5">
                <div class="ve-card__header">{ getLanguage().shipping_address.heading_title } <div class="ve-pull-right"><span class="ve-badge ve-badge--warning">{getLanguage().general.text_hidden}<span></div></div>
            </div>
        </div>
    </div>
    <script>
        this.mixin({store:d_quickcheckout_store});

        var tag = this;

        tag.fields = this.store.getFieldIds('shipping_address');

        updateFields(){
            tag.fields = this.store.getFieldIds('shipping_address');
            this.store.render();
        }

        this.on('mount', function(){
            this.store.initFieldSortable('shipping_address');
        })
    </script>
</qc_shipping_address>
