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
                <p class="ve-p" if={getLanguage().payment_method.text_description}>{  getLanguage().payment_method.text_description } </p>
            </div>
            <div class="ve-card__section">
                <div each={error, error_id in getError().payment_method} if={error} class="ve-alert ve-alert--danger has-error"><raw content="{error}"></raw></div>
                <form id="payment_method_list" if={getConfig().payment_method.display_options == 1 && getSession().payment_methods}>
                    <div if={getState().config.guest.payment_method.input_style == 'radio'}  each={ payment_method, name in getSession().payment_methods } class="ve-field" >
                        <label for="{ payment_method.code }" if={ payment_method} class="ve-radio { getSession().payment_method.code == payment_method.code ? 've-radio--selected' : '' }">
                            <img if={ getConfig().payment_method.display_images == 1} class="payment-method-image" src="{payment_method.image}" />
                            <input
                                type="radio"
                                name="payment_method"
                                value="{ payment_method.code }"
                                id="{ payment_method.code }"
                                checked={ getSession().payment_method.code == payment_method.code }
                                class="ve-input"
                                onclick={change} />
                            <i></i>
                            <span class="text">{ payment_method.title }</span> <span class="price">{ payment_method.text }</span>
                        </label>
                    </div>

                    <select if={getState().config.guest.payment_method.input_style == 'select'} class="ve-input" name="payment_method" onchange={change}>
                        <option 
                            if={ payment_method}
                            each={ payment_method, name in getSession().payment_methods }
                            value="{ payment_method.code }"
                            selected={ getSession().payment_method.code == payment_method.code }
                            for="{ payment_method.code }">
                            <span class="text">{ payment_method.title }</span> <span class="price">{ payment_method.text }</span>
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
            <p class="ve-p" if={getLanguage().payment_method.text_description}>{  getLanguage().payment_method.text_description } </p>
            <div each={error, error_id in getError().payment_method} if={error} class="ve-alert ve-alert--danger has-error"><raw content="{error}"></raw></div>
            <form id="payment_method_list" if={getConfig().payment_method.display_options == 1 && getSession().payment_methods}>
                <div if={getState().config.guest.payment_method.input_style == 'radio'}  each={ payment_method, name in getSession().payment_methods } class="ve-field" >
                    <label for="{ payment_method.code }" if={ payment_method} class="ve-radio { getSession().payment_method.code == payment_method.code ? 've-radio--selected' : '' }">
                        <img if={ getConfig().payment_method.display_images == 1} class="payment-method-image" src="{payment_method.image}" />
                        <input
                            type="radio"
                            name="payment_method"
                            value="{ payment_method.code }"
                            id="{ payment_method.code }"
                            checked={ getSession().payment_method.code == payment_method.code }
                            class="ve-input"
                            onclick={change} />
                        <i></i>
                        <span class="text">{ payment_method.title }</span> <span class="price">{ payment_method.text }</span>
                    </label>
                </div>

                <select if={getState().config.guest.payment_method.input_style == 'select'} class="ve-input" name="payment_method" onchange={change}>
                    <option 
                        if={ payment_method}
                        each={ payment_method, name in getSession().payment_methods }
                        value="{ payment_method.code }"
                        selected={ getSession().payment_method.code == payment_method.code }
                        for="{ payment_method.code }">
                        <span class="text">{ payment_method.title }</span> <span class="price">{ payment_method.text }</span>
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
        this.mixin({store:d_quickcheckout_store});

        var tag = this;

        change(e){
            this.store.dispatch('payment_method/update', $(e.currentTarget).parents('form').serializeJSON());
        }

        $(tag.root).on('click', '.ve-radio', function(){
            $(tag.root).find('.ve-radio').removeClass(' ve-radio--selected');
            $(this).addClass(' ve-radio--selected');
        })

    </script>
</qc_payment_method>
