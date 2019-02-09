<qc_custom>
    <div class="step qc-custom">

        <qc_custom_setting if={riot.util.tags.selectTags().search('"qc_custom_setting"') && getState().edit} step="{opts.step}"></qc_custom_setting>

        <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_custom_setting"') < 0 && getState().edit}></qc_pro_label>

        <!-- Step -->
        <div class="ve-card" if={ getConfig().custom.display == 1 && getState().config.guest.custom.style == 'card' }>
            <div class="ve-card__header">
                <h4 class="ve-h4">
                    <span if={ getConfig().custom.icon } class="icon">
                        <i class="{ getConfig().custom.icon }"></i>
                    </span>
                    { getLanguage().custom.heading_title }
                </h4>
                <p class="ve-p" if={getLanguage().custom.text_description}>{  getLanguage().custom.text_description } </p>
            </div>
            <div class="ve-card__section">
                
                <form  id="custom_fields" class="custom-fields qc-row" >
                    <div 
                        each={ field_id in fields }
                        if={ (getConfig().custom.fields[field_id])}
                        class="qc-field ve-field {field_id} { (getState().config.guest.custom.fields[field_id].style == 'col') ? 'qc-field-col' : 'qc-clearboth' }"
                        sort_order={ getConfig().custom.fields[field_id].sort_order }
                        field_id={field_id}
                        step="custom"
                        no-reorder
                        field={ getConfig().custom.fields[field_id] }
                        value={ getSession().custom[field_id] }
                        error={ getError().custom && getError().custom[field_id]}
                        data-is={ getConfig().custom.fields[field_id].type ? 'qc_field_' + getConfig().custom.fields[field_id].type : ''}
                    ></div>
                </form>
                <div if={getState().edit} class="ve-mt-3">
                    <qc_custom_field setting_id="custom_custom_field_{rand()}" step="custom" location_account="true"></qc_custom_field>
                </div>
            </div>
        </div>

        <div if={ getConfig().custom.display == 1 && getState().config.guest.custom.style == 'clear' } class="ve-mb-3 ve-clearfix">
            <p class="ve-p" if={getLanguage().custom.text_description}>{  getLanguage().custom.text_description } </p>
            <form  id="custom_fields" class="custom-fields qc-row" >
                <div 
                    each={ field_id in fields }
                    if={ (getConfig().custom.fields[field_id])}
                    class="qc-field ve-field { (getState().config.guest.custom.fields[field_id].style == 'col') ? 'qc-field-col' : 'qc-clearboth' }"
                    sort_order={ getConfig().custom.fields[field_id].sort_order }
                    field_id={field_id}
                    step="custom"
                    no-reorder
                    field={ getConfig().custom.fields[field_id] }
                    value={ getSession().custom[field_id] }
                    error={ getError().custom && getError().custom[field_id]}
                    data-is={ getConfig().custom.fields[field_id].type ? 'qc_field_' + getConfig().custom.fields[field_id].type : ''}
                ></div>
            </form>
            <div if={getState().edit} class="ve-mt-3">
                <qc_custom_field setting_id="custom_custom_field_{rand()}" step="custom" location_account="true"></qc_custom_field>
            </div>
        </div>

        <!-- Hidden Step -->
        <div show={(getConfig().custom.display != 1 && getState().edit)}>
            <div class="ve-card" style="opacity: 0.5">
                <div class="ve-card__header"> 
                    { getLanguage().custom.heading_title } 
                    <div class="ve-pull-right"><span class="ve-badge ve-badge--warning">{getLanguage().general.text_hidden}<span></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        this.mixin({store:d_quickcheckout_store});

        var tag = this;

        tag.fields = this.store.getFieldIds('custom');

        this.on('mount', function(){
            this.store.initFieldSortable('custom');
        })
    </script>
</qc_custom>
