<!-- Ajax Quick Checkout v4.2 by Dreamvention.com quickcheckout/register.tpl -->
<div id="shipping_address_wrap" <?php echo (!$shipping_display) ? 'class="qc-hide"' : ''; ?>>
  <div class="panel panel-default">
    <div class="panel-heading">
      <span class="wrap"><span class="fa fa-fw qc-icon-shipping-address"></span></span> 
      <span class="text"><?php echo $shipping_address['title']; ?></span>
    </div>
    <div class="panel-body">
      <?php if($shipping_address['description']) { ?><div class="description"><?php echo $shipping_address['description']; ?></div><?php } ?>

      <?php if ($this->registry->get('customer')->isLogged()) { ?>
        <?php if($address_style == 'radio'){?>
        <div>
          <?php foreach ($addresses as $address) { ?> 
            <div class="radio-input">
              <input type="radio" name="shipping_address[address_id]" value="<?php echo $address['address_id']; ?>" id="shipping_address_exists_<?php echo $address['address_id']; ?>" <?php echo ($address['address_id'] == $shipping_address['address_id']) ? 'checked="checked"' : ''; ?> class="styled" data-refresh="2" autocomplete='off' />
              <label for="shipping_address_exists_<?php echo $address['address_id']; ?>">
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
              <input type="radio" name="shipping_address[address_id]" value="0" id="shipping_address_exists_0" <?php echo ($shipping_address['address_id'] == 0) ? 'checked="checked"' : ''; ?> class="styled" data-refresh="2" autocomplete='off' />
              <label for="shipping_address_exists_0"><?php echo $text_address_new; ?></label>
          </div>
        </div>
        <?php }else{ ?>
          <div>
            <div id="shipping_address_exists_1_block" class="radio-input">
              <input type="radio" name="shipping_address[exists]" value="1" id="shipping_address_exists_1" <?php echo ($shipping_address['exists']) ? 'checked="checked"' : ''; ?>  class="styled" data-refresh="3" autocomplete='off' />
              <label for="shipping_address_exists_1"><?php echo $text_address_existing; ?></label>
            </div>
          </div>

          <div id="shipping_address_exists_list" class="select-input <?php echo (!$shipping_address['exists']) ?  'qc-hide' : ''; ?>">
            <select name="shipping_address[address_id]" style="width: 100%; margin-bottom: 15px;" data-refresh="4">
              <?php foreach ($addresses as $address) { ?>
                	<option value="<?php echo $address['address_id']; ?>" <?php echo ($address['address_id'] == $shipping_address['address_id']) ? 'selected="selected"' : ''; ?>>
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
            <div id="shipping_address_exists_0_block" class="radio-input">
              <input type="radio" name="shipping_address[exists]" value="0" id="shipping_address_exists_0" <?php echo (!$shipping_address['exists']) ? 'checked="checked"' : ''; ?>  class="styled" data-refresh="3" autocomplete='off' />
              <label for="shipping_address_exists_0"><?php echo $text_address_new; ?></label>
            </div>
          </div>
      <?php } ?>
      <?php } ?>
      <div id="shipping_address" class="form-horizontal <?php echo ($shipping_address['exists']) ?  'qc-hide' : '';?>">
        <?php echo $field_view; ?>
      </div>

    </div><!-- /.box-content -->
  </div>
</div>
<?php
// echo '<pre>';
// print_r($this->session->data['shipping_address']);
// echo '</pre>'; 
?>
<script type="text/javascript"><!--
// $('input[name=\'shipping_address[exists]\']').live('click', function() {
// 	if (this.value == '0') {
// 		$('#shipping_address_exists_list').hide();
// 		$('#shipping_address').show();
// 	} else {
// 		$('#shipping_address_exists_list').show();
// 		$('#shipping_address').hide();
// 	}
// });

function refreshShippingAddessZone(value) {
	$.ajax({
		url: 'index.php?route=module/d_quickcheckout/country&country_id=' + value,
		dataType: 'json',			
		success: function(json) {

			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        	html += '<option value="' + json['zone'][i]['zone_id'] + '"';
					if (json['zone'][i]['zone_id'] == '<?php echo $shipping_address['fields']['zone_id']['value']; ?>') {
	      		html += ' selected="selected"';
	    		}
	    		html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			
			$('#shipping_address_wrap select[name=\'shipping_address[zone_id]\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

$('#shipping_address_wrap select[name=\'shipping_address[country_id]\']').bind('change', function(){
	refreshShippingAddessZone($(this).val())	
})

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