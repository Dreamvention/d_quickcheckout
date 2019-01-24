<qc_payment>
    <div class="step qc-payment">

        <qc_payment_setting if={riot.util.tags.selectTags().search('"qc_payment_setting"') && getState().edit} step="{opts.step}"></qc_payment_setting>

        <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_payment_setting"') < 0 && getState().edit}></qc_pro_label>

        <!-- Step -->
        <div class="ve-card" if={getConfig().payment.display == 1 && getState().config.guest.payment.style == 'card'}>
            <div class="ve-card__header">
                <h4 class="ve-h4">
                    <span if={ getConfig().payment.icon } class="icon">
                        <i class="{ getConfig().payment.icon }"></i>
                    </span>
                    { getLanguage().payment.heading_title }
                </h4>
                <p class="ve-p" if={getLanguage().payment.text_description}>{  getLanguage().payment.text_description } </p>
            </div>
            <div class="ve-card__section">
                <div if={getState().edit}>{getSession().payment_method.title}</div>
                <div if={!getState().edit}>
                    <div id="payment" show={ getConfig().payment.display == 1 }></div>
                </div>
            </div>
        </div>

        <div class="ve-mb-3 ve-clearfix" if={getConfig().payment.display == 1 && getState().config.guest.payment.style == 'clear'}>
            <p class="ve-p" if={getLanguage().payment.text_description}>{  getLanguage().payment.text_description } </p>
            <div if={getState().edit}>{getSession().payment_method.title}</div>
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
            $(tag.root).find('#payment').html(getSession().payment.payment);
            payment = getSession().payment.payment;
        })

        this.on("updated", function(){
            $(tag.root).find('#payment').html(getSession().payment.payment);
            payment = getSession().payment.payment;
        });

    </script>
</qc_payment>