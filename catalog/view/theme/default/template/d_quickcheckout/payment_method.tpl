<!-- Quick Checkout v4.0 by Dreamvention.com quickcheckout/payment_method.tpl -->
<div id="payment_method_wrap" <?php echo (!$data['display']) ? 'class="qc-hide"' : ''; ?>>
<?php if ($error_warning) { ?>
<div class="error"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>
<div class="panel panel-default" >
  <div class="panel-heading <?php if (!$data['display']) {  echo 'qc-hide';  } ?>">
    <span class="wrap"><span class="fa fa-fw qc-icon-payment-method"></span></span> 
    <span class="text"><?php echo $data['title']; ?></span>
  </div>
  <div class="panel-body">
  	<?php if ($data['description']) { ?> <p class="description"><?php echo $data['description']; ?></p> <?php } ?>
    <div class="payment-methods <?php if (!$data['display_options']) {  echo 'qc-hide';  } ?>">
      <?php if($data['input_style'] == 'select'){ ?>
      <div class="select-input form-group">
        <select name="payment_method" class="form-control payment-method-select" data-refresh="6" >
          <?php foreach ($payment_methods as $payment_method) { ?>
          <?php if ($payment_method['code'] == $code || !$code) { ?>
          <?php $code = $payment_method['code']; ?>
          <option  value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" selected="selected" ><?php echo $payment_method['title']; ?> <span class="price"><?php if (isset($payment_method['cost'])) { echo $payment_method['cost']; } ?></span></option>
          <?php } else { ?>
          <option  value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" ><?php echo $payment_method['title']; ?> <span class="price"><?php if (isset($payment_method['cost'])) { echo $payment_method['cost']; } ?></span></option>
          <?php } ?>
          <?php } ?>
        </select>
      </div>
      <?php }else{?>
      <?php foreach ($payment_methods as $payment_method) { ?>
      <div class="radio-input radio">
        <label for="<?php echo $payment_method['code']; ?>">
          <?php if ($payment_method['code'] == $code || !$code) { ?>
            <?php $code = $payment_method['code']; ?>
            <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" checked="checked" class="styled"  data-refresh="6"/>
          <?php } else { ?>
            <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" class="styled"  data-refresh="6"/>
          <?php } ?>
        
          <?php if(file_exists(DIR_IMAGE.'data/payment/'.$payment_method['code'].'.png')) { ?>
          <img class="payment-image <?php if (!$data['display_images']) {  echo 'qc-hide';  } ?>" src="image/data/payment/<?php echo $payment_method['code']; ?>.png" />
          <?php } ?>
          <?php echo $payment_method['title']; ?><span class="price"><?php if (isset($payment_method['cost'])) { echo $payment_method['cost']; } ?></span></label>
      </div>
      <?php } ?>
      <?php } ?>
    </div>
    <div class="clear"></div>
  </div>
</div>
<?php } ?>
</div>
<?php 
//echo '<pre>';
// print_r($this->session->data['payment_methods']);
// echo '</pre>'; 
?>
<?php 

// echo '<pre>';
// print_r($this->session->data['payment_method']);
// echo '</pre>'; 
?>
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
});
//--></script>
