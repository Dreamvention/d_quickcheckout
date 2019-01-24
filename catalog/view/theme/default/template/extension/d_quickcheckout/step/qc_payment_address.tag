<qc_payment_address>
    <div class="step qc-payment-address">

        <qc_payment_address_setting if={riot.util.tags.selectTags().search('"qc_payment_address_setting"') && getState().edit} step="{opts.step}"></qc_payment_address_setting>

        <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_payment_address_setting"') < 0 && getState().edit}></qc_pro_label>

        <!-- Step -->
        <div class="ve-card" if={getConfig().payment_address.display == 1 && getState().config.guest.payment_address.style == 'card'}>
            <div class="ve-card__header">
                <h4 class="ve-h4">
                    <span if={ getConfig().payment_address.icon } class="icon">
                        <i class="{ getConfig().payment_address.icon }"></i>
                    </span>
                    {  getLanguage().payment_address.heading_title } 
                </h4>
                <p class="ve-p" if={getLanguage().payment_address.text_description}>{  getLanguage().payment_address.text_description } </p>
            </div>
            <div class="ve-card__section">
                
                <qc_address_radio 
                if={getSession().addresses && getConfig().payment_address.address_style == 'radio'} 
                step="payment_address" 
                address_id={ getSession().payment_address.address_id }></qc_address_radio>

                <qc_address_select 
                if={getSession().addresses && getConfig().payment_address.address_style == 'select'} 
                step="payment_address" 
                address_id={ getSession().payment_address.address_id }></qc_address_select>

                <div class="{ (getAccount() != 'logged' 
                    || (getAccount() == 'logged' 
                        && (getSession().payment_address.address_id == '0' 
                        || !getSession().payment_address.address_id))) ? '' : 've-hidden' }">
                    <form id="payment_address_fields" class="payment-address-fields qc-row" >
                        <div
                            each={ field_id in fields}
                            if={ (getConfig().payment_address.fields[field_id])}
                            class="qc-field ve-field { (getState().config.guest.payment_address.fields[field_id].style == 'col') ? 'qc-field-col' : 'qc-clearboth' }"
                            sort_order={ getConfig().payment_address.fields[field_id].sort_order }
                            field_id={field_id}
                            step="payment_address"
                            field={ getConfig().payment_address.fields[field_id] }
                            value={ getSession().payment_address[field_id] }
                            error={ getError().payment_address && getError().payment_address[field_id] }
                            ondelete={updateFields}
                            no-reorder
                            data-is={ getConfig().payment_address.fields[field_id].type ? 'qc_field_' + getConfig().payment_address.fields[field_id].type : '' }
                        ></div>
                    </form>
                    <div if={getState().edit} class="ve-mt-3">
                        <qc_custom_field setting_id="payment_address_custom_field_{rand()}" step="payment_address" location_account="true" location_address="true" onchange={updateFields} title={getLanguage().payment_address.heading_title}></qc_custom_field>
                    </div>
                </div>
            </div>
        </div>


        <div class="ve-mb-3 ve-clearfix" if={getConfig().payment_address.display == 1 && getState().config.guest.payment_address.style == 'clear'}>
            <h4 class="ve-h4">
                <span if={ getConfig().payment_address.icon } class="icon">
                    <i class="{ getConfig().payment_address.icon }"></i>
                </span>
                {  getLanguage().payment_address.heading_title } 
            </h4>
            <p class="ve-p" if={getLanguage().payment_address.text_description}>{  getLanguage().payment_address.text_description } </p>

            <qc_address_radio 
            if={getSession().addresses && getConfig().payment_address.address_style == 'radio'} 
            step="payment_address" 
            address_id={ getSession().payment_address.address_id }></qc_address_radio>

            <qc_address_select 
            if={getSession().addresses && getConfig().payment_address.address_style == 'select'} 
            step="payment_address" 
            address_id={ getSession().payment_address.address_id }></qc_address_select>

            <div class="{ (getAccount() != 'logged' 
                || (getAccount() == 'logged' 
                    && (getSession().payment_address.address_id == '0' 
                    || !getSession().payment_address.address_id))) ? '' : 've-hidden' }">
                <form id="payment_address_fields" class="payment-address-fields qc-row" >
                    <div
                        each={ field_id in fields}
                        if={ (getConfig().payment_address.fields[field_id])}
                        class="qc-field ve-field {field_id} { (getState().config.guest.payment_address.fields[field_id].style == 'col') ? 'qc-field-col' : 'qc-clearboth' }"
                        sort_order={ getConfig().payment_address.fields[field_id].sort_order }
                        field_id={field_id}
                        step="payment_address"
                        field={ getConfig().payment_address.fields[field_id] }
                        value={ getSession().payment_address[field_id] }
                        error={ getError().payment_address && getError().payment_address[field_id] }
                        ondelete={updateFields}
                        no-reorder
                        data-is={ getConfig().payment_address.fields[field_id].type ? 'qc_field_' + getConfig().payment_address.fields[field_id].type : '' }
                    ></div>
                </form>
                <div if={getState().edit} class="ve-mt-3">
                    <qc_custom_field setting_id="payment_address_custom_field_{rand()}" step="payment_address" location_account="true" location_address="true" onchange={updateFields} title={getLanguage().payment_address.heading_title}></qc_custom_field>
                </div>
            </div>
        </div>


        <!-- Hidden Step -->
        <div show={(getConfig().payment_address.display != 1 && getState().edit)}>
            <div class="panel panel-default" style="opacity: 0.5">
                <div class="panel-heading">
                    {  getLanguage().payment_address.heading_title} 
                    <div class="ve-pull-right"><span class="ve-badge ve-badge--warning">{getLanguage().general.text_hidden}<span></div>
                </div>
            </div>
        </div>

    </div>
    <script>
        this.mixin({store:d_quickcheckout_store});

        var tag = this;

        tag.fields = this.store.getFieldIds('payment_address');

        updateFields(){
            tag.fields = this.store.getFieldIds('payment_address');
            this.store.render();
        }
        
        this.on('mount', function(){
            this.store.initFieldSortable('payment_address');
        })
    </script>
</qc_payment_address>