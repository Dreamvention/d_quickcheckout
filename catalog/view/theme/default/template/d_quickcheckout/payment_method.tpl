<div id="payment_method" class="qc-step" data-col="<?php echo $col; ?>" data-row="<?php echo $row; ?>"></div>
<script type="text/html" id="payment_method_template" >
<form id="payment_method_form" <%= parseInt(model.config.display) ? '' : 'class="hidden"' %>>
	<% if (model.error_warning) { %>
		<div class="error"><%= model.error_warning %></div>
	<% } %>
	<% if (model.payment_methods.length != 0) { %>
		<div class="panel panel-default" >
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
				<div id="payment_method_list" class="<%= parseInt(model.config.display_options) ? '' : 'hidden' %>">
				<% if(model.config.input_style == 'select') { %>
					<div class="select-input form-group">
						<select name="payment_method" class="form-control payment-method-select" data-refresh="6" >
						<% _.each(model.payment_methods, function(payment_method) { %>
							<% if (payment_method.code == model.payment_method.code) { %>
								<option  value="<%= payment_method.code %>" id="<%= payment_method.code %>" selected="selected" ><%= payment_method.title %> <span class="price"><%= (payment_method.cost) ? payment_method.cost : '' %></span></option>
							<% } else { %>
								<option  value="<%= payment_method.code %>" id="<%= payment_method.code %>" ><%= payment_method.title %> <span class="price"><%= (payment_method.cost) ? payment_method.cost : '' %></span></option>
							<% } %>
						<% }) %>
						</select>
					</div>
					<% _.each(model.payment_methods, function(payment_method) { %>
						<% if (payment_method.terms) { %>
							<% if (payment_method.code == model.payment_method.code) { %>
								<p class="payment-method-terms <%= payment_method.code %>">(<%= payment_method.terms %>)</p>
							<% } else { %>
								<p class="payment-method-terms <%= payment_method.code %> hidden">(<%= payment_method.terms %>)</p>
							<% } %>
							
						<% } %>
					<% }) %>

				<% }else{ %>
					<% _.each(model.payment_methods, function(payment_method) { %>
						<div class="radio-input radio">
							<label for="<%= payment_method.code %>">
								<% if (payment_method.code == model.payment_method.code) { %>
									<input type="radio" name="payment_method" value="<%= payment_method.code %>" id="<%= payment_method.code %>" checked="checked" class="styled"  data-refresh="6"/>
								<% } else { %>
									<input type="radio" name="payment_method" value="<%= payment_method.code %>" id="<%= payment_method.code %>" class="styled"  data-refresh="6"/>
								<% } %>

								<% if(parseInt(model.config.display_images)) { %>
									<img class="payment-image" src="<%= payment_method.image %>" />
								<% } %>
      
								<%= payment_method.title %>
								<span class="price"><%= payment_method.cost ? payment_method.cost : '' %></span>

								<% if (payment_method.terms) { %>
								    <p class="payment-method-terms <%= payment_method.code %>">(<%= payment_method.terms %>)</p>
								<% } %>
							</label>
						</div>
					<% }) %>
				<% } %>
				</div>									
			</div>
		</div>
	<% } else{ %>
    <% if (model.payment_error) { %> 
       <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <%= model.payment_error %></div>
   <% } %>
	<% } %>
</form>
</script>
<script>
$(function() {
	qc.paymentMethod = $.extend(true, {}, new qc.PaymentMethod(<?php echo $json; ?>));
	qc.paymentMethodView = $.extend(true, {}, new qc.PaymentMethodView({
		el:$("#payment_method"), 
		model: qc.paymentMethod, 
		template: _.template($("#payment_method_template").html())
	}));
});
</script>