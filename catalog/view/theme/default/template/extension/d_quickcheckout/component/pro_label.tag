<pro_label>
    <a class="label label-warning pro-label" onclick={showAlert}>get PRO</a>

    <script>

       

        showAlert(){
            alertify.defaults.theme.ok = "btn btn-primary";
            alertify.defaults.theme.cancel = "btn btn-danger";
            alertify.defaults.theme.input = "form-control";
            alertify.alert('Get AQC PRO right now','You can unlock PRO features like field settings, page settings and more. You need to upgrade to the AQC PRO version. <br/><br/><a target="_blank" href="https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=9132">Learn more</a>');
        }
    </script>
</pro_label>