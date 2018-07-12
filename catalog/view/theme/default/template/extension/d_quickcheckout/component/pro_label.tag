<pro_label>
    <a class="label label-warning pro-label" onclick={showAlert}>get PRO</a>
    <div class="get-pro">
        <div class="get-pro-image"><img src="catalog/view/theme/default/image/d_quickcheckout/logo.svg"/></div>
        <div class="get-pro-heading">Need more flexibility?</div>
        <div class="get-pro-text">Unlock PRO features like field settings and sorting, step settings, page settings, layouts and themes. Wherever you see this label, there is a setting waiting for you!</div>
        <div class="get-pro-btn">
            <a class="btn btn-warning btn-lg" href="https://aqc.page.link/getpro" target="_blank">Unlock PRO with -10% discount</a>
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
</pro_label>