
<!-- Quick Checkout v4.0 by Dreamvention.com quickcheckout/register.tpl -->
<div id="payment_address_wrap" <?php echo (!$payment_address['display']) ? 'class="qc-hide"' : ''; ?>>
  <div class="panel panel-default">
    <div class="panel-heading">
      <span class="wrap"><span class="fa fa-fw qc-icon-profile"></span></span> 
      <span class="text"><?php echo $payment_address['title']; ?></span>
    </div>
    <div class="panel-body">
      <?php if($payment_address['description']) { ?><div class="description"><?php echo $payment_address['description']; ?></div><?php } ?>

      <?php if ($this->registry->get('customer')->isLogged()) { ?>
      
      <?php if($address_style  == 'radio'){?>
      <div>
        <?php foreach ($addresses as $address) { ?>
          <div class="radio-input">
            <input type="radio" name="payment_address[address_id]" value="<?php echo $address['address_id']; ?>" id="payment_address_exists_<?php echo $address['address_id']; ?>" <?php echo ($address['address_id'] == $payment_address['address_id']) ? 'checked="checked"' : ''; ?> class="styled" data-refresh="2" autocomplete='off' />
            <label for="payment_address_exists_<?php echo $address['address_id']; ?>">
                <?php echo $address['firstname']; ?> 
                <?php echo $address['lastname']; ?>, 
                <?php echo $address['address_1']; ?>, 
                <?php echo $address['city']; ?>, 
                <?php echo $address['zone']; ?>, 
                <?php echo $address['country']; ?>
            </label>
          </div>
        <?php } ?>
         <div class="radio-input">
            <input type="radio" name="payment_address[address_id]" value="0" id="payment_address_exists_0" <?php echo ($payment_address['address_id'] == 0) ? 'checked="checked"' : ''; ?> class="styled" data-refresh="2" autocomplete='off' />
            <label for="payment_address_exists_0"><?php echo $text_address_new; ?></label>
        </div>
      </div>
      <?php }else{ ?>
      <div>
        <div id="payment_address_exists_1_block" class="radio-input">
          <input type="radio" name="payment_address[exists]" value="1" id="payment_address_exists_1" <?php echo ($payment_address['exists']) ? 'checked="checked"' : ''; ?> class="styled" data-refresh="1" autocomplete='off' />
          <label for="payment_address_exists_1"><?php echo $text_address_existing; ?></label>
        </div>
      </div>
      <div id="payment_address_exists_list" class="select-input <?php echo (!$payment_address['exists']) ?  'qc-hide' : ''; ?>">
        <select name="payment_address[address_id]" style="width: 100%; margin-bottom: 15px;" data-refresh="3">
          <?php foreach ($addresses as $address) { ?>
              <option value="<?php echo $address['address_id']; ?>" <?php echo ($address['address_id'] == $payment_address['address_id']) ? 'selected="selected"' : ''; ?>> 
                <?php echo $address['firstname']; ?> 
                <?php echo $address['lastname']; ?>, 
                <?php echo $address['address_1']; ?>, 
                <?php echo $address['city']; ?>, 
                <?php echo $address['zone']; ?>, 
                <?php echo $address['country']; ?> 
              </option>
          <?php } ?>
        </select>
      </div>
      <div>
        <div id="payment_address_exists_0_block" class="radio-input">
          <input type="radio" name="payment_address[exists]" value="0" id="payment_address_exists_0" <?php echo (!$payment_address['exists']) ?  'checked="checked"' : ''; ?>  class="styled" data-refresh="1" autocomplete='off' />
          <label for="payment_address_exists_0"><?php echo $text_address_new; ?></label>
        </div>
      </div>
      <?php } // address_design?>
      <?php } ?>
      <div id="payment_address" class="form-horizontal <?php echo ($payment_address['exists']) ?  'qc-hide' : ''; ?>">
        <?php echo $field_view; ?>
      </div> <!-- #payment_address -->
  </div> <!-- .box-content -->
</div> <!-- .box -->
</div> <!-- #payment_address_wrap -->
<?php
// echo '<pre>';
// print_r($payment_address);
 //echo '</pre>'; 
?>
<script type="text/javascript"><!--
// $('#company_id_input').hide(); 
// $('#tax_id_input').hide();

$('input[name=\'payment_address[exists]\']').on('click', function() {
	if (this.value == '0') {
		$('#payment_address_exists_list').hide();
		$('#payment_address').show();
	} else {
		$('#payment_address_exists_list').show();
		$('#payment_address').hide();
	}
});

function refreshPaymentAddessZone(value) {

	$.ajax({
		url: 'index.php?route=module/d_quickcheckout/country&country_id=' + value,
		dataType: 'json',
		beforeSend: function() {

		},
		complete: function() {

		},		
		success: function(json) {

			if (json['postcode_required'] == '1') {
				$('#payment-postcode-required').show();
			} else {
				$('#payment-postcode-required').hide();
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json['zone'] != '') {

				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
					if (json['zone'][i]['zone_id'] == '<?php echo $payment_address['fields']['zone_id']['value']; ?>') {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			
			$('#payment_address_wrap select[name=\'payment_address[zone_id]\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		});
}

//refreshPaymentAddessZone($('#payment_address_wrap select[name=\'payment_address[country_id]\']').val())

$('#payment_address_wrap select[name=\'payment_address[country_id]\']').bind('change', function(){
	refreshPaymentAddessZone($(this).val())	
})
//switchery
// var elem = document.querySelector('.styled');
//   var init = new Switchery(elem);

$(function(){
	if($.isFunction($.fn.uniform)){
		$(" .styled, input:radio.styled").uniform().removeClass('styled');
	}
  

	if($.isFunction($.fn.colorbox)){
		$('.colorbox').colorbox({
			width: 640,
			height: 480
		});
	}
	if($.isFunction($.fn.fancybox)){
		$('.fancybox').fancybox({
			width: 640,
			height: 480
		});
	}
});
//--></script>
