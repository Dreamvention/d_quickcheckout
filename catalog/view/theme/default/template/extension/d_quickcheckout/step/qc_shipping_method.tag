<qc_shipping_method>
    <div class="step qc-shipping-method">

        <qc_shipping_method_setting if={riot.util.tags.selectTags().search('"qc_shipping_method_setting"') && getState().edit} step="{opts.step}"></qc_shipping_method_setting>

        <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_shipping_method_setting"') < 0 && getState().edit}></qc_pro_label>

        <!-- Step -->
        <div class="ve-card" if={ getConfig().shipping_method.display == 1 && getState().config.guest.shipping_method.style == 'card' }>

            <div class="ve-card__header">
                <h4 class="ve-h4">
                    <span if={ getState().config.guest.shipping_method.icon } class="icon">
                        <i class="{ getState().config.guest.shipping_method.icon }"></i>
                    </span>
                    <span class="text">{ getLanguage().shipping_method.heading_title }</span>
                </h4>
                <p class="ve-p" if={getLanguage().shipping_method.text_description}><qc_raw content="{  getLanguage().shipping_method.text_description }"></qc_raw> </p>
            </div>

            <div class="ve-card__section">
                <div each={error, error_id in getError().shipping_method} if={error} class="ve-alert ve-alert--danger ve-field--error"><qc_raw content="{error}"></qc_raw></div>
                <form id="shipping_method_list" if={getState().config.guest.shipping_method.display_options == 1 && getSession().shipping_methods}>
                    
                    <!-- input_style = radio -->
                    <div if={ getState().config.guest.shipping_method.input_style == 'radio'} 
                    each={ shipping_method, name in getSession().shipping_methods } 
                    class="radio-input" >
                        <div if={shipping_method}>
                            <p if={getState().config.guest.shipping_method.display_group_title == 1} class="qc-title"><qc_raw content="{ shipping_method.title }"></qc_raw></p>
                            <div class="ve-field" each={ quote, index in shipping_method.quote } >
                                <label  for="{ quote.code }" class="ve-radio {getSession().shipping_method.code == quote.code ? 've-radio--selected' : ''}">
                                    <input
                                    type="radio"
                                    class="ve-input"
                                    name="shipping_method"
                                    value="{ quote.code }"
                                    id="{ quote.code }"
                                    checked={ getSession().shipping_method.code == quote.code }
                                    onclick={change}/>
                                    <i></i>
                                    <span class="text"><qc_raw content="{ quote.title }"></qc_raw></span> <span class="price">{ quote.text }</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- input_style = select -->
                    <div if={getState().config.guest.shipping_method.input_style == 'select'}>
                        <select name="shipping_method" if={getState().config.guest.shipping_method.display_group_title == 1} class="ve-input" onchange={change}>
                            <optgroup label="{ shipping_method.title }" 
                            each={ shipping_method, name in getSession().shipping_methods } >
                                <option 
                                    each={ quote, index in shipping_method.quote } 
                                    selected={ getSession().shipping_method.code == quote.code }
                                    value="{ quote.code }">
                                    <span class="text"><qc_raw content="{ quote.title }"></qc_raw></span> <span class="price">{ quote.text }</span>
                                </option>
                            </optgroup>
                        </select>

                        <select name="shipping_method" if={getState().config.guest.shipping_method.display_group_title == 0} class="ve-input" onchange={change}>
                            <option 
                                each={ quote, index in flattenShippingMethods() } 
                                selected={ getSession().shipping_method.code == quote.code }
                                value="{ quote.code }">
                                <span class="text"><qc_raw content="{ quote.title }"></qc_raw></span> <span class="price">{ quote.text }</span>
                            </option>
                        </select>
                    </div>
                </form>
            </div>
        </div>


        <div class="ve-mb-3 ve-clearfix" if={ getConfig().shipping_method.display == 1 && getState().config.guest.shipping_method.style == 'clear' }>
            <h4 class="ve-h4">
                <span if={ getState().config.guest.shipping_method.icon } class="icon">
                    <i class="{ getState().config.guest.shipping_method.icon }"></i>
                </span>
                <span class="text">{ getLanguage().shipping_method.heading_title }</span>
            </h4>
            <p class="ve-p" if={getLanguage().shipping_method.text_description}><qc_raw content="{  getLanguage().shipping_method.text_description }"></qc_raw></p>

            <div each={error, error_id in getError().shipping_method} if={error} class="alert alert-danger ve-field--error"><qc_raw content="{error}"></qc_raw></div>
            <form id="shipping_method_list" if={getState().config.guest.shipping_method.display_options == 1 && getSession().shipping_methods}>
                
                <!-- input_style = radio -->
                <div if={ getState().config.guest.shipping_method.input_style == 'radio'} 
                each={ shipping_method, name in getSession().shipping_methods } 
                class="radio-input" >
                    <div if={shipping_method}>
                        <p if={getState().config.guest.shipping_method.display_group_title == 1} class="qc-title"><qc_raw content="{ shipping_method.title }"></qc_raw></p>
                        <div class="ve-field" each={ quote, index in shipping_method.quote } >
                            <label  for="{ quote.code }" class="ve-radio {getSession().shipping_method.code == quote.code ? 've-radio--selected' : ''}">
                                <input
                                type="radio"
                                class="ve-input"
                                name="shipping_method"
                                value="{ quote.code }"
                                id="{ quote.code }"
                                checked={ getSession().shipping_method.code == quote.code }
                                onclick={change}/>
                                <i></i>
                                <span class="text"><qc_raw content="{ quote.title }"></qc_raw></span> <span class="price">{ quote.text }</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- input_style = select -->
                <div if={getState().config.guest.shipping_method.input_style == 'select'}>
                    <select name="shipping_method" if={getState().config.guest.shipping_method.display_group_title == 1} class="ve-input" onchange={change}>
                        <optgroup label="{ shipping_method.title }" 
                        each={ shipping_method, name in getSession().shipping_methods } >
                            <option 
                                each={ quote, index in shipping_method.quote } 
                                selected={ getSession().shipping_method.code == quote.code }
                                value="{ quote.code }">
                                <span class="text"><qc_raw content="{ quote.title }"></qc_raw></span> <span class="price">{ quote.text }</span>
                            </option>
                        </optgroup>
                    </select>

                    <select name="shipping_method" if={getState().config.guest.shipping_method.display_group_title == 0} class="ve-input" onchange={change}>
                        <option 
                            each={ quote, index in flattenShippingMethods() } 
                            selected={ getSession().shipping_method.code == quote.code }
                            value="{ quote.code }">
                            <span class="text"><qc_raw content="{ quote.title }"></qc_raw></span> <span class="price">{ quote.text }</span>
                        </option>
                    </select>
                </div>
            </form>
        </div>

        <div show={(getConfig().shipping_method.display != 1 && getState().edit)}>
            <div class="ve-card" style="opacity: 0.5">
                <div class="ve-card__header">{ getLanguage().shipping_method.heading_title } <div class="ve-pull-right"><span class="ve-badge ve-badge--warning">{getLanguage().general.text_hidden}<span></div></div>
            </div>
        </div>
    </div>
    <script>
        this.mixin({store:d_quickcheckout_store});

        var tag = this;
        
        change(e){
            this.store.dispatch('shipping_method/update', serializeJSON(Array.from(dv_cash(e.currentTarget).parents('form'))));
        }

        flattenShippingMethods(){
            var result = {};
            for (var name in  this.store.getSession().shipping_methods) {
                var shipping_method = this.store.getSession().shipping_methods[name];
                for (var index in shipping_method.quote){
                    var quote = shipping_method.quote[index];
                    result[name+index] = quote ;
                }
            }
            return result;
        }

        dv_cash(tag.root).on('click', '.ve-radio', function(){
            dv_cash(tag.root).find('.ve-radio').removeClass('ve-radio--selected');
            dv_cash(this).addClass('ve-radio--selected');
        })
    </script>
</qc_shipping_method>
