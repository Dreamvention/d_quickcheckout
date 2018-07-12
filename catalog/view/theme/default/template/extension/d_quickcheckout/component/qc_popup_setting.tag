<qc_popup_setting>
    <div class="popup-setting">
        <div class="popup-setting-header">
            <h2>{opts.title} {getLanguage().general.text_settings}</h2>
            <a class="close" onclick={close}>
                <i class="fa fa-times" ></i>
            </a>
        </div>
        <div class="popup-setting-footer" >
            <a onclick={save}>{getLanguage().general.button_update}</a>
        </div>
    </div>
    <script>
        this.mixin({store:d_quickcheckout_store});
    </script>
</qc_popup_setting>