<qc_payment_method>
    <div class="step">

        <qc_payment_method_setting if={riot.util.tags.selectTags().search('"qc_payment_method_setting"') && getState().edit} step="{opts.step}"></qc_payment_method_setting>

        <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_payment_method_setting"') < 0 && getState().edit}></qc_pro_label>

        <!-- Step -->
        <div class="panel panel-default payment-method" show={getConfig().payment_method.display == 1}>
            <div class="panel-heading">
                <h4 class="panel-title">
                    <span class="icon">
                        <i class="{ getConfig().payment_method.icon }"></i>
                    </span>
                    <span class="text">{ getLanguage().payment_method.heading_title }</span>
                </h4>
                <h5 if={getLanguage().payment_method.text_description}>{  getLanguage().payment_method.text_description } </h5>
            </div>
            <div class="panel-body">
                <div each={error, error_id in getError().payment_method} if={error} class="alert alert-danger has-error"><raw content="{error}"></raw></div>
                <form id="payment_method_list" if={getConfig().payment_method.display_options == 1 && getSession().payment_methods}>
                    <div if={getConfig().payment_method.input_style == 'radio'}  each={ payment_method, name in getSession().payment_methods } class="radio-input" >
                        <label for="{ payment_method.code }" if={ payment_method} class="qc-radio { getSession().payment_method.code == payment_method.code ? 'qc-radio-selected' : '' }">
                            <img if={ getConfig().payment_method.display_images == 1} class="payment-method-image" src="{payment_method.image}" />
                            <input
                                type="radio"
                                name="payment_method"
                                value="{ payment_method.code }"
                                id="{ payment_method.code }"
                                checked={ getSession().payment_method.code == payment_method.code }
                                onclick={change} />
                            <span class="text">{ payment_method.title }</span> <span class="price">{ payment_method.text }</span>
                        </label>
                    </div>

                    <select if={getConfig().payment_method.input_style == 'select'} class="form-control" name="payment_method" onchange={change}>
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

        <!-- Hidden Step -->
        <div show={(getConfig().payment_method.display != 1 && getState().edit)}>
            <div class="panel panel-default" style="opacity: 0.5">
                <div class="panel-heading">{ getLanguage().payment_method.heading_title } <div class="pull-right"><span class="label label-warning">{getLanguage().general.text_hidden}<span></div></div>
            </div>
        </div>
    </div>
    <script>
        this.mixin({store:d_quickcheckout_store});

        var tag = this;

        change(e){
            this.store.dispatch('payment_method/update', $(e.currentTarget).parents('form').serializeJSON());
        }

        $(tag.root).on('click', '.qc-radio', function(){
            $(tag.root).find('.qc-radio').removeClass('qc-radio-selected');
            $(this).addClass('qc-radio-selected');
        })

    </script>
</qc_payment_method>
