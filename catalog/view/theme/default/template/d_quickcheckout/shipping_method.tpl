<!-- Quick Checkout v4.0 by Dreamvention.com quickcheckout/shipping_method.tpl -->
<div id="shipping_method_wrap" <?php echo (!$data['display']) ? 'class="qc-hide"' : ''; ?>>
<?php if ($error_warning) { ?>
<div class="error"><?php echo $error_warning; ?></div>
<?php } ?>

<?php if ($shipping_methods) { ?>

<div class="panel panel-default">
<div class="panel-heading">
    <span class="wrap">
        <span class="fa fa-fw qc-icon-shipping-method"></span>
    </span> 
    <span class="text"><?php echo $data['title']; ?></span>
</div>
<div class="panel-body">
<?php if ($data['description']) { ?> <p class="description"><?php echo $data['description']; ?></p> <?php } ?>

<div class="<?php if (!$data['display_options']) {  echo 'qc-hide';  } ?>">

<?php if($data['input_style'] == 'select'){ ?>
<div class="select-input form-group">
    <select name="shipping_method" class="form-control shipping-method-select" data-refresh="5">
        <?php foreach ($shipping_methods as $shipping_method) { ?>
        <?php foreach ($shipping_method['quote'] as $quote) { ?>
                <?php if ($quote['code'] == $code || !$code) { ?>
                <?php $code = $quote['code']; ?>
                <option  value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" selected="selected" ><?php echo $quote['title']; ?> <?php echo $quote['text']; ?></option>
                <?php } else { ?>
                <option  value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" ><?php echo $quote['title']; ?> <?php echo $quote['text']; ?></option>
                <?php } ?>
                <?php } ?>
            <?php } ?>
    </select> 
</div>
<?php } else { ?> 


<?php foreach ($shipping_methods as $shipping_method) { ?>
    <?php if ($data['display_title']) { ?> 
        <div class="title"><?php echo $shipping_method['title']; ?></div>
    <?php } ?>
    <?php if (!$shipping_method['error']) { ?>
        <?php foreach ($shipping_method['quote'] as $quote) { ?>
            <div class="radio-input radio">
                <label for="<?php echo $quote['code']; ?>">
                <?php if ($quote['code'] == $code || !$code) { ?>
                    <?php $code = $quote['code']; ?>
                    <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" checked="checked"  data-refresh="5" class="styled"/> 
                <?php } else { ?>
                    <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>"  data-refresh="5" class="styled"/> 
                <?php } ?>
                <span class="text"><?php echo $quote['title']; ?></span><span class="price"><?php echo $quote['text']; ?></span></label>
            </div>
        <?php } ?>
    <?php } else { ?>
    	<div class="error alert alert-error"><?php echo $shipping_method['error']; ?></div>
    <?php } ?>
<?php } ?>


<?php } ?>

</div>
<div class="clear"></div>
</div>
</div>

<?php } ?>
</div>
<?php 
// echo '<pre>';
// print_r($this->session->data['shipping_methods']);
// echo '</pre>'; 
?>
<?php
// echo '<pre>';
// print_r($this->session->data['shipping_address']);
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






