<div id="payment_view" class="qc-step" data-col="<?php echo $col; ?>" data-row="<?php echo $row; ?>"></div>
<script type="text/html" id="payment_template">
<% if(Number(model.payment_popup)) {%>
	
<div class="modal fade" id="payment_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><span class="text"><%= model.payment_popup_title %></span></h4>
			</div>
			<div class="modal-body clearfix">
				<% if(model.payment){ %>
					<%= model.payment %>
				<% } %>
			</div>
		</div>
	</div>
</div>
<% }else{ %>
	<% if(model.payment){ %>
		<%= model.payment %>
	<% } %>
<% } %>
</script>
<script>

$(function() {
	qc.payment = $.extend(true, {}, new qc.Payment(<?php echo $json; ?>));
	qc.paymentView = $.extend(true, {}, new qc.PaymentView({
		el:$("#payment_view"), 
		model: qc.payment, 
		template: _.template($("#payment_template").html())
	}));

});

</script>

