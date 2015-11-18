<script>
var config = <?php echo $json_config; ?>;
</script>
<style>
<?php echo $config['design']['custom_style']; ?>
<?php if($config['design']['only_quickcheckout']){ ?>
body > *{
	display: none
}
body > #d_quickcheckout{
	display: block;
} 
#d_quickcheckout.container #logo{
	margin: 20px 0px;
}
<?php } ?>
</style>
<div id="d_quickcheckout">
	<div id="logo" class="center-block text-center"></div>
	<?php echo $field; ?>
	<div class="row">
		<div class="col-md-12"></div>
	</div>
	<div class="qc-col-0">
		<?php echo $login; ?>
		<?php echo $payment_address; ?>
		<?php echo $shipping_address; ?>
		<?php echo $shipping_method; ?>
		<?php echo $payment_method; ?>
		<?php echo $cart; ?>
		<?php echo $payment; ?>
		<?php echo $confirm; ?>
	</div>
	<div class="row">
		<div class="qc-col-1 col-md-<?php echo $config['design']['column_width'][1] ?>">
		</div>
		<div class="col-md-<?php echo $config['design']['column_width'][4] ?>">
			<div class="row">
				<div class="qc-col-2 col-md-<?php echo  ($config['design']['column_width'][4]) ? round(($config['design']['column_width'][2] / $config['design']['column_width'][4])*12) : 0;  ?>">
    			</div>
    			<div class="qc-col-3 col-md-<?php echo ($config['design']['column_width'][4]) ? 12 - round(($config['design']['column_width'][2] / $config['design']['column_width'][4])*12) : 0; ?>">
    			</div>
				<div class="qc-col-4 col-md-12">
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(function() {
	
<<<<<<< HEAD
		refreshCheckout(0, function(){
			validateAllFields(function(){
				confirmOrderQC(function(){
					$('.processing-payment').show()
					triggerPayment()	
				})	
			})
		})

	event.stopImmediatePropagation()
});

function triggerPayment(){
	console.log('triggerPayment') ;
	var href = $("<?php echo (!empty($settings['general']['trigger'])) ? $settings['general']['trigger'] : '#confirm_payment .button, #confirm_payment .btn, #confirm_payment input[type=submit]' ; ?>").attr('href');
	if(href != '' && href != undefined) {
        console.log('clicked')
        document.location.href = href;
			
	}else{
		console.log('clicked')
		$("<?php echo (!empty($settings['general']['trigger'])) ? $settings['general']['trigger'] : '#confirm_payment .button, #confirm_payment .btn, #confirm_payment input[type=submit]' ; ?>").trigger("click", function(){

		})
	}
}

/* 	Validation
*
*	Validate all fields in the checkout
*/
function validateAllFields(func){
	console.log('validateAllFields')
	$.ajax({
		url: 'index.php?route=module/d_quickcheckout/validate_all_fields',
		type: 'post',
		data:  $('#quickcheckout input[data-require=require], #quickcheckout select[data-require=require],#quickcheckout textarea[data-require=require]'),
		dataType: 'json',
		beforeSend: function() {
		},
		complete: function() {
		},
		success: function(json) {
			//console.log(json)
			$('.text-danger').remove()
			$('.has-error').removeClass('has-error')
			var error = false;
			if("error" in json){
				console.log(json);
				if ($('#payment_address').is(':visible')  && json['error']['payment_address']) {
					$.each(json['error']['payment_address'], function(key, value){
						$('#payment_address_wrap [name=\'payment_address\['+key+'\]\']').parents('[class*=-input]').addClass('has-error').append('<div class="col-xs-12 text-danger">' + value + '</div>');
					});
					error = true;
				}
				if ($('#shipping_address').is(':visible') && json['error']['shipping_address'] ) {
					$.each(json['error']['shipping_address'], function(key, value){
						$('#shipping_address_wrap [name=\'shipping_address\['+key+'\]\']').parents('[class*=-input]').addClass('has-error').append('<div class="col-xs-12 text-danger">' + value + '</div>');
					});
					error = true;
				}
				
				if ($('#shipping_method_wrap').is(':visible') && json['error']['shipping_method'] ) {
					$.each(json['error']['shipping_method'], function(key, value){
						$('#shipping_method_wrap ').prepend('<div class="alert alert-danger">' + value + '</div>');
					});
					error = true;
				}
				
				if ($('#payment_method_wrap').is(':visible') && json['error']['payment_method'] ) {
					$.each(json['error']['payment_method'], function(key, value){
						$('#payment_method_wrap ').prepend('<div class="alert alert-danger">' + value + '</div>');
					});
					error = true;
				}
				
				if ($('#confirm_wrap').is(':visible') && json['error']['confirm'] ) {
					error = true;
					$.each(json['error']['confirm'], function(key, value){
						if(key == 'error_warning'){
							$.each(json['error']['confirm']['error_warning'], function(key, value){
								$('#confirm_wrap .checkout-product').append('<div class="col-xs-12 text-danger">' + value + '</div>');
							});
						}else{
						$('#confirm_wrap [name=\'confirm\['+key+'\]\']').parents('[class*=-input]').addClass('has-error').append('<div class="col-xs-12 text-danger">' + value + '</div>');
						}
					});
					
				}
			}
			if(error == false){
				if (typeof func == "function") func(); 
			}else{
				$('html,body').animate({
				scrollTop: $(".text-danger").offset().top-60},
				'slow');	
			}
				
			
			
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

/* 	
*	Validate changed field in checkout
*/
function validateField(fieldId, func){
	console.log('validateField')
	if($('#'+fieldId).attr('data-require') == 'require'){
		$.ajax({
			url: 'index.php?route=module/d_quickcheckout/validate_field',
			type: 'post',
			data:  $('#quickcheckout input') + '&field='+$('#'+fieldId).attr('name')+'&value='+$('#'+fieldId).val(),
			dataType: 'json',
			beforeSend: function() {
				
			},
			complete: function() {
				
			},
			success: function(json) {
				$('#'+fieldId).parents('[class*=-input]').removeClass('has-error').find('.text-danger').remove()
				if("error" in json){
				
					$('#'+fieldId).parents('[class*=-input]').addClass('has-error').append('<div class="col-xs-12 text-danger">'+json['error']+'</div>')
				}
				if (typeof func == "function") func(); 
				
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

/* 	
*	Validate Checkboxes and radio buttons
*/
function validateCheckbox(fieldId, func){
	console.log('validateCheckbox:' + 'field='+$('#'+fieldId).attr('name')+'&value='+$('#'+fieldId).val()) 
	if($('#'+fieldId).attr('data-require') == 'require'){
		
		$.ajax({
			url: 'index.php?route=module/d_quickcheckout/validate_field',
			type: 'post',
			data:  'field='+$('#'+fieldId).attr('name')+'&value='+$('#'+fieldId).val(),
			dataType: 'json',
			beforeSend: function() {

			},
			complete: function() {
				
			},
			success: function(json) {
				$('#'+fieldId).parents('[class*=-input]').find('.text-danger').remove()
				if("error" in json){
					$('#'+fieldId).parents('[class*=-input]').append('<div class="col-xs-12 text-danger">'+json['error']+'</div>')
				}
				if (typeof func == "function") func(); 
				
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

/* 	
*	Confirm Order
*/
function confirmOrderQC(func){
	console.log('confirmOrderQC') 
	$.ajax({
		url: 'index.php?route=module/d_quickcheckout/confirm_order',
		type: 'post',
		data:   $('#quickcheckout input[type=\'text\'], #quickcheckout input[type=\'password\'], #quickcheckout input[type=\'checkbox\']:checked, #quickcheckout input[type=\'radio\']:checked, #quickcheckout select'),
		dataType: 'html',
		beforeSend: function() {
			
		},
		complete: function() {
			
		},
		success: function(html) {
			console.log(html) 
			// bug with payment address rewriting shipping address 
			refreshStepView(1, function(){
				refreshStepView(2, function(){
					refreshStepView(3, function(){
						if (typeof func == "function") func();
					});
				});	
			});	
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}



/*	
*	Actions
*/
$(document).on('focus', 'input[name=\'payment_address[password]\']', function() {
	$(this).on('change', function() {
		$('input[name=\'payment_address[confirm]\']').next('.alert').remove()
	});
});

$(document).on('click', '#quickcheckout input[name="payment_address[shipping]"]', function(event) {			
<?php if(!$settings['design']['uniform']){?>	
	if ($(this).is(':checked')) {
		$(this).val(1) 
	} else {
		$(this).val(0)
	}
<?php } ?>
	refreshCheckout(3)
	event.stopImmediatePropagation()
});

/*
*	Change values of text or select(dropdown)
*/
$(document).on('focus', '#quickcheckout input[type=text], #quickcheckout input[type=password], #quickcheckout select, #quickcheckout textarea', function(event) {
	//setTimeout(function(){
	$(this).on('change', function(e) {
		var dataRefresh = $(this).attr('data-refresh');

		validateField( $(this).attr('id') )
		
		if(dataRefresh){refreshCheckout(dataRefresh)}
		e.stopImmediatePropagation()
	});
	//}, 50);
	event.stopImmediatePropagation()
});

$(document).on('click', '#quickcheckout .qc-quantity span', function(event){											
    if($(this).hasClass('increase')){	   
   		$(this).parent().children('input').val(parseInt($(this).parent().children('input').val())+1)
    }else{
    	$(this).parent().children('input').val(parseInt($(this).parent().children('input').val())-1)		
    }
    if($(this).parent().children('input').val() != 0){
    	refreshCheckout(4)
    }else{
    	refreshAllSteps()
    }
	
	event.stopImmediatePropagation()
})

$(document).on('click', '#quickcheckout #confirm_coupon', function(event){	
	$.ajax({
		url: 'index.php?route=module/d_quickcheckout/validate_coupon',
		type: 'post',
		data: $('#quickcheckout #coupon'),
		dataType: 'json',
		beforeSend: function() {
			
		},
		complete: function() {
				
		},
		success: function(json) {
			
			$('#quickcheckout #step_6 .qc-checkout-product .alert').remove();
			if(json['error']){
				$('#quickcheckout #step_6 .qc-checkout-product').prepend('<div class="alert alert-danger" >' + json['error'] + '</div>');
			}
			if(json['success']){
				$('#quickcheckout #step_6 .qc-checkout-product').prepend('<div class="alert alert-success" >' + json['success'] + '</div>');
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
		url: 'index.php?route=module/d_quickcheckout/validate_voucher',
		type: 'post',
		data: $('#quickcheckout #voucher'),
		dataType: 'json',
		beforeSend: function() {
			
		},
		complete: function() {
				
		},
		success: function(json) {
			$('#quickcheckout #step_6 .qc-checkout-product .alert').remove();
			
			if(json['error']){
				$('#quickcheckout #step_6 .qc-checkout-product').prepend('<div class="alert alert-danger" >' + json['error'] + '</div>');
			}
			if(json['success']){
				$('#quickcheckout #step_6 .qc-checkout-product').prepend('<div class="alert alert-success" >' + json['success'] + '</div>');
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
		url: 'index.php?route=module/d_quickcheckout/validate_reward',
		type: 'post',
		data: $('#quickcheckout #reward'),
		dataType: 'json',
		beforeSend: function() {
			
		},
		complete: function() {
				
		},
		success: function(json) {
			$('#quickcheckout #step_6 .qc-checkout-product .alert').remove();
			if(json['error']){
				$('#quickcheckout #step_6 .qc-checkout-product').prepend('<div class="alert alert-danger" >' + json['error'] + '</div>');
			}
			if(json['success']){
				$('#quickcheckout #step_6 .qc-checkout-product').prepend('<div class="alert alert-success" >' + json['success'] + '</div>');
				refreshCheckout(3)
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
	event.stopImmediatePropagation()
})

/*
*	Change values of checkbox or radio or select(click)
*/


$(document).on('click', '#quickcheckout  input[type=checkbox]', function(event) {
	console.log('#quickcheckout  input[type=checkbox]') 										
<?php if(!$settings['design']['uniform']){?>	
	if ($(this).is(':checked')) {
		$(this).val(1) 
	} else {
		$(this).val(0)
	}
=======
	$('.qc-step').each(function(){
		$(this).appendTo('.qc-col-' + $(this).attr('data-col'));	
	})
	$('.qc-step').tsort({attr:'data-row'});
<?php if($config['design']['only_quickcheckout']){ ?>
	$('body').prepend($('#d_quickcheckout'));
	$('#d_quickcheckout').addClass('container')
	$('#d_quickcheckout #logo ').prepend($('header #logo').html())
<?php } ?>
<?php if(!$config['design']['breadcrumb']) { ?>
	$('.qc-breadcrumb').hide();
>>>>>>> master
<?php } ?>
})
</script>