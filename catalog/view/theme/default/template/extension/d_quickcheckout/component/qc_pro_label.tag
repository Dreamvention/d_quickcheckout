<qc_pro_label>
    <a class="label label-warning pro-label" onclick={showAlert}>get PRO</a>
    <div class="get-pro">
        <div class="get-pro-image"><img src="catalog/view/theme/default/image/d_quickcheckout/logo.svg"/></div>
        <div class="get-pro-heading">Get more with PRO.</div>
        <div class="get-pro-text">Wherever you see this label, there is a setting waiting for you! Unlock PRO features like field settings, field sorting, step settings, page settings, layouts and themes. </div>
        <div><strong>Stop losing customers today.</strong><br/><br/></div>
        <div class="get-pro-btn">
            <a class="btn btn-warning btn-lg" href="https://aqc.page.link/getpro" target="_blank">Get PRO -10% OFF</a>
            <br/><br/>
            <small>Ends soon! Will open in a new window</small>
        </div>
    </div>

    <script>
        this.mixin({store:d_quickcheckout_store});
        showAlert(){
            alertify.getPro ($(this.root).find('.get-pro')[0]).set('selector', '#get_pro_popup');
        }
    </script>
</qc_pro_label>