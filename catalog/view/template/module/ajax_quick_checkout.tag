<ajax_quick_checkout>
    <div if={!getSession().status}>
      <h1>{getLanguage().general.text_cart_title}</h1>
      <p>{getLanguage().general.text_cart_empty}</p>
    </div>

    <div if={getSession().status}>
        <div class="qc-loader" style="display:none">{getLanguage().general.text_loading}</div>
        <qc_layout></qc_layout>
    </div>

    <script>
        this.mixin({store:ajax_quick_checkout_store});
    </script>
</ajax_quick_checkout>