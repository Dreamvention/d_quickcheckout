<pro_label>
    <a class="label label-warning pro-label" onclick={showAlert}>get PRO</a>

    <script>

        showAlert(){
            alertify.defaults.theme.ok = "btn btn-primary";
            alertify.defaults.theme.cancel = "btn btn-danger";
            alertify.defaults.theme.input = "form-control";
            alertify.alert('Ajax Quick Checkout PRO','Need more flexibility? Unlock PRO features like field settings, step settings, page settings, layouts and themes. <br/><br/><a target="_blank" href="http://dreamvention.ee/1a3b3300">-10% Coupon AQC7FREE</a> <br/><br/> Upgrade to the Aajx Quick Checkout PRO version today.')
        }
    </script>
</pro_label>