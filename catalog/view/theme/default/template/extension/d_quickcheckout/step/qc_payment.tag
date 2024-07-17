<qc_payment>
    <div class="step qc-payment">

        <qc_payment_setting if={riot.util.tags.selectTags().search('"qc_payment_setting"') && getState().edit} step="{opts.step}"></qc_payment_setting>

        <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_payment_setting"') < 0 && getState().edit}></qc_pro_label>

        <!-- Step -->
        <div if={getState().edit && getSession().payment.payment_popup == true}><qc_raw content="{getSession().payment_method?.title}"></qc_raw></div>
        <div class="dvdy-modal" id="payment_modal" if={getSession().payment.payment_popup == true}>
            <div class="dvdy-modal-backdrop"></div>
	        <div class="dvdy-modal-dialog">
		        <div class="dvdy-modal-dialog__content">
                    <div class="ve-card">
                        <div class="ve-card__header">
                            <button type="button" class="dvdy-close">&times;</button>
                            <h4 class="ve-h4"><qc_raw content="{ getSession().payment.payment_popup_title }"></qc_raw></h4>
                        </div>
                        <div class="ve-card__section clearfix">
                            <qc_raw content="{getSession().payment.payment}"></qc_raw>
                        </div>
                    </div>
		        </div>
	        </div>
        </div>
        <div class="ve-card" if={getConfig().payment.display == 1 && getState().config.guest.payment.style == 'card' && getSession().payment.payment_popup == false}>
            <div class="ve-card__header">
                <h4 class="ve-h4">
                    <span if={ getConfig().payment.icon } class="icon">
                        <i class="{ getConfig().payment.icon }"></i>
                    </span>
                    <qc_raw content="{ getLanguage().payment.heading_title }"></qc_raw>
                    
                </h4>
                <p class="ve-p" if={getLanguage().payment.text_description}><qc_raw content="{  getLanguage().payment.text_description }"></qc_raw> </p>
            </div>
            <div class="ve-card__section">
                <div if={getState().edit}><qc_raw content="{getSession().payment_method?.title}"></qc_raw></div>
                <div if={!getState().edit}>
                    <div id="payment" show={ getConfig().payment.display == 1 }></div>
                </div>
            </div>
        </div>

        <div class="ve-mb-3 ve-clearfix" if={getConfig().payment.display == 1 && getState().config.guest.payment.style == 'clear' && getSession().payment.payment_popup == false}>
            <p class="ve-p" if={getLanguage().payment.text_description}><qc_raw content="{  getLanguage().payment.text_description }"></qc_raw> </p>
            <div if={getState().edit}><qc_raw content="{getSession().payment_method?.title}"></qc_raw></div>
            <div if={!getState().edit}>
                <div id="payment" show={ getConfig().payment.display == 1 }></div>
            </div>
        </div>

        <!-- Hidden Step -->
        <div show={(getConfig().payment.display != 1 && getState().edit)}>
            <div class="ve-card" style="opacity: 0.5">
                <div class="ve-card__header">
                    { getLanguage().payment.heading_title } 
                    <div class="ve-pull-right"><span class="ve-badge ve-badge--warning">{getLanguage().general.text_hidden}<span></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        this.mixin({store:d_quickcheckout_store});

        var tag = this;

        var payment = getSession().payment.payment;

        shouldUpdate() { 
            if(this.store.getState().edit){
                return true;
            }
            if(payment != getSession().payment.payment){
                return true 
            }
            return false; 
        }

        this.on('mount', function(){
            dv_cash(this.root).find('.dvdy-close').on('click', function () {
                setState({ session: { payment: { payment: '', }, }, });
            });
            dv_cash('#payment').html(getSession().payment.payment);
            payment = getSession().payment.payment;
            if (dv_cash(this.root).find('#payment_modal').length) {
                dv_cash('body').find('.dqc_payment_modal').remove();
                dv_cash(this.root).find('#payment_modal').appendTo('body').addClass('dqc_payment_modal');
            }
        })

        this.on("updated", function(){
            dv_cash('#payment').html(getSession().payment.payment);
            payment = getSession().payment.payment;
            if (dv_cash(this.root).find('#payment_modal').length) {
                dv_cash('body').find('.dqc_payment_modal').remove();
                dv_cash(this.root).find('#payment_modal').appendTo('body').addClass('dqc_payment_modal');
            }
        });

    </script>
</qc_payment>