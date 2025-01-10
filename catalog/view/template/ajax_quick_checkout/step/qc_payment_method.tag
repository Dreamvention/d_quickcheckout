<qc_payment_method>
    <div class="step  qc-payment-method">

        <qc_payment_method_setting if={riot.util.tags.selectTags().search('"qc_payment_method_setting"') && getState().edit} step="{opts.step}"></qc_payment_method_setting>

        <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_payment_method_setting"') < 0 && getState().edit}></qc_pro_label>

        <!-- Step -->
        <div class="ve-card" if={getConfig().payment_method.display == 1 && getState().config.guest.payment_method.style == 'card'}>
            <div class="ve-card__header">
                <h4 class="ve-h4">
                    <span if={ getConfig().payment_method.icon } class="icon">
                        <i class="{ getConfig().payment_method.icon }"></i>
                    </span>
                    <span class="text">{ getLanguage().payment_method.heading_title }</span>
                </h4>
                <p class="ve-p" if={getLanguage().payment_method.text_description}><qc_raw content="{  getLanguage().payment_method.text_description }"></qc_raw> </p>
            </div>
            <div class="ve-card__section">
                <div each={error, error_id in getError().payment_method} if={error} class="ve-alert ve-alert--danger has-error"><qc_raw content="{error}"></qc_raw></div>
                <form id="payment_method_list" if={getConfig().payment_method.display_options == 1 && getSession().payment_methods}>
                    <div if={getState().config.guest.payment_method.input_style == 'radio'}  each={ payment_method, name in getSession().payment_methods } class="ve-field" >
                        <div if={ payment_method && getState().opencart_version > '4.0.1.1'}>
                            <p class="qc-title"><qc_raw content="{ payment_method.name }"></qc_raw></p>
                            <div class="ve-field" each={ option, index in payment_method.option } >
                                <label for="{ option.code }" class="ve-radio {(getSession().payment_method.code == option.code) ? 've-radio--selected' : ''}">
                                    <input
                                    type="radio"
                                    class="ve-input"
                                    name="payment_method"
                                    value="{ option.code }"
                                    id="{ option.code }"
                                    checked={ getSession().payment_method.code == option.code }
                                    onclick={change}/>
                                    <i></i>
                                    <span class="text"><qc_raw content="{ option.name }"></qc_raw></span> <span class="price">{ option.text ? option.text : '' }</span>
                                </label>
                            </div>
                        </div>
                        <label for="payment-method__{ payment_method.code }" if={ payment_method && getState().opencart_version <= '4.0.1.1'} class="ve-radio { (getSession().payment_method == payment_method.code ||(payment?.length && getSession().payment_method?.code == payment_method.option[payment[1]].code)) ? 've-radio--selected' : '' }">
                            <img if={ getConfig().payment_method.display_images == 1} class="payment-method-image" src="{payment_method.image}" />
                            <input
                                type="radio"
                                name="payment_method"
                                value="{ payment_method.code }"
                                id="payment-method__{ payment_method.code }"
                                checked={ (getSession().payment_method == payment_method.code ||(payment?.length && getSession().payment_method?.code == payment_method.option[payment[1]].code)) }
                                class="ve-input"
                                onclick={change} />
                            <i></i>
                            <span class="text"><qc_raw content="{ payment_method.title || payment_method.name }"></qc_raw></span> <span class="price">{ payment_method.text }</span>
                        </label>
                    </div>

                    <select if={getState().config.guest.payment_method.input_style == 'select'} class="ve-input" name="payment_method" onchange={change}>
                        <optgroup if={getState().opencart_version > '4.0.1.1'} label="{ payment_method.name }" 
                            each={ payment_method, name in getSession().payment_methods } >
                            <option
                                if={ payment_method }
                                each={ option, index in payment_method.option } 
                                selected={ getSession().payment_method.code == option.code }
                                value="{ option.code }">
                                <span class="text"><qc_raw content="{ option.name }"></qc_raw></span> <span class="price">{ option.text ? option.text : '' }</span>
                            </option>
                        </optgroup>
                        <option 
                            if={ payment_method && getState().opencart_version <= '4.0.1.1' }
                            each={ payment_method, name in getSession().payment_methods }
                            value="{ payment_method.code }"
                            selected={ (getSession().payment_method == payment_method.code ||(payment?.length && getSession().payment_method?.code == payment_method.option[payment[1]].code)) }
                            for="{ payment_method.code }">
                            <span class="text"><qc_raw content="{ payment_method.title || payment_method.name }"></qc_raw></span> <span class="price">{ payment_method.text }</span>
                        </option>
                    </select>
                </form>
            </div>
        </div>

        <div class="ve-mb-3 ve-clearfix" if={getConfig().payment_method.display == 1 && getState().config.guest.payment_method.style == 'clear'}>
            <h4 class="ve-h4">
                <span if={ getConfig().payment_method.icon } class="icon">
                    <i class="{ getConfig().payment_method.icon }"></i>
                </span>
                <span class="text">{ getLanguage().payment_method.heading_title }</span>
            </h4>
            <p class="ve-p" if={getLanguage().payment_method.text_description}><qc_raw content="{  getLanguage().payment_method.text_description }"></qc_raw> </p>
            <div each={error, error_id in getError().payment_method} if={error} class="ve-alert ve-alert--danger has-error"><qc_raw content="{error}"></qc_raw></div>
            <form id="payment_method_list" if={getConfig().payment_method.display_options == 1 && getSession().payment_methods}>
                <div if={getState().config.guest.payment_method.input_style == 'radio'}  each={ payment_method, name in getSession().payment_methods } class="ve-field" >
                    <div if={ payment_method && getState().opencart_version > '4.0.1.1'}>
                        <p class="qc-title"><qc_raw content="{ payment_method.name }"></qc_raw></p>
                        <div class="ve-field" each={ option, index in payment_method.option } >
                            <label for="{ option.code }" class="ve-radio {(getSession().payment_method.code == option.code) ? 've-radio--selected' : ''}">
                                <input
                                type="radio"
                                class="ve-input"
                                name="payment_method"
                                value="{ option.code }"
                                id="{ option.code }"
                                checked={ getSession().payment_method.code == option.code }
                                onclick={change}/>
                                <i></i>
                                <span class="text"><qc_raw content="{ option.name }"></qc_raw></span> <span class="price">{ option.text ? option.text : '' }</span>
                            </label>
                        </div>
                    </div>
                    <label for="payment-method__{ payment_method.code }" if={ payment_method && getState().opencart_version <= '4.0.1.1'} class="ve-radio { (getSession().payment_method == payment_method.code || getSession().payment_method.code == payment_method.code) ? 've-radio--selected' : '' }">
                        <img if={ getConfig().payment_method.display_images == 1} class="payment-method-image" src="{payment_method.image}" />
                        <input
                            type="radio"
                            name="payment_method"
                            value="{ payment_method.code }"
                            id="payment-method__{ payment_method.code }"
                            checked={ (getSession().payment_method == payment_method.code || getSession().payment_method.code == payment_method.code) }
                            class="ve-input"
                            onclick={change} />
                        <i></i>
                        <span class="text"><qc_raw content="{ payment_method.title || payment_method.name }"></qc_raw></span> <span class="price">{ payment_method.text }</span>
                    </label>
                </div>

                <select if={getState().config.guest.payment_method.input_style == 'select'} class="ve-input" name="payment_method" onchange={change}>
                    <optgroup if={getState().opencart_version > '4.0.1.1'} label="{ payment_method.name }" 
                            each={ payment_method, name in getSession().payment_methods } >
                        <option
                            if={ payment_method }
                            each={ option, index in payment_method.option } 
                            selected={ getSession().payment_method.code == option.code }
                            value="{ option.code }">
                            <span class="text"><qc_raw content="{ option.name }"></qc_raw></span> <span class="price">{ option.text ? option.text : '' }</span>
                        </option>
                    </optgroup>
                    <option 
                        if={ payment_method && getState().opencart_version <= '4.0.1.1'}
                        each={ payment_method, name in getSession().payment_methods }
                        value="{ payment_method.code }"
                        selected={ (getSession().payment_method == payment_method.code || getSession().payment_method.code == payment_method.code) }
                        for="{ payment_method.code }">
                        <span class="text"><qc_raw content="{ payment_method.title || payment_method.name }"></qc_raw></span> <span class="price">{ payment_method.text }</span>
                    </option>
                </select>
            </form>
        </div>


        <!-- Hidden Step -->
        <div show={(getConfig().payment_method.display != 1 && getState().edit)}>
            <div class="ve-card" style="opacity: 0.5">
                <div class="ve-card__header">{ getLanguage().payment_method.heading_title } <div class="ve-pull-right"><span class="ve-badge ve-badge--warning">{getLanguage().general.text_hidden}<span></div></div>
            </div>
        </div>
    </div>
    <script>
        this.mixin({store:ajax_quick_checkout_store});

        //for OC 4.0.2.1
        if (this.store.getSession().payment_method?.code) {
            payment = this.store.getSession().payment_method.code.split('.');
        } else {
            payment = false;
        }


        var tag = this;

        change(e){
            this.store.dispatch('payment_method/update', serializeJSON(e.currentTarget));
        }
        this.on("mount", function () {
            Array.from(tag.root.getElementsByClassName('ve-radio')).forEach(function(el) {
                el.addEventListener('click', function(e) {
                    Array.from(tag.root.getElementsByClassName('ve-radio')).forEach(function(elm) {
                        elm.classList.remove('ve-radio--selected');
                    });
                    e.currentTarget.classList.add('ve-radio--selected');
                });
            });
        });
        this.on("update", function(){
            //for OC 4.0.2.1
            if (this.store.getSession().payment_method?.code) {
                payment = this.store.getSession().payment_method.code.split('.');
            }
        });

    </script>
</qc_payment_method>
