<qc_cart>
    <div class="step">

        <qc_cart_setting if={riot.util.tags.selectTags().search('"qc_cart_setting"') && getState().edit} step="{opts.step}"></qc_cart_setting>

        <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_cart_setting"') < 0 && getState().edit}></qc_pro_label>

        <!-- Step -->
        <div class="panel panel-default" show={ getConfig().cart.display == 1 }>
            <div class="panel-heading">
                <h4 class="panel-title">
                    <span class="icon">
                        <i class="{ getConfig().cart.icon }"></i>
                    </span>
                    <span class="text">{ getLanguage().cart.heading_title }</span>
                </h4>
                <h5 if={getLanguage().cart.text_description}>{  getLanguage().cart.text_description } </h5>
            </div>

            <div class="qc-checkout-product panel-body" >
                <div each={error, error_id in getError().cart} class="alert alert-danger" if={ error }>{error}<raw  content="{error}"></raw></div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td class="qc-image" show={ getConfig().cart.columns.image.display == 1} >{ getLanguage().cart.entry_image }</td>
                            <td class="qc-name" show={ getConfig().cart.columns.name.display == 1}>{ getLanguage().cart.entry_name }</td>
                            <td class="qc-model" show={ getConfig().cart.columns.model.display == 1} >{ getLanguage().cart.entry_model }</td>
                            <td class="qc-quantity" show={ getConfig().cart.columns.quantity.display == 1} >{ getLanguage().cart.entry_quantity }</td>
                            <td class="qc-price hidden-xs { ( getConfig().cart.columns.price.display == 1 ) ? '' : 'hidden' }">{ getLanguage().cart.entry_price }</td>
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
                                    class="qc-popover" 
                                    data-image="{ product.image }">
                                    <img src="{ product.thumb }" class="img-responsive"/>

                                </a>
                            </td>

                            <td class="qc-name" show={ getConfig().cart.columns.name.display == 1 } >
                                <a href="{ raw(product.href) }" { getConfig().cart.columns.image.display == 1 ? '' : 'rel="popup" data-help=\'<img src="' + product.image + '"/>\'' }>
                                    { product.name } <span class="out-of-stock" show={!product.stock}>***</span>
                                </a>
                            </td>

                            <td class="qc-model hidden-xs" show={ getConfig().cart.columns.model.display == 1 }>{ product.model }</td>

                            <td class="qc-quantity" show={ getConfig().cart.columns.quantity.display == 1 }>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary decrease hidden-xs" data-product="{ product.key }" onclick={decrease}><i class="fa fa-chevron-down"></i></button>
                                    </span>
                                    <input type="text" data-mask="9?999999999999999" value="{ product.quantity }" class="qc-product-quantity form-control text-center" data-product="{ product.key }" name="cart[{ product.key }]"  data-refresh="2" onchange={change}/>
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary increase hidden-xs" data-product="{ product.key }" onclick={increase}><i class="fa fa-chevron-up"></i></button>

                                        <button class="btn btn-danger delete hidden-xs" data-product="{ product.key }"  onclick={delete}><i class="fa fa-times"></i></button>
                                    </span>
                                </div>
                            </td>
                            <td class="qc-price hidden-xs { ( getConfig().cart.columns.price.display == 1 )  ? '' : 'hidden' }">{ product.price }</td>
                            <td class="qc-total { ( getConfig().cart.columns.total.display == 1 )  ? '' : 'hidden' }">{ product.total }</td>
                        </tr>
                    </tbody>
                </table>

                <div class="form-horizontal">
                    <div class="form-group qc-coupon" show={getConfig().cart.option.coupon.display == '1'}>
                        <div class="col-sm-12" if={getState().notifications.cart && getState().notifications.cart.error_coupon}>
                            <div class="alert alert-danger">
                                {getState().notifications.cart.error_coupon}
                            </div>
                        </div>
                        <div class="col-sm-12" if={getState().notifications.cart && getState().notifications.cart.success_coupon}>
                            <div class="alert alert-success">
                                {getState().notifications.cart.success_coupon}
                            </div>
                        </div>
                        <label class="col-sm-4 control-label" >
                            { getLanguage().cart.entry_coupon }
                        </label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" value="{getSession().coupon}" name="coupon" placeholder="{ getLanguage().cart.entry_coupon }" class="form-control"/>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" onclick={useCoupon} type="button"><i class="fa fa-check"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group qc-voucher" show={getConfig().cart.option.voucher.display == '1'}>
                        <div class="col-sm-12" if={getState().notifications.cart && getState().notifications.cart.error_voucher}>
                            <div class="alert alert-danger">
                                {getState().notifications.cart.error_voucher}
                            </div>
                        </div>
                        <div class="col-sm-12" if={getState().notifications.cart && getState().notifications.cart.success_voucher}>
                            <div class="alert alert-success">
                                {getState().notifications.cart.success_voucher}
                            </div>
                        </div>
                        <label class="col-sm-4 control-label" >
                            { getLanguage().cart.entry_voucher }
                        </label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" value="{getSession().voucher}" name="voucher" placeholder="{ getLanguage().cart.entry_voucher }" class="form-control"/>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" onclick={useVoucher} type="button"><i class="fa fa-check"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>


                    <div class="form-group qc-reward" show={getConfig().cart.option.reward.display == '1'}>
                        <div class="col-sm-12" if={getState().notifications.cart && getState().notifications.cart.error_reward}>
                            <div class="alert alert-danger">
                                {getState().notifications.cart.error_reward}
                            </div>
                        </div>
                        <div class="col-sm-12" if={getState().notifications.cart && getState().notifications.cart.success_reward}>
                            <div class="alert alert-success">
                                {getState().notifications.cart.success_reward}
                            </div>
                        </div>
                        <label class="col-sm-4 control-label" >
                            { getLanguage().cart.entry_reward }
                        </label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" value="{getSession().reward}" name="reward" placeholder="{ getLanguage().cart.entry_reward }" class="form-control"/>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" onclick={useReward} type="button"><i class="fa fa-check"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-horizontal qc-totals">
                    <div class="row" each={total in getSession().totals} if={total}>
                        <label class="col-sm-9 col-xs-6 control-label" >{ total.title }</label>
                        <div class="col-sm-3 col-xs-6 form-control-static text-right">{ total.text }</div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Hidden Step -->
        <div show={(getConfig().cart.display != 1 && getState().edit)}>
            <div class="panel panel-default" style="opacity: 0.5">
                <div class="panel-heading">{ getLanguage().cart.heading_title } <div class="pull-right"><span class="label label-warning">{getLanguage().general.text_hidden}<span></div></div>
            </div>
        </div>
    </div>
    <script>
        this.mixin({store:d_quickcheckout_store});

        var tag = this;

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

        useCoupon(e){
            var coupon = $(e.currentTarget).parents('.qc-coupon').find('input[name="coupon"]').val();
            this.store.dispatch('cart/update', {coupon: coupon});
        }

        useVoucher(e){
            var voucher = $(e.currentTarget).parents('.qc-voucher').find('input[name="voucher"]').val();
            this.store.dispatch('cart/update', {voucher: voucher});
        }

        useReward(e){
            var reward = $(e.currentTarget).parents('.qc-reward').find('input[name="reward"]').val();
            this.store.dispatch('cart/update', {reward: reward});
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

        this.on('update', function(){
            this.initPopover();
        })
    </script>
</qc_cart>