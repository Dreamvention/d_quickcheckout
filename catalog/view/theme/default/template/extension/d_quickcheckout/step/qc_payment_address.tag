<qc_payment_address>
    <div class="step">

        <qc_payment_address_setting if={riot.util.tags.selectTags().search('"qc_payment_address_setting"') && getState().edit} step="{opts.step}"></qc_payment_address_setting>

        <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_payment_address_setting"') < 0 && getState().edit}></qc_pro_label>

        <!-- Step -->
        <div class="panel panel-default" show={getConfig().payment_address.display == 1}>
            <div class="panel-heading">
                <h4 class="panel-title">
                    <span class="icon">
                        <i class="{ getConfig().payment_address.icon }"></i>
                    </span>
                    {  getLanguage().payment_address.heading_title } 
                </h4>
                <h5 if={getLanguage().payment_address.text_description}>{  getLanguage().payment_address.text_description } </h5>
            </div>
            <div class="panel-body">
                
                <qc_address_radio 
                if={getSession().addresses && getConfig().payment_address.address_style == 'radio'} 
                step="payment_address" 
                address_id={ getSession().payment_address.address_id }></qc_address_radio>

                <qc_address_select 
                if={getSession().addresses && getConfig().payment_address.address_style == 'select'} 
                step="payment_address" 
                address_id={ getSession().payment_address.address_id }></qc_address_select>


                <div class="row { (getAccount() != 'logged' 
                    || (getAccount() == 'logged' 
                        && (getSession().payment_address.address_id == '0' 
                        || !getSession().payment_address.address_id))) ? '' : 'hidden'  }">
                    <form id="payment_address_fields" class="payment-address-fields" >
                        
                        <div
                            
                            each={ field_id in fields}
                            if={ (getConfig().payment_address.fields[field_id])}
                            class="qc-field { (getConfig().payment_address.fields[field_id].style == 'col') ? 'qc-col' : 'qc-clearboth' }"
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
                    <div class="col-md-12">
                        <qc_custom_field if={getState().edit} setting_id="payment_address_custom_field_{rand()}" step="payment_address" location_account="true" location_address="true" onchange={updateFields}></qc_custom_field>
                    </div>
                </div>
                
            </div>
        </div>
        <!-- Hidden Step -->
        <div show={(getConfig().payment_address.display != 1 && getState().edit)}>
            <div class="panel panel-default" style="opacity: 0.5">
                <div class="panel-heading">
                    {  getLanguage().payment_address.heading_title} 
                    <div class="pull-right"><span class="label label-warning">{getLanguage().general.text_hidden}<span></div>
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