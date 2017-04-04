<script type="text/html" id="field_template">

<% var col_left = (config.design.block_style == 'block') ? 'col-xs-12' : 'col-xs-5' %>
<% var col_right = (config.design.block_style == 'block') ? 'col-xs-12' : 'col-xs-7' %>
<% var autocomplete = (Number(config.design.autocomplete)) ? 'on' : 'off' %>
<% var telephone_countries = config.design.telephone_countries.split(',') %>
<% var telephone_preferred_countries = config.design.telephone_preferred_countries.split(',') %>
<% var telephone_validation = (Number(config.design.telephone_validation)) %>
<% _.each(model.config.fields,  function(f){ %>
	<% if(model[model.config.id][f.id] !== undefined || f.type == "heading" || f.type == "label"){ %>
		<% if(f.type){ %>
		  	<% var display = Number(f.display) %>
		  	<% var require = Number(f.require) %> 

		  	<% if(f.type == "heading"){ %>

	        	<% if(display) { %>
			
				<div id="<%= model.config.id %>_<%= f.id %>_heading" class="sort-item <%= f.id %> <%= f.class ? f.class : '' %>" data-sort="<%= f.sort_order %>">
				    <i class="fa fa-book"></i>
				    <%= htmlDecode(f.title) %>
				    <hr/>
				</div>
			
				<% } %>

			<% }else if(f.type == "label"){ %>

				<div id="<%= model.config.id %>_<%= f.id %>_label" 
					class="label-input form-group  sort-item <%= display ? '' : 'hidden' %> <%= f.class ? f.class : '' %> <%= require ? 'required' : '' %>" 
					data-sort="<%= f.sort_order %>">
					<div class="col-xs-12">
						<label class="control-label" for="<%= model.config.id %>_<%= f.id %>">
							<%= htmlDecode(f.title) %>
						</label>

						<p id="<%= model.config.id %>_<%= f.id %>" class="label-text" />
							<%= model[f.id] %>
						</p>
					</div>
				</div>

	    	<% }else if(f.type == "radio"){ %>

	    		<% if(f.options){ %>
			    	<div id="<%= model.config.id %>_<%= f.id %>_input" 
				        class="radio-input form-group  sort-item <%= display ? '' : 'hidden' %> <%= f.class ? f.class : '' %> <%= require ? 'required' : '' %>" 
				        data-sort="<%= f.sort_order %>">
				        <div class="<%= col_left %>">
				        	<label class="control-label">
				        		<span <%= f.tooltip ? 'data-toggle="tooltip"' : '' %> title="<%= f.tooltip %>"><%= htmlDecode(f.title) %></span> 
				        	</label>
				        </div>
				        <div class="<%= col_right %>">

			            	<% _.each(f.options, function(option){ %>
				            <div class="radio">
				            	<label for="<%= model.config.id %>_<%= f.id %>_<%= option.value %>">
				            		<input type="radio" 
				            			autocomplete="<%= autocomplete %>" 
						                name="<%= model.config.id %>.<%= f.id %>" 
						                value="<%= option.value %>" 
						                <%= require ? 'required' : '' %>
						                id="<%= model.config.id %>_<%= f.id %>_<%= option.value %>" 
						                <%= option.value == model[model.config.id][f.id] ? 'checked="checked"' : '' %>  
						                class="validate <%= require ? 'required' : 'not-required' %>"  />
				                	<%= option.name %>
				                </label>
				            </div>
			        		<% }) %>
			    		</div>
			    	</div>
			    <% } %>

	    	<% }else if(f.type == "checkbox"){ %>
	    		

			    <div id="<%= model.config.id %>_<%= f.id %>_input" 
			    class="checkbox-input form-group sort-item <%= display ? '' : 'hidden' %> <%= f.class ? f.class : '' %> <%= require ? 'required' : '' %>" 
			    data-sort="<%= f.sort_order %>">
			    	
			    	<% if(f.options){ %>
			    		<%  var i = 0 %> 
			    		<% _.each(f.options, function(option){ %>
		    			<div class="col-xs-12">
					      <label for="<%= model.config.id %>_<%= f.id %>" >
						      <input type="hidden" 
						          name="<%= model.config.id %>.<%= f.id %>" 
						          value="0" />
					          <input type="checkbox" 
					          autocomplete="<%= autocomplete %>" 
					          name="<%= model.config.id %>.<%= f.id %>.<%= i %>" 
					          id="<%= model.config.id %>_<%= f.id %>_<%= option.value %>" 
					          class="validate not-required" 
					          <%= require ? 'required' : '' %> 
					          value="<%= option.value %>" 
					          <%= model[model.config.id][f.id][i] > 0 ? 'checked="checked"' : '' %> />

					          <span <%= f.tooltip ? 'data-toggle="tooltip"' : '' %> title="<%= f.tooltip %>"><%= htmlDecode(option.name) %></span> 
					        </label>
					    </div>
					    <% i++ %>
					    <% })%>
			    	<% }else{ %>
			    		<div class="col-xs-12">
					      <label for="<%= model.config.id %>_<%= f.id %>" class="control-label" >
						      <input type="hidden" 
						          name="<%= model.config.id %>.<%= f.id %>" 
						          value="0" />
					          <input type="checkbox" 
					          autocomplete="<%= autocomplete %>" 
					          name="<%= model.config.id %>.<%= f.id %>" 
					          id="<%= model.config.id %>_<%= f.id %>" 
					          class="validate <%= require ? 'required' : 'not-required' %>" 
					          <%= require ? 'required' : '' %> 
					          value="1" 
					          <%= model[model.config.id][f.id] == 1 ? 'checked="checked"' : '' %> />

					          <span <%= f.tooltip ? 'data-toggle="tooltip"' : '' %> title="<%= f.tooltip %>"><%= htmlDecode(f.title) %></span> 
					        </label>
					    </div>
			    	<% } %>
			    	

			      
			    </div>

	    	<% }else if(f.type == "select"){ %>

				<div id="<%= model.config.id %>_<%= f.id %>_input" 
		        class="select-input form-group  sort-item <%= display ? '' : 'hidden' %> <%= f.class ? f.class : '' %> <%= require ? 'required' : '' %>" 
		        data-sort="<%= f.sort_order %>">
		        <div class="<%= col_left %>">
		          <label class="control-label" for="<%= model.config.id %>_<%= f.id %>"> 
		            <span class="text" <%= f.tooltip ? 'data-toggle="tooltip"' : '' %> title="<%= f.tooltip %>"><%= f.title %></span>
		          </label>
		        </div>
		        <div class="<%= col_right %>">
		          <select name="<%= model.config.id %>.<%= f.id %>" 
		            <%= require ? 'required' : '' %> 
		            id="<%= model.config.id %>_<%= f.id %>"
		            class="form-control <%= require ? 'required' : 'not-required' %> <%= f.id %>"  autocomplete="<%= autocomplete %>"  >
		            <option value=""><?php echo $text_select; ?></option>
		            <% if(f.options){ %>
		                <% _.each(f.options, function(option){ %>
		                    <option value="<%= option.value %>" <%= option.value == model[model.config.id][f.id] ? 'selected="selected"' : '' %> ><%= option.name %></option>
		                <% }) %>
		            <% } %>
		          </select>
		        </div>
		      </div>

	      	<% }else if(f.type == "date" || f.type == "time" || f.type == "datetime"){ %>
	      		<div id="<%= model.config.id %>_<%= f.id %>_input" 
			        class="text-input form-group  sort-item <%= display ? '' : 'hidden' %> <%= f.class ? f.class : '' %> <%= require ? 'required' : '' %>" 
			        data-sort="<%= f.sort_order %>">
			        <div class="<%= col_left %>">
			          <label class="control-label" for="<%= model.config.id %>_<%= f.id %>"> 
			            <span class="text" <%= f.tooltip ? 'data-toggle="tooltip"' : '' %> title="<%= f.tooltip %>"> <%= htmlDecode(f.title) %></span> 
			          </label>
			        </div>
			        <div class="<%= col_right %>"> 
			        	<div class="input-group">
				        	<input type="text" 
					            name="<%= model.config.id %>.<%= f.id %>" 
					            id="<%= model.config.id %>_<%= f.id %>" 
					            value="<%= model[model.config.id][f.id] %>" 
					            class="form-control  <%= f.mask ? 'qc-mask': '' %> <%= f.type %> validate <%= require ? 'required' : 'not-required' %> <%= f.id %>" 
					            autocomplete="<%= autocomplete %>" 
					            qc-mask="<%=f.mask%>" 
					            <% if(f.type == "date"){ %>data-date-format="MM/DD/YYYY" <% } %>
					            <% if(f.type == "time"){ %>data-date-format="HH:mm" <% } %>
					            <% if(f.type == "datetime"){ %>data-date-format="YYYY-MM-DD HH:mm" <% } %>
					            
					            <% if(Number(config.design.placeholder)) {  %>placeholder="<%= require ? '*' : '' %> <%= htmlDecode(f.title).replace(':', '') %>" <% } %>
					            <%= setValidateRules(f.error) %> />
			                <span class="input-group-btn" >
			                	<label type="button" class="btn btn-default" for="<%= model.config.id %>_<%= f.id %>"><i class="fa fa-calendar"></i></label>
			                </span>
		                </div>
			        </div>
			      </div>

			<% }else if(f.type == "textarea"){ %>
	      		<div id="<%= model.config.id %>_<%= f.id %>_input" 
			        class="text-input form-group  sort-item <%= display ? '' : 'hidden' %> <%= f.class ? f.class : '' %> <%= require ? 'required' : '' %>" 
			        data-sort="<%= f.sort_order %>">
			        <div class="col-xs-12">
			          <label class="control-label" for="<%= model.config.id %>_<%= f.id %>"> 
			            <span class="text" <%= f.tooltip ? 'data-toggle="tooltip"' : '' %> title="<%= f.tooltip %>"> <%= htmlDecode(f.title) %></span> 
			          </label>
			        </div>
			        <div class="col-xs-12"> 
			          <textarea
			            name="<%= model.config.id %>.<%= f.id %>" 
			            id="<%= model.config.id %>_<%= f.id %>"
			            class="form-control validate <%= require ? 'required' : 'not-required' %> <%= f.type %> <%= f.id %>" 
			            autocomplete="<%= autocomplete %>" 
			            <% if(Number(config.design.placeholder)) {  %>placeholder="<%= require ? '*' : '' %> <%= htmlDecode(f.title).replace(':', '') %>"<% } %> 
			            <%= setValidateRules(f.error) %> ><%= model[model.config.id][f.id] %></textarea>
			        </div>
			      </div>
			<% }else if(f.type == "tel"){ %>
			      <div id="<%= model.config.id %>_<%= f.id %>_input" 
			        class="text-input form-group  sort-item <%= display ? '' : 'hidden' %> <%= f.class ? f.class : '' %> <%= require ? 'required' : '' %>" 
			        data-sort="<%= f.sort_order %>">
			        <div class="<%= col_left %>">
			          <label class="control-label" for="<%= model.config.id %>_<%= f.id %>"> 
			            <span class="text" <%= f.tooltip ? 'data-toggle="tooltip"' : '' %> title="<%= f.tooltip %>"> <%= htmlDecode(f.title) %></span> 
			          </label>
			        </div>
			        <div class="<%= col_right %>"> 
			          <input type="<%= f.type %>" 
			            name="<%= model.config.id %>.<%= f.id %>" 
			            id="<%= model.config.id %>_<%= f.id %>" 
			            value="<%= model[model.config.id][f.id] %>" 
			            class="form-control <%= f.mask ? 'qc-mask': '' %> <%= require ? 'required' : 'not-required' %> <%= f.id %> <%= telephone_validation ? 'telephone-validation' : '' %> " 
			            autocomplete="<%= autocomplete %>" 
			            data-telephone_countries="<%= telephone_countries %>"
			            data-telephone_preferred_countries="<%= telephone_preferred_countries %>"
			            
			            <% if(f.mask){ %>
			            qc-mask="<%= f.mask %>"
			            <% } %> 
			            <% if(Number(config.design.placeholder)) {  %>placeholder="<%= htmlDecode(f.placeholder).replace(':', '') %>"<% } %> 
			            <%= setValidateRules(f.error) %> />
			        </div>
			      </div>

	    	<% }else{ %>
	    		<div id="<%= model.config.id %>_<%= f.id %>_input" 
			        class="text-input form-group  sort-item <%= display ? '' : 'hidden' %> <%= f.class ? f.class : '' %> <%= require ? 'required' : '' %>" 
			        data-sort="<%= f.sort_order %>">
			        <div class="<%= col_left %>">
			          <label class="control-label" for="<%= model.config.id %>_<%= f.id %>"> 
			            <span class="text" <%= f.tooltip ? 'data-toggle="tooltip"' : '' %> title="<%= f.tooltip %>"> <%= htmlDecode(f.title) %></span> 
			          </label>
			        </div>
			        <div class="<%= col_right %>"> 
			          <input type="<%= f.type %>" 
			            name="<%= model.config.id %>.<%= f.id %>" 
			            id="<%= model.config.id %>_<%= f.id %>" 
			            value="<%= model[model.config.id][f.id] %>" 
			            class="form-control <%= f.mask ? 'qc-mask': '' %> <%= require ? 'required' : 'not-required' %> <%= f.id %>" 
			            autocomplete="<%= autocomplete %>" 
			            <% if(f.mask){ %>
			            qc-mask="<%= f.mask %>"
			            <% } %> 
			            <% if(Number(config.design.placeholder)) {  %>placeholder="<%= require ? '*' : '' %> <%= htmlDecode(f.title).replace(':', '') %>"<% } %> 
			            <%= setValidateRules(f.error) %> />
			        </div>
			      </div>
	    	<% } %>
	    <% } %>
	<% } %>
<% }) %>
</script>
<script>

function htmlDecode(value) {
	return $("<textarea/>").html(value).text();
}

function htmlEncode(value) {
	return $('<textarea/>').text(value).html();
}

function setValidateRules(rules){
	var result = '';
	_.each(rules, function(rule){

		if(rule.min_length){
			result+= 'data-rule-minlength="'+rule.min_length+'" ';
			if(rule.text){
				result+= 'data-msg-minlength="'+rule.text+'" ';
			}
		}
		if(rule.min_length){
			result+= 'data-rule-minlength="'+rule.min_length+'" ';
			if(rule.text){
				result+= 'data-msg-minlength="'+rule.text+'" ';
			}
		}
		if(rule.max_length){
			result+= 'data-rule-maxlength="'+rule.max_length+'" ';
			if(rule.text){
				result+= 'data-msg-maxlength="'+rule.text+'" ';
			}
		}
		if(rule.checked){
			result+= 'data-rule-min="1" ';
			if(rule.text){
				result+= 'data-msg-min="'+rule.text+'" ';
			}
		}

		if(rule.compare_to){
			result+= 'data-rule-equalto="'+rule.compare_to+'" ';
			if(rule.text){
				result+= 'data-msg-equalto="'+rule.text+'" ';
			}
		}

		if(rule.telephone){
			if(rule.text){
				result+= 'data-msg-telephone="'+rule.text+'" ';
			}
		}

		if(rule.email_exists){
			result+= 'data-rule-remote="index.php?route=d_quickcheckout/field/validate_email" ';
			if(rule.text){
				result+= 'data-msg-remote="'+rule.text+'" ';
			}
		}else if(rule.regex){
			result+= 'data-rule-remote="index.php?route=d_quickcheckout/field/validate_regex&regex='+encodeURIComponent(rule.regex)+'" ';
			if(rule.text){
				result+= 'data-msg-remote="'+rule.text+'" ';
			}
		}


		

		
	})
	return result;
}
jQuery.extend(jQuery.validator.messages, {
    required: "<?php echo $error_field_required; ?>",
    email: "<?php echo $error_email; ?>",
    // remote: "Please fix this field.",
    // url: "Please enter a valid URL.",
    // date: "Please enter a valid date.",
    // dateISO: "Please enter a valid date (ISO).",
    // number: "Please enter a valid number.",
    // digits: "Please enter only digits.",
    // creditcard: "Please enter a valid credit card number.",
    // equalTo: "Please enter the same value again.",
    // accept: "Please enter a value with a valid extension.",
    // maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
    // minlength: jQuery.validator.format("Please enter at least {0} characters."),
    // rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
    // range: jQuery.validator.format("Please enter a value between {0} and {1}."),
    // max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
    // min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
});

</script>