<qc_cart>
    <div class="step  qc-cart">

        <qc_cart_setting if={riot.util.tags.selectTags().search('"qc_cart_setting"') && getState().edit} step="{opts.step}"></qc_cart_setting>

        <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_cart_setting"') < 0 && getState().edit}></qc_pro_label>

        <!-- Step -->
        <div class="ve-card" if={ getConfig().cart.display == 1 && getState().config.guest.cart.style == 'card' }>
            <div class="ve-card__header">
                <h4 class="ve-h4">
                    <span if={getConfig().cart.icon} class="icon">
                        <i class="{ getConfig().cart.icon }"></i>
                    </span>
                    <span class="text">{ getLanguage().cart.heading_title }</span>
                </h4>
                <p class="ve-p" if={getLanguage().cart.text_description}>{  getLanguage().cart.text_description } </p>
            </div>

            <div class="qc-checkout-product ve-card__section">
                <div each={error, error_id in getError().cart} class="alert alert-danger" if={ error }><qc_raw  content="{error}"></qc_raw></div>
            
                <table class="ve-table ve-table--borderless">
                    <thead class="ve-hidden">
                        <tr>
                            <td class="qc-image" show={ getConfig().cart.columns.image.display == 1} >{ getLanguage().cart.entry_image }</td>
                            <td class="qc-name" show={ getConfig().cart.columns.name.display == 1}>{ getLanguage().cart.entry_name }</td>
                            <td class="qc-model" show={ getConfig().cart.columns.model.display == 1} >{ getLanguage().cart.entry_model }</td>
                            <td class="qc-quantity" show={ getConfig().cart.columns.quantity.display == 1} >{ getLanguage().cart.entry_quantity }</td>
                            <td class="qc-price ve-hidden-xs { ( getConfig().cart.columns.price.display == 1 ) ? '' : 'hidden' }">{ getLanguage().cart.entry_price }</td>
                            <td class="qc-total { ( getConfig().cart.columns.total.display == 1 ) ? '' : 'hidden' }">{ getLanguage().cart.entry_total }</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr each={ product in getSession().cart.products } if={product}>
                            <td class="qc-image" show={ getConfig().cart.columns.image.display == 1} >
                                <a  href="{ raw(product.href) }"
                                    data-container="body"
                                    data-toggle="popover"
                                    data-placement="top"
                                    data-trigger="hover"
                                    class="qc-popover ve-thumbnail" 
                                    data-image="{ product.image }">
                                    <img src="{ product.thumb }" class="img-responsive"/>

                                </a>
                            </td>

                            <td class="qc-name" show={ getConfig().cart.columns.name.display == 1 } >
                                <a href="{ raw(product.href) }" { getConfig().cart.columns.image.display == 1 ? '' : 'rel="popup" data-help=\'<img src="' + product.image + '"/>\'' }>
                                    <qc_raw content="{ product.name }"></qc_raw> <span class="out-of-stock" show={!product.stock}>***</span>
                                </a>
                                <p each={option in product.option}>
                                    <small>{option.name}: {option.value}</small>
                                </p>
                                <p class="ve-help ve-hidden ve-visible--sm">{ product.price } x { product.quantity }</p>
                            </td>

                            <td class="qc-model ve-hidden--sm" show={ getConfig().cart.columns.model.display == 1 }>{ product.model }</td>

                            <td class="qc-quantity ve-hidden--sm" show={ getConfig().cart.columns.quantity.display == 1 }>
                                <div class="ve-input-group">
                                    <button class="ve-btn d-vis ve-btn--primary decrease" data-product="{ product.key }" onclick={decrease}><i class="fa fa-chevron-down"></i></button>
                                    <input type="text" data-mask="9?999999999999999" value="{ product.quantity }"  class="ve-input qc-product-quantity text-center" data-product="{ product.key }" name="cart[{ product.key }]"  data-refresh="2" onchange={change}/>
                                    <button class="ve-btn d-vis ve-btn--primary increase" data-product="{ product.key }" onclick={increase}><i class="fa fa-chevron-up"></i></button>
                                    <button class="ve-btn d-vis ve-btn--danger delete" data-product="{ product.key }"  onclick={delete}><i class="fa fa-times"></i></button>
                                </div>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-btn">
                                        
                                    </span>
                                    <span class="input-group-btn">
                                        
                                    </span>
                                </div>
                            </td>
                            <td class="qc-price ve-hidden--sm { ( getConfig().cart.columns.price.display == 1 )  ? '' : 've-hidden' }">{ product.price }</td>
                            <td class="qc-total { ( getConfig().cart.columns.total.display == 1 )  ? '' : 've-hidden' }">{ product.total }</td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <hr class="ve-hr"/>
            <div class="qc-checkout-product ve-card__section">
                <div class="">

                    <div class="ve-field d-vis qc-coupon" show={getConfig().cart.option.coupon.display == '1'}>
                        
                        <div class="ve-alert ve-alert--danger" if={getState().notifications.cart && getState().notifications.cart.error_coupon}>
                            {getState().notifications.cart.error_coupon}
                        </div>
                        <div class="ve-alert ve-alert--success" if={getState().notifications.cart && getState().notifications.cart.success_coupon}>
                            {getState().notifications.cart.success_coupon}
                        </div>
                        <div class="ve-row">
                            <label class="ve-col-md-4 ve-label" >
                                { getLanguage().cart.entry_coupon }
                            </label>
                            <div class="ve-col-md-8">
                                <div class="ve-field ve-field--block">
                                    <input type="text" value="{getSession().coupon}" name="coupon" placeholder="{ getLanguage().cart.entry_coupon }" class="ve-input" onkeydown={changeCoupon}/>
                                    <button class="ve-btn d-vis ve-btn--default" onclick={useCoupon} type="button">{ getLanguage().cart.button_apply }</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ve-field d-vis qc-voucher" show={getConfig().cart.option.voucher.display == '1'}>
                        <div class="ve-alert ve-alert--danger"  if={getState().notifications.cart && getState().notifications.cart.error_voucher}>
                            {getState().notifications.cart.error_voucher}
                        </div>
                        <div class="ve-alert ve-alert--success" if={getState().notifications.cart && getState().notifications.cart.success_voucher}>
                            {getState().notifications.cart.success_voucher}
                        </div>
                        <div class="ve-row">
                            <label class="ve-col-sm-4 ve-label" >
                                { getLanguage().cart.entry_voucher }
                            </label>
                            <div class="ve-col-sm-8">
                                <div class="ve-field ve-field--block">
                                    <input type="text" value="{getSession().voucher}" name="voucher" placeholder="{ getLanguage().cart.entry_voucher }" class="ve-input" onkeydown={changeVoucher}/>
                                    <button class="ve-btn d-vis ve-btn--default" onclick={useVoucher} type="button">{ getLanguage().cart.button_apply }</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="ve-field d-vis qc-reward" show={getConfig().cart.option.reward.display == '1'}>
                        <div class="ve-alert ve-alert--danger" if={getState().notifications.cart && getState().notifications.cart.error_reward}>
                            {getState().notifications.cart.error_reward}
                        </div>
                        <div class="ve-alert ve-alert--success" if={getState().notifications.cart && getState().notifications.cart.success_reward}>
                            {getState().notifications.cart.success_reward}
                        </div>
                        <div class="ve-row">
                            <label class="ve-col-sm-4 ve-label" >
                                { getLanguage().cart.entry_reward }
                            </label>
                            <div class="ve-col-sm-8">
                                <div class="ve-field ve-field--block">
                                    <input class="ve-input" type="text" value="{getSession().reward}" name="reward" placeholder="{ getLanguage().cart.entry_reward }" onkeydown={changeReward} />
                                    <button class="ve-btn d-vis ve-btn--default" onclick={useReward} type="button">{ getLanguage().cart.button_apply }</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-horizontal qc-totals">
                    <div class="ve-row ve-clearfix qc-total" each={total in getSession().totals} if={total}>
                        <label class="ve-col-sm-9 ve-col-6 ve-label" >{ total.title }</label>
                        <div class="ve-col-sm-3 ve-col-6 text-right">{ total.text }</div>
                    </div>
                </div>

            </div>
        </div>


        <!-- Step -->
        <div if={ getConfig().cart.display == 1 && getState().config.guest.cart.style == 'clear' } class="ve-mb-3 ve-clearfix">
            <h4 class="ve-h4">
                <span if={getConfig().cart.icon} class="icon">
                    <i class="{ getConfig().cart.icon }"></i>
                </span>
                <span class="text">{ getLanguage().cart.heading_title }</span>
            </h4>
            <p class="ve-p" if={getLanguage().cart.text_description}>{  getLanguage().cart.text_description } </p>

            <div class="qc-checkout-product">
                <div each={error, error_id in getError().cart} class="alert alert-danger" if={ error }><qc_raw  content="{error}"></qc_raw></div>
            
                <table class="ve-table ve-table--borderless">
                    <thead class="ve-hidden">
                        <tr>
                            <td class="qc-image" show={ getConfig().cart.columns.image.display == 1} >{ getLanguage().cart.entry_image }</td>
                            <td class="qc-name" show={ getConfig().cart.columns.name.display == 1}>{ getLanguage().cart.entry_name }</td>
                            <td class="qc-model" show={ getConfig().cart.columns.model.display == 1} >{ getLanguage().cart.entry_model }</td>
                            <td class="qc-quantity" show={ getConfig().cart.columns.quantity.display == 1} >{ getLanguage().cart.entry_quantity }</td>
                            <td class="qc-price ve-hidden-xs { ( getConfig().cart.columns.price.display == 1 ) ? '' : 'hidden' }">{ getLanguage().cart.entry_price }</td>
                            <td class="qc-total { ( getConfig().cart.columns.total.display == 1 ) ? '' : 'hidden' }">{ getLanguage().cart.entry_total }</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr each={ product in getSession().cart.products } if={product}>
                            <td class="qc-image" show={ getConfig().cart.columns.image.display == 1} >
                                <a  href="{ raw(product.href) }"
                                    data-container="body"
                                    data-toggle="popover"
                                    data-placement="top"
                                    data-trigger="hover"
                                    class="qc-popover ve-thumbnail" 
                                    data-image="{ product.image }">
                                    <img src="{ product.thumb }" class="img-responsive"/>

                                </a>
                            </td>

                            <td class="qc-name" show={ getConfig().cart.columns.name.display == 1 } >
                                <a href="{ raw(product.href) }" { getConfig().cart.columns.image.display == 1 ? '' : 'rel="popup" data-help=\'<img src="' + product.image + '"/>\'' }>
                                    <qc_raw content="{ product.name }"></qc_raw>  <span class="out-of-stock" show={!product.stock}>***</span>
                                </a>
                                <p each={option in product.option}>
                                    <small>{option.name}: {option.value}</small>
                                </p>
                                <p class="ve-help ve-hidden ve-visible--sm">{ product.price } x { product.quantity }</p>
                            </td>

                            <td class="qc-model ve-hidden--sm" show={ getConfig().cart.columns.model.display == 1 }>{ product.model }</td>

                            <td class="qc-quantity ve-hidden--sm" show={ getConfig().cart.columns.quantity.display == 1 }>
                                <div class="ve-input-group">
                                    
                                    <button class="ve-btn d-vis ve-btn--primary decrease" data-product="{ product.key }" onclick={decrease}><i class="fa fa-chevron-down"></i></button>
                                    <input type="text" data-mask="9?999999999999999" value="{ product.quantity }"  class="ve-input qc-product-quantity text-center" data-product="{ product.key }" name="cart[{ product.key }]"  data-refresh="2" onchange={change}/>
                                    <button class="ve-btn d-vis ve-btn--primary increase" data-product="{ product.key }" onclick={increase}><i class="fa fa-chevron-up"></i></button>
                                    <button class="ve-btn d-vis ve-btn--danger delete" data-product="{ product.key }"  onclick={delete}><i class="fa fa-times"></i></button>
                                </div>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-btn">
                                        
                                    </span>
                                    <span class="input-group-btn">
                                        
                                    </span>
                                </div>
                            </td>
                            <td class="qc-price ve-hidden--sm { ( getConfig().cart.columns.price.display == 1 )  ? '' : 've-hidden' }">{ product.price }</td>
                            <td class="qc-total { ( getConfig().cart.columns.total.display == 1 )  ? '' : 've-hidden' }">{ product.total }</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr class="ve-hr"/>
            <div class="qc-checkout-product">
                <div class="">

                    <div class="ve-field d-vis qc-coupon" show={getConfig().cart.option.coupon.display == '1'}>
                        
                        <div class="ve-alert ve-alert--danger" if={getState().notifications.cart && getState().notifications.cart.error_coupon}>
                            {getState().notifications.cart.error_coupon}
                        </div>
                        <div class="ve-alert ve-alert--success" if={getState().notifications.cart && getState().notifications.cart.success_coupon}>
                            {getState().notifications.cart.success_coupon}
                        </div>
                        <div class="ve-row">
                            <label class="ve-col-md-4 ve-label" >
                                { getLanguage().cart.entry_coupon }
                            </label>
                            <div class="ve-col-md-8">
                                <div class="ve-field ve-field--block">
                                    <input type="text" value="{getSession().coupon}" name="coupon" placeholder="{ getLanguage().cart.entry_coupon }" class="ve-input" onkeydown={changeCoupon}/>
                                    <button class="ve-btn d-vis ve-btn--default" onclick={useCoupon} type="button">{ getLanguage().cart.button_apply }</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ve-field d-vis qc-voucher" show={getConfig().cart.option.voucher.display == '1'}>
                        <div class="ve-alert ve-alert--danger"  if={getState().notifications.cart && getState().notifications.cart.error_voucher}>
                            {getState().notifications.cart.error_voucher}
                        </div>
                        <div class="ve-alert ve-alert--success" if={getState().notifications.cart && getState().notifications.cart.success_voucher}>
                            {getState().notifications.cart.success_voucher}
                        </div>
                        <div class="ve-row">
                            <label class="ve-col-sm-4 ve-label" >
                                { getLanguage().cart.entry_voucher }
                            </label>
                            <div class="ve-col-sm-8">
                                <div class="ve-field ve-field--block">
                                    <input type="text" value="{getSession().voucher}" name="voucher" placeholder="{ getLanguage().cart.entry_voucher }" class="ve-input" onkeydown={changeVoucher}/>
                                    <button class="ve-btn d-vis ve-btn--default" onclick={useVoucher} type="button">{ getLanguage().cart.button_apply }</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="ve-field d-vis qc-reward" show={getConfig().cart.option.reward.display == '1'}>
                        <div class="ve-alert ve-alert--danger" if={getState().notifications.cart && getState().notifications.cart.error_reward}>
                            {getState().notifications.cart.error_reward}
                        </div>
                        <div class="ve-alert ve-alert--success" if={getState().notifications.cart && getState().notifications.cart.success_reward}>
                            {getState().notifications.cart.success_reward}
                        </div>
                        <div class="ve-row">
                            <label class="ve-col-sm-4 ve-label" >
                                { getLanguage().cart.entry_reward }
                            </label>
                            <div class="ve-col-sm-8">
                                <div class="ve-field ve-field--block">
                                    <input class="ve-input" type="text" value="{getSession().reward}" name="reward" placeholder="{ getLanguage().cart.entry_reward }" onkeydown={changeReward} />
                                    <button class="ve-btn d-vis ve-btn--default" onclick={useReward} type="button">{ getLanguage().cart.button_apply }</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-horizontal qc-totals">
                    <div class="ve-row ve-clearfix qc-total" each={total in getSession().totals} if={total}>
                        <label class="ve-col ve-col-sm-9 ve-label" >{ total.title }</label>
                        <div class="ve-col ve-col-sm-3 text-right">{ total.text }</div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Hidden Step -->
        <div show={(getConfig().cart.display != 1 && getState().edit)}>
            <div class="ve-card" style="opacity: 0.5">
                <div class="ve-card__header">{ getLanguage().cart.heading_title } <div class="ve-pull-right"><span class="ve-badge ve-badge--warning">{getLanguage().general.text_hidden}<span></div></div>
            </div>
        </div>
    </div>
    <script>
        this.mixin({store:d_quickcheckout_store});

        var tag = this;
        tag.cartLoading = false;

        change(e){
            var $input = $(e.currentTarget);
            var product_id = $(e.currentTarget).data('product');
            var quantity = $input.val()
            var data = {};

            var state = this.store.getState();
            $.each(state.session.cart.products, function(i, product){
                if(product.key == product_id){
                    state.session.cart.products[i].quantity = quantity;
                }

            })
            this.store.setState(state);

            data[$input.attr('name')] = parseInt(quantity);

            this.store.dispatch('cart/update', data);
        }

        increase(e){
            var $input = $(e.currentTarget).parents('.qc-quantity').find('input.qc-product-quantity');
            var product_id = $(e.currentTarget).data('product');
            var quantity = parseInt($input.val()) + 1;
            data = {};

            var state = this.store.getState();
            $.each(state.session.cart.products, function(i, product){
                if(product.key == product_id){
                    state.session.cart.products[i].quantity = quantity;
                }

            })
            this.store.setState(state);

            data[$input.attr('name')] = quantity;

            this.store.dispatch('cart/update', data);
        }

        decrease(e){
            var $input = $(e.currentTarget).parents('.qc-quantity').find('input.qc-product-quantity');
            var product_id = $(e.currentTarget).data('product');
            var quantity = parseInt($input.val()) - 1;
            var data = {};

            var state = this.store.getState();
            $.each(state.session.cart.products, function(i, product){
                if(product.key == product_id){
                    state.session.cart.products[i].quantity = quantity;
                }

            })
            this.store.setState(state);

            data[$input.attr('name')] = quantity;

            this.store.dispatch('cart/update', data);
        }


        delete(e){
            var $input = $(e.currentTarget).parents('.qc-quantity').find('input.qc-product-quantity');
            var data = {};
            data[$input.attr('name')] = 0;

            this.store.dispatch('cart/update', data);
        }

        changeCoupon(e){
            var state = this.store.getState();
            if($(e.currentTarget).val() != state.session.coupon){
                $(tag.root).find('.qc-coupon .ve-btn')
                            .removeClass('ve-btn--default')
                            .addClass('ve-btn--primary')
            }
            
        }

        useCoupon(e){
            tag.cartLoading = true;
            var coupon = $(e.currentTarget).parents('.qc-coupon').find('input[name="coupon"]').val();
            this.store.dispatch('cart/update_option', {coupon: coupon});
            $(tag.root).find('.qc-coupon .ve-btn')
                            .removeClass('ve-btn--default')
                            .addClass('ve-btn--primary')
        }

        changeVoucher(e){
            var state = this.store.getState();
            if($(e.currentTarget).val() != state.session.voucher){
                $(tag.root).find('.qc-voucher .ve-btn--default')
                            .removeClass('ve-btn--default')
                            .addClass('ve-btn--primary ');
            }
            
        }

        useVoucher(e){
            var voucher = $(e.currentTarget).parents('.qc-voucher').find('input[name="voucher"]').val();
            this.store.dispatch('cart/update_option', {voucher: voucher});
            $(tag.root).find('.qc-voucher .ve-btn')
                            .removeClass('ve-btn--default')
                            .addClass('ve-btn--primary')
        }

        changeReward(e){
            var state = this.store.getState();
            if($(e.currentTarget).val() != state.session.reward){
                $(tag.root).find('.qc-reward .ve-btn--default')
                            .removeClass('ve-btn--default')
                            .addClass('ve-btn--primary');
            }
            
        }

        useReward(e){
            var reward = $(e.currentTarget).parents('.qc-reward').find('input[name="reward"]').val();
            this.store.dispatch('cart/update_option', {reward: reward});
            $(tag.root).find('.qc-reward .ve-btn')
                .removeClass('ve-btn--default')
                .addClass('ve-btn--primary')
        }

        initPopover(){
            $('.popover').popover('hide');
            $('.qc-image .qc-popover').popover({
                'html': true,
                'trigger' : 'hover',
                'content' : function () {
                    return '<img src="'+$(this).data('image') + '" />';
                }
            })
        }

        this.on('mount', function(){
            this.initPopover();
        })

        this.on('updated', function(){
            this.initPopover();
        })
    </script>
</qc_cart>