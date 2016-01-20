<script>
var config = <?php echo $json_config; ?>;
if(typeof(ga) == "undefined")
   config.general.analytics_event = 0;
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
#d_quickcheckout.container #d_logo{
	margin: 20px 0px;
}
<?php } ?>
</style>
<div id="d_quickcheckout">
	<?php if($config['design']['only_quickcheckout']){ ?>
	<div id="d_logo" class="center-block text-center"></div>
	<?php } ?>
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
	
	$('.qc-step').each(function(){
		$(this).appendTo('.qc-col-' + $(this).attr('data-col'));	
	})
	$('.qc-step').tsort({attr:'data-row'});
<?php if($config['design']['only_quickcheckout']){ ?>
	$('body').prepend($('#d_quickcheckout'));
	$('#d_quickcheckout').addClass('container')
	$('#d_quickcheckout #d_logo ').prepend($('header #logo').html())
<?php } ?>
<?php if(!$config['design']['breadcrumb']) { ?>
	$('.qc-breadcrumb').hide();
<?php } ?>
})
</script>