<qc_shipping_address>
    <div class="step">

        <qc_shipping_address_setting if={riot.util.tags.selectTags().search('"qc_shipping_address_setting"') && getState().edit} step="{opts.step}"></qc_shipping_address_setting>

        <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_shipping_address_setting"') < 0 && getState().edit}></qc_pro_label>

        <!-- Step -->
        <div class="panel panel-default" show={getConfig().shipping_address.display == 1}>
            <div class="panel-heading">
                <h4 class="panel-title">
                    <span class="icon">
                        <i class="{ getConfig().shipping_address.icon }"></i>
                    </span>
                    { getLanguage().shipping_address.heading_title }
                </h4>
                <h5 if={getLanguage().shipping_address.text_description}>{  getLanguage().shipping_address.text_description } </h5>
            </div>
            <div class="panel-body">
                <qc_address_radio 
                if={getSession().addresses && getConfig().shipping_address.address_style == 'radio'} 
                step="shipping_address" 
                address_id={ getSession().shipping_address.address_id }></qc_address_radio>

                <qc_address_select 
                if={getSession().addresses && getConfig().shipping_address.address_style == 'select'} 
                step="shipping_address" 
                address_id={ getSession().shipping_address.address_id }></qc_address_select>

                <div class="row" 
                    if={getAccount() != 'logged' 
                    || (getAccount() == 'logged' 
                        && (getSession().shipping_address.address_id == '0' 
                        || !getSession().shipping_address.address_id)) }>
                    <form id="shipping_address_fields" class="shipping-address-fields">
                        <div
                            each={ field_id in fields}
                            if={getConfig().shipping_address.fields[field_id]}
                            class="qc-field { (getConfig().shipping_address.fields[field_id].style == 'col') ? 'qc-col' : 'qc-clearboth' }"
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
                    <div class="col-md-12">
                        <qc_custom_field if={getState().edit} setting_id="shipping_address_custom_field{rand()}" step="shipping_address" location_account="true" location_address="true" onchange={updateFields}></qc_custom_field>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- Hidden Step -->
        <div show={(getConfig().shipping_address.display != 1 && getState().edit)}>
            <div class="panel panel-default" style="opacity: 0.5">
                <div class="panel-heading">{ getLanguage().shipping_address.heading_title } <div class="pull-right"><span class="label label-warning">{getLanguage().general.text_hidden}<span></div></div>
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
