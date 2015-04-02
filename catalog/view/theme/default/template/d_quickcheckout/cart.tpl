<!-- Quick Checkout v4.2 by Dreamvention.com quickcheckout/cart.tpl -->
<style>
.qc.qc-popup {
  width: <?php echo $settings['design']['cart_image_size']['width']+2; ?>px;
  height: <?php echo $settings['design']['cart_image_size']['height']+2; ?>px;
}
</style>
<div id="cart_wrap">
  <div class="panel panel-default">
    <div class="panel-heading <?php if (!$data['display']) {  echo 'qc-hide';  } ?>">
      <span class="wrap"><span class="qc-icon-cart"></span></span> 
      <span class="text"><?php echo $data['title']; ?></span>
    </div>
  
  <div class="qc-checkout-product panel-body <?php echo (!$data['display']) ? 'qc-hide' : ''; ?>" >
    <?php if(isset($error)){ ?>
      <?php foreach ($error as $error_message){ ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
      <?php } ?>
    <?php } ?>

    <table class="table table-bordered qc-cart">
      <thead>
        <tr>
          <td class="qc-image <?php echo (!$data['columns']['image'])?  'qc-hide' :""; ?>"><?php echo $column_image; ?>:</td>
          <td class="qc-name <?php echo (!$data['columns']['name'])?  'qc-hide' :""; ?>"><?php echo $column_name; ?>:</td>
          <td class="qc-model <?php echo (!$data['columns']['model'])?  'qc-hide' :""; ?>"><?php echo $column_model; ?>:</td>
          <td class="qc-quantity <?php echo (!$data['columns']['quantity'])?  'qc-hide' :""; ?>"><?php echo $column_quantity; ?>:</td>
          <td class="qc-price  <?php echo (!$data['columns']['price'] || $show_price)?  'qc-hide' :""; ?> "><?php echo $column_price; ?>:</td>
          <td class="qc-total <?php  echo (!$data['columns']['total'] || $show_price)?  'qc-hide' :""; ?>"><?php echo $column_total; ?>:</td>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($products as $product) { ?>
        <tr <?php echo(!$product['stock']) ? 'class="stock"' : '' ;?>>
          <td class="qc-image <?php echo (!$data['columns']['image'])?  'qc-hide' : '' ?> ">
            <a  href="<?php echo $product['href']; ?>" data-container="body" data-toggle="popover" data-placement="top" data-content='<img src="<?php echo $product['image']; ?>" />' data-trigger="hover">
              <img src="<?php echo $product['thumb']; ?>" />
            </a>
            <i rel="tooltip" data-help="'.$field['tooltip'] .'"></i>
          </td>
          <td class="qc-name  <?php echo (!$data['columns']['name'])?  'qc-hide' : '' ?> ">
            <a href="<?php echo $product['href']; ?>" <?php echo (!$data['columns']['image'])?  'rel="popup" data-help=\'<img src="'.$product['image'].'"/>\'' : '' ?>> 
              
              <?php echo $product['name']; ?> <?php echo (!$product['stock'])? '<span class="out-of-stock">***</span>' : '' ?>
            </a>
            <?php foreach ($product['option'] as $option) { ?>
              <div> &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small> </div>
            <?php } ?>
            <div class="qc-name-model <?php echo (!$data['columns']['model'])?  'qc-hide' : '' ?>"><span class="title"><?php echo $column_model; ?>:</span> <span class="text"><?php echo $product['model']; ?></span></div>
            <div class="qc-name-price <?php echo (!$data['columns']['price'] || $show_price)?  'qc-hide' : ''; ?>"><span class="title"><?php echo $column_price; ?>:</span> <span class="text"><?php echo $product['price']; ?></span></div>
          </td>
          <td class="qc-model <?php echo (!$data['columns']['model'])?  'qc-hide' : '' ?> "><?php echo $product['model']; ?></td>
          <td class="qc-quantity  <?php echo (!$data['columns']['quantity'])?  'qc-hide' : '' ?> ">
            <div class="input-group">
              <span class="input-group-btn">
                <button class="btn btn-defaut decrease" data-product="<?php echo $product['key']; ?>"><i class="fa fa-minus"></i></button>
              </span>            
              <input type="text" value="<?php echo $product['quantity']; ?>" class="qc-product-qantity form-control text-center" name="cart[<?php echo $product['key']; ?>]"  data-refresh="2"/>
              <span class="input-group-btn">
                <button class="btn btn-defaut increase" data-product="<?php echo $product['key']; ?>"><i class="fa fa-plus"></i></button>
              </span>
            </div>
          </td>
          <td class="qc-price <?php echo (!$data['columns']['price'] || $show_price)?  'qc-hide' : ''; ?> "><?php echo $product['price']; ?></td>
          <td class="qc-total <?php echo (!$data['columns']['total'] || $show_price)?  'qc-hide' : ''; ?> "><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($vouchers as $vouchers) { ?>
        <tr>
          <td class="qc-name <?php echo (!$data['columns']['image'])?  'qc-hide' : '' ?> "></td>
          <td class="qc-name <?php echo (!$data['columns']['name'])?  'qc-hide' : '' ?> "><?php echo $vouchers['description']; ?></td>
          <td class="qc-model <?php echo (!$data['columns']['model'])?  'qc-hide' : '' ?> "></td>
          <td class="qc-quantity <?php echo (!$data['columns']['quantity'])?  'qc-hide' : '' ?> ">1</td>
          <td class="qc-price <?php echo (!$data['columns']['price'] || $show_price)?  'qc-hide' : ''; ?> "><?php echo $vouchers['amount']; ?></td>
          <td class="qc-total <?php echo (!$data['columns']['total'] || $show_price)?  'qc-hide' : '' ?> "><?php echo $vouchers['amount']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>

    <div class="form-horizontal qc-options">
        <div class="row form-group qc-coupon <?php if(!$coupon_status || !$data['option']['coupon']['display']){ echo 'qc-hide';} ?>">
          <label class="col-sm-6 control-label" >
            <?php echo $text_use_coupon; ?>
          </label>
          <div class="col-sm-6 qc-total">
            <div class="input-group">
              <input type="text" value="<?php echo (isset($coupon))?  $coupon : ''; ?>" name="coupon" id="coupon" placeholder="<?php echo $text_use_coupon; ?>" class="form-control"/>
              <span class="input-group-btn">
                <button class="btn btn-primary" id="confirm_coupon" type="button"><i class="fa fa-check"></i></button>
              </span>
            </div>
          </div>
        </div>
        <div class="row form-group qc-voucher <?php if(!$voucher_status || !$data['option']['voucher']['display']){ echo 'qc-hide';} ?>">
          <label class="col-sm-6 control-label" >
            <?php echo $text_use_voucher; ?>
          </label>
          <div class="col-sm-6 qc-total">
            <div class="input-group">
              <input type="text" value="<?php echo (isset($voucher))?  $voucher : ''; ?>" name="voucher" id="voucher" placeholder="<?php echo $text_use_voucher; ?>" class="form-control"/>
              <span class="input-group-btn">
                <button class="btn btn-primary" id="confirm_voucher" type="button"><i class="fa fa-check"></i></button>
              </span>
            </div>
          </div>
        </div>
        <div class="row form-group qc-reward <?php if(!$reward_status || !$data['option']['reward']['display']){ echo 'qc-hide';} ?>">
          <label class="col-sm-6 control-label" >
            <?php echo $text_use_reward; ?>
          </label>
          <div class="col-sm-6 qc-total ">
            <div class="input-group">
              <input type="text" value="<?php echo (isset($reward))?  $reward : ''; ?>" name="reward" id="reward" placeholder="<?php echo $text_use_reward; ?>" class="form-control"/>
              <span class="input-group-btn">
                <button class="btn btn-primary" id="confirm_reward" type="button"><i class="fa fa-check"></i></button>
              </span>
            </div>
          </div>
        </div>
    </div>
    <div class="form-horizontal qc-summary <?php if($show_price){ echo 'qc-hide';}?>">
        <?php foreach ($totals as $total) { ?>
        <div class="row qc-totals">
          <label class="col-xs-6 control-label" ><?php echo $total['title']; ?></label>
          <div class="col-xs-6 form-control-static"><?php echo $total['text']; ?></div>
        </div>
        <?php } ?>
    </div>

  </div>

  </div>
</div>
<script><!--
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

  $(document).on('click', '#quickcheckout .qc-quantity button', function(event){                      
    if($(this).hasClass('increase')){    
      $(this).parent().parent().children('input').val(parseInt($(this).parent().parent().children('input').val())+1)
    }else{
      $(this).parent().parent().children('input').val(parseInt($(this).parent().parent().children('input').val())-1)    
    }
    if($(this).parent().parent().children('input').val() != 0){
      refreshCheckout(4)
    }else{
      refreshAllSteps()
    }
  
  event.stopImmediatePropagation()
})

$(document).on('click', '#quickcheckout #confirm_coupon', function(event){  
  $.ajax({
    url: 'index.php?route=module/quickcheckout/validate_coupon',
    type: 'post',
    data: $('#quickcheckout #coupon'),
    dataType: 'json',
    beforeSend: function() {
      
    },
    complete: function() {
        
    },
    success: function(json) {
      
      $('#quickcheckout #step_6 .qc-checkout-product .error').remove();
      if(json['error']){
        $('#quickcheckout #step_6 .qc-checkout-product').prepend('<div class="error" >' + json['error'] + '</div>');
      }
      $('#quickcheckout #step_6 .qc-checkout-product .success').remove();
      if(json['success']){
        $('#quickcheckout #step_6 .qc-checkout-product').prepend('<div class="success" >' + json['success'] + '</div>');
        refreshCheckout(3)
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });   
  event.stopImmediatePropagation()
})

$(document).on('click', '#quickcheckout #confirm_voucher', function(event){ 
  $.ajax({
    url: 'index.php?route=module/quickcheckout/validate_voucher',
    type: 'post',
    data: $('#quickcheckout #voucher'),
    dataType: 'json',
    beforeSend: function() {
      
    },
    complete: function() {
        
    },
    success: function(json) {
      $('#quickcheckout #step_6 .qc-checkout-product .error').remove();
      
      if(json['error']){
        $('#quickcheckout #step_6 .qc-checkout-product').prepend('<div class="error" >' + json['error'] + '</div>');
      }
      $('#quickcheckout #step_6 .qc-checkout-product .success').remove();
      if(json['success']){
        $('#quickcheckout #step_6 .qc-checkout-product').prepend('<div class="success" >' + json['success'] + '</div>');
        refreshCheckout(3)
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  }); 
  event.stopImmediatePropagation()
})

$(document).on('click', '#quickcheckout #confirm_reward', function(event){  
  $.ajax({
    url: 'index.php?route=module/quickcheckout/validate_reward',
    type: 'post',
    data: $('#quickcheckout #reward'),
    dataType: 'json',
    beforeSend: function() {
      
    },
    complete: function() {
        
    },
    success: function(json) {
      $('#quickcheckout #step_6 .qc-checkout-product .error').remove();
      if(json['error']){
        $('#quickcheckout #step_6 .qc-checkout-product').prepend('<div class="error" >' + json['error'] + '</div>');
      }
      $('#quickcheckout #step_6 .qc-checkout-product .success').remove();
      if(json['success']){
        $('#quickcheckout #step_6 .qc-checkout-product').prepend('<div class="success" >' + json['success'] + '</div>');
        refreshCheckout(3)
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  }); 
  event.stopImmediatePropagation()
})
});
//--></script>
