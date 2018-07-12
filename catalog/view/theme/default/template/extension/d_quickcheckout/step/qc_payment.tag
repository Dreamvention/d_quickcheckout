<qc_payment>
    <div class="step">

        <qc_payment_setting if={riot.util.tags.selectTags().search('"qc_payment_setting"') && getState().edit} step="{opts.step}"></qc_payment_setting>

        <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_payment_setting"') < 0 && getState().edit}></qc_pro_label>

        <!-- Step -->
        <div class="panel panel-default" if={getState().edit} >
            <div class="panel-heading">
                <h4 class="panel-title">
                    <span class="icon">
                        <i class="{ getConfig().payment.icon }"></i>
                    </span>
                    { getLanguage().payment.heading_title }
                </h4>
                <h5 if={getLanguage().payment.text_description}>{  getLanguage().payment.text_description } </h5>
            </div>
            <div class="panel-body" id="payment" show={ getConfig().payment.display == 1 }>

            </div>
        </div>
        <div if={!getState().edit}>
            <div id="payment" show={ getConfig().payment.display == 1 }>

            </div>
        </div>

        <!-- Hidden Step -->
        <div show={(getConfig().payment.display != 1 && getState().edit)}>
            <div class="panel panel-default" style="opacity: 0.5">
                <div class="panel-heading">
                    { getLanguage().payment.heading_title } 
                    <div class="pull-right"><span class="label label-warning">{getLanguage().general.text_hidden}<span></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        this.mixin({store:d_quickcheckout_store});

        var tag = this;

        var payment = getSession().payment.payment;

        shouldUpdate() { 
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