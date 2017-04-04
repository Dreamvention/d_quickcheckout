<div class="qc-step" data-col="<?php echo $col; ?>" data-row="<?php echo $row; ?>">
	<div id="login_view"></div>
	<div id="login_social_login"><?php echo $d_social_login; ?></div>
</div>

<script type="text/html" id="login_template">

<% if(model.account == 'logged'){ %> 
	<% $('#login_social_login').hide() %>
<% }else{ %>

	<% if(model.error) { %>
		<div class="alert alert-danger">
			<i class="fa fa-exclamation-circle"></i> <%= model.error %>
		</div>
	<% } %>
	<% if(parseInt(model.config.option.guest.display) || parseInt(model.config.option.register.display) || (config.design.login_style == 'popup' && parseInt(model.config.option.login.display))){ %>
	<div class="login-btn-group">
		<div class="btn-group btn-group-justified">
		<% if(parseInt(model.config.option.guest.display)){ %>
			<div class="btn-group" role="group">
				<label class="btn <%= model.account == 'guest' ? 'btn-primary active' : 'btn-default' %>">
					<input class="hidden" type="radio" name="account" id="guest" value="guest" autocomplete="off" <%= model.account == 'guest' ? 'checked="checked"' : '' %>> <%= model.config.option.guest.title %>
				</label>
			</div>
		<% } %>
		<% if(parseInt(model.config.option.register.display)){ %>
			<div class="btn-group" role="group">
				<label  class="btn <%= model.account == 'register' ? 'btn-primary active' : 'btn-default' %> <%= parseInt(model.config.option.register.display) ? '' : 'hidden' %>">
					<input  class="hidden" type="radio" name="account" id="register" value="register" autocomplete="off" <%= model.account == 'register' ? 'checked="checked"' : '' %> > <%= model.config.option.register.title %>
				</label>
			</div>	
		<% } %>
		<% if(config.design.login_style == 'popup' && parseInt(model.config.option.login.display)){ %> 
			<div class="btn-group" role="group">	
				<button id="login_button_popup" type="button" class="btn btn-default"  data-toggle="modal" data-target="#login_model"><%= model.config.option.login.title %></button>
			</div>
		<% } %>
		</div>
	</div>
	<% } %>
	<% if(model.config.description){ %><p class="description"><%= model.config.description %></p><% } %>
	<div class="<%= parseInt(model.config.option.login.display) ? '' : 'hidden' %>">
		
		<% if(config.design.login_style == 'block'){ %> 
		<div class="panel panel-default ">
			<div class="panel-heading">
				<span class="icon">
					<i class="<%= model.config.icon %>"></i>
				</span>
				<span class="text"><?php echo $text_returning_customer; ?></span>
			</div>
			<div class="panel-body">

				<form id="login_form">
					<div class="row">
						<div class="form-group col-lg-12 email">
							<label class="control-label" for="login_email"><?php echo $entry_email; ?></label>
							<input type="text" name="email" value="<%= model.email %>" id="login_email" placeholder="<?php echo $entry_email; ?>" class="form-control"/>
						</div>
						<div class="form-group  col-lg-12 password">
							<label class="control-label" for="login_password"><?php echo $entry_password; ?></label> 
							
							<input type="password" name="password" value="<%= model.password %>" id="login_password" placeholder="<?php echo $entry_password; ?>" class="form-control"/>
							<a id="remeber_password" href="<?php echo $forgotten; ?>"><i class="fa fa-angle-right "></i> <?php echo $text_forgotten; ?></a>
						</div>

						<div class="form-group col-sm-12">
							<label class="control-label hidden-lg" >&ensp;</label> 
							<button id="button_login" class="btn btn-primary btn-block"><?php echo $button_login; ?></button>
						</div>
					</div>
				</form>
				
			</div>
		</div>
		<% }else{ %>
		

<div class="modal fade" role="dialog" id="login_model" >
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
        	<span class="icon">
				<i class="<%= model.config.icon %>"></i>
			</span> 
			<?php echo $button_login; ?>
		</h4>
      </div>
      <div class="modal-body">
        <form id="login_form">
			<div class="row">
				<div class="form-group col-lg-12 email">
					<label class="control-label" for="login_email"><?php echo $entry_email; ?></label>
					<input type="text" name="email" value="<%= model.email %>" id="login_email" placeholder="<?php echo $entry_email; ?>" class="form-control"/>
				</div>
				<div class="form-group  col-lg-12 password">
					<label class="control-label" for="login_password"><?php echo $entry_password; ?></label> 
					
					<input type="password" name="password" value="<%= model.password %>" id="login_password" placeholder="<?php echo $entry_password; ?>" class="form-control"/>
					<a id="remeber_password" href="<?php echo $forgotten; ?>"><i class="fa fa-angle-right "></i> <?php echo $text_forgotten; ?></a>
				</div>

				<div class="form-group col-sm-12">
					<label class="control-label hidden-lg" >&ensp;</label> 
					<button id="button_login" class="btn btn-primary btn-block"><?php echo $button_login; ?></button>
				</div>
			</div>
		</form>
      </div>
      
    </div>
  </div>
</div>
<% } %>
</div>
<% } %>
</script>
<script>
$(function() {
	qc.login = $.extend(true, {}, new qc.Login(<?php echo $json; ?>));
	qc.loginView = $.extend(true, {}, new qc.LoginView({
		el:$("#login_view"), 
		model: qc.login, 
		template: _.template($("#login_template").html())
	}));
});

</script>
