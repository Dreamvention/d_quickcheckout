<d_quickcheckout>
    
    <div if={!getSession().status}>
      <h1>{getLanguage().general.text_cart_title}</h1>
      <p>{getLanguage().general.text_cart_empty}</p>
    </div>

    <div if={getSession().status}>
        <div class="loader" style="display:none">{getLanguage().general.text_loading}</div>
        <layout></layout>
    </div>
</d_quickcheckout>