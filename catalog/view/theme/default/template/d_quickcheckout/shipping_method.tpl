<div id="shipping_method" class="qc-step" data-col="<?php echo $col; ?>" data-row="<?php echo $row; ?>"></div>
<script type="text/html" id="shipping_method_template">

<form id="shipping_method_form" <%= parseInt(model.config.display) && model.show_shipping_method ? '' : 'class="hidden"' %>>
	<% if (model.shipping_methods.length != 0 ) { %>
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
				<% if(model.error){ %>
					<div class="alert alert-danger">
						<i class="fa fa-exclamation-circle"></i> <%= model.error %>
					</div>
				<% } %>
				<% if (model.config.description) { %> 
					<p class="description"><%= model.config.description %></p>
				<% } %>
				<div id="shipping_method_list">
					

						<% if(model.config.input_style == 'select') { %>
							<div class="select-input form-group">
								<select name="shipping_method" class="form-control shipping-method-select" data-refresh="5" >
								<% _.each(model.shipping_methods, function(shipping_method) { %>
									<% if (parseInt(model.config.display_title)) { %> 
										<optgroup label="<%= shipping_method.title %>">
									<% } %>
									<% _.each(shipping_method.quote, function(quote) { %>
										<% if (quote.code == model.shipping_method.code) { %>
											<option  value="<%= quote.code %>" id="<%= quote.code %>" selected="selected" ><%= quote.title %> <span class="price"><%= quote.text %></span></option>
										<% } else { %>
											<option  value="<%= quote.code %>" id="<%= quote.code %>" ><%= quote.title %> <span class="price"><%= quote.text %></span></option>
										<% } %>
									<% }) %>
									<% if (parseInt(model.config.display_title)) { %> 
										</optgroup>
									<% } %>
								<% }) %>
								</select>
							</div>


						<% }else{ %>
							<% _.each(model.shipping_methods, function(shipping_method) { %>
								<% if (parseInt(model.config.display_title)) { %> 
									<strong class="title"><%= shipping_method.title %></strong>
								<% } %>

								<% if (!shipping_method.error) { %>
									<% _.each(shipping_method.quote, function(quote) { %>
									  <div class="radio-input radio">
									    <label for="<%= quote.code %>">
									    <% if (quote.code == model.shipping_method.code) { %>
									      <input type="radio" name="shipping_method" value="<%= quote.code %>" id="<%= quote.code %>" checked="checked" data-refresh="5" class="styled"/> 
									    <% } else { %>
									      <input type="radio" name="shipping_method" value="<%= quote.code %>" id="<%= quote.code %>" data-refresh="5" class="styled"/> 
									    <% } %>
									    <span class="text"><%= quote.title %></span><span class="price"><%= quote.text %></span></label>
									  </div>
									<% }) %>
								<% } else { %>
									<div class="error alert alert-error"><%= shipping_method.error %></div>
								<% } %>
							<% }) %>
						<% } %>
					
				</div>
			</div>
		</div>
	<% }else{ %>
    <% if (model.shipping_error) { %> 
       <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <%= model.shipping_error %></div>
   <% } %>
	<% } %>
</form>
</script>
<script>
$(function() {
	qc.shippingMethod = $.extend(true, {}, new qc.ShippingMethod(<?php echo $json; ?>));
	qc.shippingMethodView = $.extend(true, {}, new qc.ShippingMethodView({
		el:$("#shipping_method"), 
		model: qc.shippingMethod, 
		template: _.template($("#shipping_method_template").html())
	}));
});
</script>