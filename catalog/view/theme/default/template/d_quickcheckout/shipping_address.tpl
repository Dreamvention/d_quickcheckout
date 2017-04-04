<div id="shipping_address" class="qc-step" data-col="<?php echo $col; ?>" data-row="<?php echo $row; ?>"></div>
<script type="text/html" id="shipping_address_template">
<div class="<%= (parseInt(model.config.display) == 1) && model.show_shipping_address ? '' : 'hidden' %>">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<span class="icon">
					<i class="<%= model.config.icon %>"></i>
				</span>
				<span class="text"><%= model.config.title %></span>
			</h4>
		</div>
		<div class="panel-body">
			<% if(model.config.description){ %><p class="description"><%= model.config.description %></p><% } %>
			<% if(model.account == 'logged'){ %> 
				<% if(_.size(model.addresses) > 0){ %>
					<p><?php echo $text_address_existing; ?></p>
					<% if(config.design.address_style == 'list') { %>
					<div class="list-group">
					<% _.each (model.addresses, function(address) { %>
						<div class="list-group-item <%= address.address_id == model.shipping_address.address_id ? 'active' : '' %>">
				            <label for="shipping_address_exists_<%= address.address_id %>">  
				            	<input type="radio" name="shipping_address[address_id]" class="shipping-address" value="<%= address.address_id %>" id="shipping_address_exists_<%= address.address_id %>" <%= address.address_id == model.shipping_address.address_id ? 'checked="checked"' : '' %> data-refresh="2" autocomplete='off' /> 
				                <div class="address-item" ><%= sformat(address.address_format, address) %> </div>
				            </label>
				        </div>
			        <% }) %>
					</div>
					<% }else{ %>

					<% _.each (model.addresses, function(address) { %>
			          <div class="radio-input">
			          	<label for="shipping_address_address_id_<%= address.address_id %>">
			            	<input type="radio" name="shipping_address[address_id]" class="shipping-address" value="<%= address.address_id %>" id="shipping_address_address_id_<%= address.address_id %>" <%= address.address_id == model.shipping_address.address_id ? 'checked="checked"' : '' %> data-refresh="2" autocomplete='off' />
			                <strong> <%= address.firstname %> 
			                <%= address.lastname %> </strong> 
			                <%= address.address_1 %> 
			                <%= address.city %> 
			                <%= address.zone %>
			                <%= address.country %>,
							<%= address.postcode %>
			            </label>
			          </div>
			        <% }) %>
			        <% } %>
				<% } %>
				<div class="radio-input">
		            <input type="radio" name="shipping_address[address_id]" class="shipping-address" value="new" id="shipping_address_address_id_new" <%= model.shipping_address.address_id == 'new' ? 'checked="checked"' : '' %> data-refresh="2" autocomplete='off' />
		            <label for="shipping_address_address_id_new">
		                <?php echo $text_address_new; ?>
		            </label>
		        </div>
		        <form id="shipping_address_form" class="form-horizontal <%= model.shipping_address.address_id == 'new' ? '' : 'hidden' %>">
				
				</form>

			<% }else{ %>
			<form id="shipping_address_form" class="form-horizontal">
				
			</form>
			<% } %>
		</div>
	</div>
</div>
</script>
<script>

$(function() {
	qc.shippingAddress = $.extend(true, {}, new qc.ShippingAddress(<?php echo $json; ?>));
	qc.shippingAddressView = $.extend(true, {}, new qc.ShippingAddressView({
		el:$("#shipping_address"), 
		model: qc.shippingAddress, 
		template: _.template($("#shipping_address_template").html())
	}));
	qc.shippingAddressView.setZone(qc.shippingAddress.get('shipping_address.country_id'));
});
</script>