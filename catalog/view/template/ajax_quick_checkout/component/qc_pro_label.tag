<qc_pro_label>
    <a class="ve-badge ve-badge--warning pro-label" onclick={showAlert}>Go PRO</a>
    <div class="get-pro">
        <div class="get-pro-image"><img src="extension/ajax_quick_checkout/catalog/view/image/ajax_quick_checkout/logo.svg"/></div>
        <div class="get-pro-heading">Get more with PRO.</div>
        <div class="get-pro-text">Wherever you see this label, there is a setting waiting for you! Unlock PRO features like field settings, field sorting, step settings, page settings, layouts and themes. </div>
        <div><strong>Stop losing customers today.</strong><br/><br/></div>
        <div class="get-pro-btn">
            <a class="ve-btn ve-btn--warning ve-btn--hg" href="https://aqc.page.link/getpro" target="_blank">Go PRO -10% OFF</a>
            <br/><br/>
            <small>Ends soon! Will open in a new window</small>
        </div>
    </div>

    <script>
        this.mixin({store:ajax_quick_checkout_store});
        showAlert(){
            alertify.getPro(this.root.querySelector('.get-pro')).set('selector', '#get_pro_popup');
        }
    </script>
</qc_pro_label>