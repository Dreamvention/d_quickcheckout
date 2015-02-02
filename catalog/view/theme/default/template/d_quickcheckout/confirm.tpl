<!-- Quick Checkout v4.0 by Dreamvention.com quickcheckout/cofirm.tpl -->
<div id="confirm_wrap">
  <div class="panel panel-default">
    <div class="panel-body">
      <div id="confirm_inputs" class="form-horizontal">
        <?php echo $field_view; ?>
      </div> <!-- #confirm_inputs -->
      <div>
        <div class="buttons">
          <div class="right">
            <?php if($button_confirm_display) {?>
              <input type="button" id="qc_confirm_order" class="button btn btn-primary" value="<?php if(isset($payment)){ echo $button_confirm; }else{ echo $button_continue;  } ?>" />
            <?php } ?>
          </div>
        </div>
      </div>
      <div class="clear"></div>
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
});

//--></script>
