<d_quickcheckout>
    <div if={!getSession().status}>
      <h1>{getLanguage().general.text_cart_title}</h1>
      <p>{getLanguage().general.text_cart_empty}</p>
    </div>

    <div if={getSession().status}>
        <div class="qc-loader" style="display:none">{getLanguage().general.text_loading}</div>
        <qc_layout></qc_layout>
    </div>

    <script>
        this.mixin({store:d_quickcheckout_store});
    </script>
</d_quickcheckout>