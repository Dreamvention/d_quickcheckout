<?php
/*
 *	location: admin/view
 */
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="form-inline pull-right">

				<?php if($stores){ ?>
				<select class="form-control" onChange="location='<?php echo $module_link; ?>&store_id='+$(this).val()">
					<?php foreach($stores as $store){ ?>
					<?php if($store['store_id'] == $store_id){ ?>
					<option value="<?php echo $store['store_id']; ?>" selected="selected" ><?php echo $store['name']; ?></option>
					<?php }else{ ?>
					<option value="<?php echo $store['store_id']; ?>" ><?php echo $store['name']; ?></option>
					<?php } ?>
					<?php } ?>
				</select> 
				<?php } ?>
				<button id="save_and_stay" data-toggle="tooltip" title="<?php echo $button_save_and_stay; ?>" class="btn btn-success"><i class="fa fa-save"></i></button>
				<button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<h1><?php echo $heading_title; ?> <?php echo $version; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>

	<div class="container-fluid">
		<?php if (!empty($error['warning'])) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error['warning']; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<?php if (!empty($success)) { ?>
		<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?> <?php echo $setting_name; ?></h3>
            </div>
            <div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">

			        <ul  class="nav nav-tabs">
			            <li class="active"><a href="#setting" data-toggle="tab">
			                <span class="fa fa-cog"></span> 
			                <?php echo $tab_setting; ?>
			            </a></li>
			            <?php if($debug){?>
			            <li><a href="#debug" data-toggle="tab">
			                <span class="fa fa-bug"></span> 
			                <?php echo $tab_debug; ?>
			            </a></li>
			            <?php } ?>
			            <?php if(!empty($setting['support'])){?>
			            <li><a href="#support" data-toggle="tab">
			                <span class="fa fa-support"></span> 
			                <?php echo $tab_support; ?>
			            </a></li>
			            <?php } ?>
			            <li><a href="#instruction" data-toggle="tab">
			                <span class="fa fa-graduation-cap"></span> 
			                <?php echo $tab_instruction; ?>
			            </a></li>
			        </ul>

					<div class="tab-content">
						<div class="tab-pane active" id="setting" >
							<div class="tab-body">
								<div class="row">
									<div class="col-sm-2">
										<ul class="nav nav-pills s-nav-stacked">
											<li class="active">
												<a href="#d_home" data-toggle="tab">
													<span class="fa fa-home fa-fw"></span> <span><?php echo $text_home; ?></span>
												</a>
											</li>
											<?php if($setting_id) {?>
											<li>
												<a href="#d_general" data-toggle="tab">
													<i class="fa fa-cog fa-fw"></i> <span><?php echo $text_general; ?></span>
												</a>
											</li>
											<li>
												<a href="#d_login" data-toggle="tab">
													<i class="fa fa-key fa-fw"></i> <span><?php echo $text_login; ?></span>
												</a>
											</li>
											<li>
												<a href="#d_payment_address" data-toggle="tab">
													<i class="fa fa-book fa-fw"></i> <span><?php echo $text_payment_address; ?></span>
												</a>
											</li>
											<li>
												<a href="#d_shipping_address" data-toggle="tab">
													<i class="fa fa-book fa-fw"></i> <span><?php echo $text_shipping_address; ?></span>
												</a>
											</li>
											<li>
												<a href="#d_shipping_method" data-toggle="tab">
													<i class="fa fa-truck fa-fw"></i> <span><?php echo $text_shipping_method; ?></span>
												</a>
											</li>
											<li>
												<a href="#d_payment_method" data-toggle="tab">
													<i class="fa fa-credit-card fa-fw"></i> <span><?php echo $text_payment_method; ?></span>
												</a>
											</li>
											<li>
												<a href="#d_confirm" data-toggle="tab">
													<i class="fa fa-shopping-cart fa-fw"></i> <span><?php echo $text_cart; ?> & <?php echo $text_confirm; ?></span>
												</a>
											</li>
											<li>
												<a href="#d_design" data-toggle="tab">
													<i class="fa fa-paint-brush fa-fw"></i> <span><?php echo $text_design; ?></span>
												</a>
											</li>
											<li>
												<a href="#d_analytics" data-toggle="tab">
													<i class="fa fa-bar-chart fa-fw"></i> <span><?php echo $text_analytics; ?></span>
												</a>
											</li>
											<?php } ?>
										</ul>
									</div><!--  /.col-md-2 -->

									<div class="col-sm-10">
										<div class="tab-content">
									       
											<div id="d_home" class="tab-pane active">
									            <div class="page-header">
									                <h3><span class="fa fa-home"></span> <span><?php echo $text_home; ?></span></h3>
									            </div>
									            <?php if(!$settings){ ?>
									            <div class="bs-callout bs-callout-warning"><?php echo $text_intro_create_setting; ?></div>
									            <?php } ?>
									            <div class="row">
													
									            	<?php foreach($settings as $setting_value){ ?>
									                
									                <div id="setting_id_<?php echo $setting_value['setting_id']; ?>" class="col-lg-3 col-md-4 col-sm-6 ">
														<div class="tile  <?php echo ($setting_value['setting_id'] == $setting_id) ? 'selected' : ''; ?>">
															<a href="#" class="view-setting " data-setting-id="<?php echo $setting_value['setting_id']; ?>" >
																<div class="tile-heading clearfix">
																	<?php echo $setting_value['name']; ?>
																	<span class="pull-right" data-toggle="tooltip" title="<?php echo $help_average_time; ?>" ><?php echo gmdate("H:i:s",$setting_value['average_checkout_time']); ?></span>
																</div>
																<div class="tile-body">
																	<a href="<?php echo $setting_value['href']; ?> " target="_blank" data-toggle="tooltip" title="<?php echo $help_view_shop; ?>"><i class="fa fa-eye"></i></a> 
																	<a href="#" class="view-setting " data-setting-id="<?php echo $setting_value['setting_id']; ?>" data-toggle="tooltip" title="<?php echo $help_view_setting; ?>"><i class="fa fa-pencil"></i></a>
																	<h3 class="pull-right" data-toggle="tooltip" title="<?php echo $help_average_rating; ?>"><?php echo round($setting_value['average_rating'] * 100); ?>%</h3>
																	
																</div>
															</a>
															<div class="tile-footer form-inline clearfix">
																<div class="">
																	<?php echo $text_probability; ?>:
																	<div class="input-group pull-right probability">
																		<span class="input-group-btn probability-down">
																	    	<button class="btn btn-default btn-sm " type="button"><i class="fa fa-chevron-down"></i></button>
																	    </span>
      																	<input type="text" style="width:50px; text-align:center" class="form-control input-sm probability-value" name="<?php echo $id ?>_setting_cycle[<?php echo $setting_value['setting_id']; ?>]" value="<?php echo (isset($setting_cycle[$setting_value['setting_id']])) ? $setting_cycle[$setting_value['setting_id']] : 1 ; ?>" aria-describedby="sizing-addon2" />
																		<span class="input-group-btn probability-up">
																	    	<button class="btn btn-default btn-sm " type="button"><i class="fa fa-chevron-up"></i></button>
																	    </span>
																	</div>
																</div>
															</div>
														</div>
													</div>
									                <?php } ?>
									                <script>
									                	$('.probability .probability-down').on('click', function(){
									                		$(this).next().val( $(this).next().val() - 1);
									                		$('.probability-value').trigger('change');
									                	})
									                	$('.probability .probability-up').on('click', function(){
									                		$(this).prev().val( Number($(this).prev().val()) + 1);
									                		$('.probability-value').trigger('change');
									                	})

									                	$('.probability-value').on('change', function(){
									                		if($(this).val() < 0){
									                			$(this).val(0)
									                		}
									                	})
									                </script>
									                
									                <div class="col-lg-3 col-md-4 col-sm-6">
														<div class="tile">
															<div class="tile-heading">
																<?php echo $text_create_setting_heading;?>
															</div>
															<a href="#" id="create_setting" class="create-setting" >
															<div class="tile-body">
																
																	<i class="fa fa-plus"></i>
																	<h3 class="pull-right"><?php echo $text_create_setting;?></h3>
																
															</div>
															</a>
															<div class="tile-footer">
																<?php echo $text_create_setting_probability; ?>
															</div>
														</div>
													</div>


									                
									            </div>
									            <hr/>
									            <div class="row">
									            	<div class="col-md-6">
									            			<div class="form-group">
															<label class="col-sm-4 control-label" for="input_status"><?php echo $entry_status; ?></label>
															<div class="col-sm-8">
																<input type="hidden" value="0" name="<?php echo $id; ?>_status" id="input_status" class="form-control" />
																<input type="checkbox"  value="1" name="<?php echo $id; ?>_status" id="input_status" <?php if (${$id.'_status'}) { ?>checked="checked"<?php } ?> class="form-control" />
															</div>
														</div><!-- //status -->
														
														<div class="form-group">
															<label class="col-sm-4 control-label" for="input-catalog-limit">
																<span data-toggle="tooltip" title="<?php echo $help_trigger; ?>">
																	<?php echo $entry_trigger; ?>
																</span>
															</label>
															<div class="col-sm-8">
																<input type="text" value="<?php echo ${$id.'_trigger'}; ?>" name="<?php echo $id; ?>_trigger" id="trigger" class="form-control"/>
															</div>
														</div>
														

														
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label class="col-sm-4 control-label" for="button_update"><?php echo $entry_update; ?></label>
															<div class="col-sm-4">
																<a id="button_update" class="btn btn-primary btn-block"><i class="fa fa-refresh"></i> <?php echo $button_update; ?></a>
															</div>
															<div class="col-sm-4">
																<div id="notification_update"></div>
															</div>
														</div><!-- //update -->
														<div class="form-group">
															<label class="col-sm-4 control-label" for="button_support_email"><?php echo $entry_support; ?></label>
															<div class="col-sm-4">
																<a href="mailto:<?php echo $support_email; ?>?Subject=Request Support: <?php echo $heading_title; ?>&body=Shop: <?php echo HTTP_SERVER; ?>" id="button_support_email" class="btn btn-primary btn-block"><i class="fa fa-support"></i> <?php echo $button_support_email; ?></a>

															</div>
														</div><!-- //support_email -->
														
														<div class="form-group">
															<label class="col-sm-4 control-label" for="input_debug"><?php echo $entry_debug; ?></label>
															<div class="col-sm-8">
																<input type="hidden" name="<?php echo $id; ?>_debug" value="0" />
																<input type="checkbox" id="input_debug" name="<?php echo $id; ?>_debug" <?php echo ($debug)? 'checked="checked"':'';?> value="1" />
															</div>
														</div>
														<!-- //debug -->
													</div>	
									            </div>
											</div><!-- /#home-->

											<script>
											$('.tab-content a[data-toggle]').click(function(){

											$('.panel .nav-stacked li.active').removeClass('active')
											$('.panel .nav-stacked li a[href="'+$(this).attr('href')+'"]').parent().addClass('active')
											//return false;
											})
											</script>
									        
									        <!---------------------------------- general ---------------------------------->
											<div id="d_general" class="tab-pane">          
												<h3 class="page-header">
													<span class="fa fa-cog fa-fw"></span> <span><?php echo $text_general; ?></span>
												</h3>
												<div class="row">
													<div class="col-md-6">

														

														

														<div class="form-group">
															<label class="col-sm-4 control-label" for="input_setting_name">
																<span data-toggle="tooltip" title="<?php echo $help_name; ?>">
																	<?php echo $entry_name; ?>
																</span>
															</label>
															<div class="col-sm-8">
																<input type="text" value="<?php echo $setting_name; ?>" name="<?php echo $id; ?>_setting[name]" id="input_setting_name" class="form-control"/>
															</div>
														</div>

														

														<div class="form-group">
															<label class="col-sm-4 control-label" for="input-catalog-limit">
																<span data-toggle="tooltip" title="<?php echo $help_general_clear_session; ?>">
																	<?php echo $entry_general_clear_session; ?>
																</span>
															</label>
															<div class="col-sm-8">
																<input type="hidden" value="0" name="<?php echo $id; ?>_setting[general][clear_session]" />
																<input type="checkbox" value="1" name="<?php echo $id; ?>_setting[general][clear_session]" <?php if(isset($setting['general']['clear_session']) && $setting['general']['clear_session'] == 1){ ?>checked="checked"<?php } ?> id="general_clear_session" />
															</div>
														</div>

														<div class="form-group">
															<label class="col-sm-4 control-label" for="input-catalog-limit">
																<span data-toggle="tooltip" title="<?php echo $help_general_login_refresh; ?>">
																	<?php echo $entry_general_login_refresh; ?>
																</span>
															</label>
															<div class="col-sm-8">
																<input type="hidden" value="0" name="<?php echo $id; ?>_setting[general][login_refresh]" />
																<input type="checkbox" value="1" name="<?php echo $id; ?>_setting[general][login_refresh]" <?php if(isset($setting['general']['login_refresh']) && $setting['general']['login_refresh'] == 1){ ?>checked="checked"<?php } ?> id="general_login_refresh" />
															</div>
														</div>

														<!-- <div class="form-group">
															<label class="col-sm-4 control-label" for="input-catalog-limit">
																<span data-toggle="tooltip" title="<?php echo $help_general_default_email; ?>">
																	<?php echo $entry_general_default_email; ?>
																</span>
															</label>
															<div class="col-sm-8">
																<?php if(isset($setting['general']['default_email']) && $setting['general']['default_email'] != ""){ ?>
																<input type="text" value="<?php echo $setting['general']['default_email']; ?>" name="<?php echo $id; ?>_setting[general][default_email]" id="general_default_email" class="form-control">
																<?php }else{ ?>
																<input type="text" value="0" name="<?php echo $id; ?>_setting[general][default_email]" id="general_default_email" class="form-control"/>
																<?php } ?>
															</div>
														</div> -->

														<div class="form-group">
															<label class="col-sm-4 control-label" for="input-catalog-limit">
																<span data-toggle="tooltip" title="<?php echo $help_general_analytics_event; ?>">
																	<?php echo $entry_general_analytics_event; ?>
																</span>
															</label>
															<div class="col-sm-8">
																<input type="hidden" value="0" name="<?php echo $id; ?>_setting[general][analytics_event]" />
																<input type="checkbox" value="1" name="<?php echo $id; ?>_setting[general][analytics_event]" <?php if(isset($setting['general']['analytics_event']) && $setting['general']['analytics_event'] == 1){ ?>checked="checked"<?php } ?> id="general_analytics_event" />
															</div>
														</div>

														<div class="form-group">
															<label class="col-sm-4 control-label" for="input-catalog-limit">
																<span data-toggle="tooltip" title="<?php echo $help_general_compress; ?>">
																	<?php echo $entry_general_compress; ?>
																</span>
															</label>
															<div class="col-sm-8">
																<input type="hidden" value="0" name="<?php echo $id; ?>_setting[general][compress]" />
																<input type="checkbox" value="1" name="<?php echo $id; ?>_setting[general][compress]" <?php if(isset($setting['general']['compress']) && $setting['general']['compress'] == 1){ ?>checked="checked"<?php } ?> id="general_compress" />
															</div>
														</div>

														<?php if ($config_files) { ?>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="select_config"><?php echo $entry_config_files; ?></label>
															<div class="col-sm-8">
																<select id="select_config" name="<?php echo $id; ?>_setting[general][config]"  class="form-control">
																	<?php foreach ($config_files as $config_file) { ?>
																	<option value="<?php echo $config_file; ?>" <?php echo ($config_file == $config)? 'selected="selected"' : ''; ?>><?php echo $config_file; ?></option>
																	<?php } ?>
																</select>
															</div>
														</div>
														<?php } ?>
														<!-- //config -->
													</div>

													<div class="col-md-6">
							
														

														<div class="form-group">
															<label class="col-sm-4 control-label" for="input-catalog-limit">
																<span data-toggle="tooltip" title="<?php echo $help_general_min_order; ?>">
																	<?php echo $entry_general_min_order; ?>
																</span>
															</label>
															<div class="col-sm-8">
																<div class="input-group">
																	<label for="general_min_order_value" id="label_general_min_order_value" class="input-group-addon">%s</label>
																	<?php if(isset($setting['general']['min_order']['value']) && $setting['general']['min_order']['value'] != ""){ ?>
																	<input type="text" value="<?php echo $setting['general']['min_order']['value']; ?>" name="<?php echo $id; ?>_setting[general][min_order][value]" id="general_min_order_value" class="form-control"/>
																	<?php }else{ ?>
																	<input type="text" value="0" name="<?php echo $id; ?>_setting[general][min_order][value]" class="form-control" id="general_min_order_value"/>
																	<?php } ?>
																</div>

																<?php foreach ($languages as $language) { ?>
																<div id="tab_general_min_order_text_<?php echo $language['language_id']; ?>" class="input-group">
																	<label class="input-group-addon" for="general_min_order_text_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>
																	<input type="text" name="<?php echo $id; ?>_setting[general][min_order][text][<?php echo $language['language_id']; ?>]" id="general_min_order_text_<?php echo $language['language_id']; ?>" value="<?php echo isset($setting['general']['min_order']['text'][$language['language_id']]) ? $setting['general']['min_order']['text'][$language['language_id']] : $text_value_min_order; ?>" class="form-control" >
																</div>
																<?php } ?>
															</div>
														</div>

														<div class="form-group">
															<label class="col-sm-4 control-label" for="input-catalog-limit">
																<span data-toggle="tooltip" title="<?php echo $help_general_min_quantity; ?>">
																	<?php echo $entry_general_min_quantity; ?>
																</span>
															</label>
															<div class="col-sm-8">
																<div class="input-group">
																	<label for="general_min_quantity_value" id="label_general_min_quantity_value" class="input-group-addon">%s</label>
																	<?php if(isset($setting['general']['min_quantity']['value']) && $setting['general']['min_quantity']['value'] != ""){ ?>
																	<input type="text" value="<?php echo $setting['general']['min_quantity']['value']; ?>" name="<?php echo $id; ?>_setting[general][min_quantity][value]" id="general_min_quantity_value" class="form-control"/>
																	<?php }else{ ?>
																	<input type="text" value="0" name="<?php echo $id; ?>_setting[general][min_quantity][value]" class="form-control" id="general_min_quantity_value"/>
																	<?php } ?>
																</div>

																<?php foreach ($languages as $language) { ?>
																<div id="tab_general_min_quantity_text_<?php echo $language['language_id']; ?>" class="input-group">
																	<label class="input-group-addon" for="general_min_quantity_text_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>
																	<input type="text" name="<?php echo $id; ?>_setting[general][min_quantity][text][<?php echo $language['language_id']; ?>]" id="general_min_quantity_text_<?php echo $language['language_id']; ?>" value="<?php echo isset($setting['general']['min_quantity']['text'][$language['language_id']]) ? $setting['general']['min_quantity']['text'][$language['language_id']] : $text_value_min_quantity; ?>" class="form-control" >
																</div>
																<?php } ?>
															</div>
														</div>

														

														<div class="form-group">
															<label class="col-sm-4 control-label" for="button_delete_setting"><?php echo $entry_delete_setting; ?></label>
															<div class="col-sm-4">
																<a onclick="confirm('<?php echo $text_confirm_delete_setting; ?>') ? location.href='<?php echo $delete_setting; ?>' : false;" id="button_delete_setting" class="btn btn-primary btn-block"><i class="fa fa-trash-o"></i> <?php echo $button_delete_setting; ?></a>

															</div>
															
														</div><!-- //delete_setting -->

													</div>
												</div>
												<hr/>	
												<div class="form-group">
													<label class="col-sm-2 control-label" for="button_delete_setting">
														<span data-toggle="tooltip" title="<?php echo $help_action_bulk_setting; ?>">
																	<?php echo $entry_action_bulk_setting; ?>
																</span>
													</label>
													<div class="col-sm-2">
														<button class="btn btn-primary btn-block" id="generate_setting"><i class="fa fa-cog"></i> <?php echo $button_create_bulk_setting; ?></button>
													</div>
													<div class="col-sm-2">
														<button class="btn btn-primary btn-block" id="save_bulk_setting"><i class="fa fa-floppy-o"></i> <?php echo $button_save_bulk_setting; ?></button>
													</div>
													<div class="col-sm-6" id="notification_setting">
													</div>
												</div>
												
												<div class="form-group">
													<label class="col-sm-2 control-label" for="button_delete_setting">
														<span data-toggle="tooltip" title="<?php echo $help_bulk_setting; ?>">
															<?php echo $entry_bulk_setting; ?>
														</span>
													</label>
													<div class="col-sm-10">
														<textarea id="bulk_setting" class="form-control"></textarea>
													</div>
												</div>
											</div><!-- /#general-->

									        <!---------------------------------- login ---------------------------------->
									        <div id="d_login" class="tab-pane">
									          
									         	<h3 class="page-header">
									         		<span class="fa fa-key fa-fw"></span> <span><?php echo $text_login; ?></span>
									         	</h3>

									         	<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_general_default_option; ?>">
															<?php echo $entry_general_default_option; ?>
														</span>
													</label>
													<div class="col-sm-9">
														<div class="btn-group" data-toggle="buttons">
															<label class="btn btn-success <?php if(isset($setting['step']['login']['default_option']) && $setting['step']['login']['default_option'] == 'guest'){ ?> active <?php } ?>">
																<input type="radio" value="guest" name="<?php echo $id; ?>_setting[step][login][default_option]" <?php if(isset($setting['step']['login']['default_option']) && $setting['step']['login']['default_option'] == 'guest'){ ?> checked="checked" <?php } ?> id="general_default_option_guest" /> <?php echo $text_guest; ?>
															</label>
															<label class="btn btn-success <?php if(isset($setting['step']['login']['default_option']) && $setting['step']['login']['default_option'] == 'register'){ ?> active <?php } ?>">
																<input type="radio" value="register" name="<?php echo $id; ?>_setting[step][login][default_option]" <?php if(isset($setting['step']['login']['default_option']) && $setting['step']['login']['default_option'] == 'register'){ ?> checked="checked" <?php } ?> id="general_default_option_register" /> <?php echo $text_register; ?>
															</label>
														</div>

													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_design_login_option; ?>"><?php echo $entry_design_login_option; ?></span>
													</label>
													<div class="col-sm-9">
														<input type="hidden" value="0" name="<?php echo $id; ?>_setting[step][login][option][login][display]" />
														<input type="checkbox" value="1" data-label-text="<?php echo $text_login; ?>"  name="<?php echo $id; ?>_setting[step][login][option][login][display]" <?php if(isset($setting['step']['login']['option']['login']['display']) && $setting['step']['login']['option']['login']['display'] == 1){ ?>checked="checked"<?php } ?> id="step_login_option_login_display"/>

														<input type="hidden" value="0" name="<?php echo $id; ?>_setting[step][login][option][register][display]" />
														<input type="checkbox" value="1" data-label-text="<?php echo $text_register; ?>"  name="<?php echo $id; ?>_setting[step][login][option][register][display]" <?php if(isset($setting['step']['login']['option']['register']['display']) && $setting['step']['login']['option']['register']['display'] == 1){ ?>checked="checked"<?php } ?> id="step_login_option_register_display"/>

														<input type="hidden" value="0" name="<?php echo $id; ?>_setting[step][login][option][guest][display]" />
														<input type="checkbox" value="1" data-label-text="<?php echo $text_guest; ?>"  name="<?php echo $id; ?>_setting[step][login][option][guest][display]" <?php if(isset($setting['step']['login']['option']['guest']['display']) && $setting['step']['login']['option']['guest']['display'] == 1){ ?>checked="checked"<?php } ?> id="step_login_option_guest_display"/>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_design_login; ?>"><?php echo $entry_design_login; ?></span>
													</label>
													<div class="col-sm-9">
														<div class="btn-group" data-toggle="buttons">
															<label class="btn btn-success <?php if(isset($setting['design']['login_style']) && $setting['design']['login_style'] == 'block'){ ?>active<?php } ?>">
																<input type="radio" value="block" name="<?php echo $id; ?>_setting[design][login_style]" <?php if(isset($setting['design']['login_style']) && $setting['design']['login_style'] == 'block'){ ?>checked="checked"<?php } ?> id="login_style_block" /> <?php echo $text_block; ?>
															</label>
															<label class="btn btn-success <?php if(isset($setting['design']['login_style']) && $setting['design']['login_style'] == 'popup'){ ?>active<?php } ?>">
																<input type="radio" value="popup" name="<?php echo $id; ?>_setting[design][login_style]" <?php if(isset($setting['design']['login_style']) && $setting['design']['login_style'] == 'popup'){ ?>checked="checked"<?php } ?> id="login_style_popup"/> <?php echo $text_popup; ?>
															</label>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_icon; ?>">
															<?php echo $text_icon; ?>
														</span>
													</label>
													<div class="col-sm-3">
														<input type="text" name="<?php echo $id; ?>_setting[step][login][icon]" id="login_icon; ?>" value="<?php echo $setting['step']['login']['icon']; ?>" class="form-control" placeholder="<?php echo $text_icon; ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_title; ?>">
															<?php echo $text_title; ?>
														</span>
													</label>
													<div class="col-sm-3">
														<?php foreach ($languages as $language) { ?>
														<div id="tab_general_min_order_text_<?php echo $language['language_id']; ?>" class="input-group">
															<label class="input-group-addon" for="login_option_guest_title_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>
															<input type="text" name="<?php echo $id; ?>_setting[step][login][option][guest][title][<?php echo $language['language_id']; ?>]" id="login_option_guest_title_<?php echo $language['language_id']; ?>" value="<?php echo (isset($setting['step']['login']['option']['guest']['title'][$language['language_id']]) && is_array($setting['step']['login']['option']['guest']['title'])) ? $setting['step']['login']['option']['guest']['title'][$language['language_id']] : $setting['step']['login']['option']['guest']['title']; ?>" class="form-control" placeholder="<?php echo $text_guest; ?>">
														</div>
														<?php } ?>
													</div>
													<div class="col-sm-3">
														<?php foreach ($languages as $language) { ?>
														<div id="tab_general_min_order_text_<?php echo $language['language_id']; ?>" class="input-group">
															<label class="input-group-addon" for="login_option_register_title_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>
															<input type="text" name="<?php echo $id; ?>_setting[step][login][option][register][title][<?php echo $language['language_id']; ?>]" id="login_option_register_title_<?php echo $language['language_id']; ?>" value="<?php echo (isset($setting['step']['login']['option']['register']['title'][$language['language_id']]) && is_array($setting['step']['login']['option']['register']['title'])) ? $setting['step']['login']['option']['register']['title'][$language['language_id']] : $setting['step']['login']['option']['register']['title']; ?>" class="form-control"  placeholder="<?php echo $text_register; ?>">
														</div>
														<?php } ?>
													</div>
													<div class="col-sm-3">
														<?php foreach ($languages as $language) { ?>
														<div id="tab_general_min_order_text_<?php echo $language['language_id']; ?>" class="input-group">
															<label class="input-group-addon" for="login_option_login_title_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>
															<input type="text" name="<?php echo $id; ?>_setting[step][login][option][login][title][<?php echo $language['language_id']; ?>]" id="login_option_login_title_<?php echo $language['language_id']; ?>" value="<?php echo (isset($setting['step']['login']['option']['login']['title'][$language['language_id']]) && is_array($setting['step']['login']['option']['login']['title'])) ? $setting['step']['login']['option']['login']['title'][$language['language_id']] : $setting['step']['login']['option']['login']['title']; ?>" class="form-control"  placeholder="<?php echo $text_login; ?>">
														</div>
														<?php } ?>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_description; ?>">
															<?php echo $text_description; ?>
														</span>
													</label>
													<div class="col-sm-9">
														
														<?php foreach ($languages as $language) { ?>
														<div id="tab_general_min_order_text_<?php echo $language['language_id']; ?>" class="input-group">
															<label class="input-group-addon" for="login_description_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>
															<input type="text" name="<?php echo $id; ?>_setting[step][login][description][<?php echo $language['language_id']; ?>]" id="login_description_<?php echo $language['language_id']; ?>" value="<?php echo (isset($setting['step']['login']['description'][$language['language_id']]) && is_array($setting['step']['login']['description'])) ? $setting['step']['login']['description'][$language['language_id']] : $setting['step']['login']['description']; ?>" class="form-control" >
														</div>
														<?php } ?>
													</div>
												</div>
									         	<?php if($social_login) { ?>

													<?php /*<div id="sortable_social_login" class="list-group atab col-md-5">
														<?php foreach($setting['general']['social_login']['providers'] as $provider_name => $provider){ ?>
														<?php if(isset($provider['id'])) { ?> 
														<div class="clearfix sort-item atab-item list-group-item">
															<div class="col-sm-5"><span><span class="<?php echo $provider['icon']; ?>"> </span><?php echo ${'text_'.$provider['id']};?></span></div>
															<div class="col-sm-7"><span>
																<input type="hidden" class="sort-value" value="<?php echo $provider['sort_order']; ?>" name="<?php echo $id; ?>_setting[general][social_login][providers][<?php echo $provider_name; ?>][sort_order]">
																<input type="hidden" value="0" name="<?php echo $id; ?>_setting[general][social_login][providers][<?php echo $provider_name; ?>][enabled]">
																<input type="checkbox" value="1" id="general_social_login_providers_<?php echo $provider['id']; ?>" <?php echo ($provider['enabled']) ? 'checked="checked"': ''; ?> name="<?php echo $id; ?>_setting[general][social_login][providers][<?php echo $provider_name; ?>][enabled]"> <label for="general_social_login_providers_<?php echo $provider['id']; ?>"><?php echo $text_enable; ?><label></span> 
																<span class="fa fa-drag"></span>
															</div>
														</div>
														<?php } ?>
														<?php } ?>
													</div>

													<div class="col-md-7">

														<div class="form-group">
															<label class="col-sm-6 control-label">
																<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_socila_login_style; ?>"><?php echo $entry_socila_login_style; ?></span>
															</label>
															<div class="col-sm-6">
																<select name="<?php echo $id; ?>_setting[general][socila_login_style]" class="form-control">
																	<?php foreach ($socila_login_styles as $style) {?>
																	<?php if($setting['general']['socila_login_style'] == $style){ ?>
																	<option value="<?php echo $style; ?>" selected="selected"><?php echo $style; ?></option>
																	<?php }else{ ?>
																	<option value="<?php echo $style; ?>"><?php echo $style; ?></option>
																	<?php } ?>
																	<?php } ?>
																</select>
															</div>
														</div> */ ?>
													<div class="form-group">
														<label class="col-sm-3 control-label" for="input-catalog-limit">
															<span data-toggle="tooltip" title="<?php echo $help_social_login; ?>">
																<?php echo $entry_social_login; ?>
															</span>
														</label>
														<div class="col-sm-9">
															<input type="hidden" value="0" name="<?php echo $id; ?>_setting[general][social_login]" />
															<input type="checkbox" value="1" name="<?php echo $id; ?>_setting[general][social_login]" <?php if(isset($setting['general']['social_login']) && $setting['general']['social_login'] == 1){ ?>checked="checked"<?php } ?> id="social_login" />
														</div>
													</div>

													<div class="form-group">
														<div class="col-sm-offset-3 col-sm-9">
															<a href="<?php echo $link_social_login_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i> <?php echo $button_social_login_edit?></a>
														</div>
													</div> 

													

													

									        	<?php }else{ ?>
													<div class="bs-callout bs-callout-warning"><?php echo $text_social_login_required; ?></div>
									        	<?php } ?>

												<script>
												// $(function() {
												//   $( "#sortable_social_login" ).sortable({
												//    axis: "y",
												//    placeholder: "ui-state-highlight",
												//    distance: 5,
												//    stop: function( event, ui ) {
												//     ui.item.children( ".sort-item" ).triggerHandler( "focusout" );
												//   $(this).find(".sort-item").each(function(i, el){
												//    $(this).find(".sort-value").val($(el).index())
												//   });
												//    }
												//   });
												// });
												</script>
											</div><!-- /#login-->

											<!---------------------------------- payment_address ---------------------------------->
											<div id="d_payment_address" class="tab-pane">

												<h3 class="page-header">
													<span class="fa fa-book fa-fw"></span> <span><?php echo $text_payment_address; ?></span>
												</h3>

												<div class="table-responsive">
													<table class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th></th>
																<th><?php echo $text_defualt; ?></th>
																<th class="guest"><?php echo $text_guest; ?></th>
																<th class="register"><?php echo $text_register; ?></th>
																<th class="login"><?php echo $text_logged; ?></th>
															</tr>
														</thead>
														<tbody>
															<tr id="payment_address_display_input" sort-data="-1">
																<td class="name">
																	<label  class="control-label">
																		<span data-toggle="tooltip" title="<?php echo $help_payment_address_display; ?>">
																			<?php echo $entry_payment_address_display; ?>
																		</span>
																	</label>
																</td>
																<td>
																</td>
																<?php foreach($options as $option) {?>
																<td > 
																	
																	<input type="hidden" value="0" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][payment_address][display]" />
																	<input type="checkbox" 
																		value="1" 
																		data-size="mini"  
																		data-label-text="<?php echo $text_display; ?>" 
																		name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][payment_address][display]" 
																		id="<?php echo $option; ?>_payment_address_fields_display_display" 
																		<?php if(isset($setting['account'][$option]['payment_address']['display']) && $setting['account'][$option]['payment_address']['display'] == 1){ ?>checked="checked"<?php } ?>
																	/>
																
																</td>
																<?php } ?>

															</tr> 
														</tbody>

														<tbody class="sortable table-sortable "> 
															<?php foreach($setting['step']['payment_address']['fields'] as $field){ if(isset($field['title'])){?>
															<tr id="payment_address_<?php echo $field['id']; ?>_input" 
																class="sort-item <?php echo ($field['type'] == 'system')? 'hide' : ''; ?>" 
																sort-data="<?php echo (isset($setting['step']['payment_address']['fields'][$field['id']]['sort_order']) ? $setting['step']['payment_address']['fields'][$field['id']]['sort_order'] : ''); ?>">

																<td class="name">
																	
																	<label class="">
																		<span class="btn btn-link">
																		<i class="fa fa-bars"></i>
																	</span>
																		<span data-toggle="tooltip" title="<?php echo $text_type; ?> <?php echo $setting['step']['payment_address']['fields'][$field['id']]['type']; ?>">
																			<?php echo $field['title']; ?> <i class="fa fa-question-circle"></i>
																		</span>
																		<input class="sort" 
																		type="hidden" 
																		value="<?php echo (isset($setting['step']['payment_address']['fields'][$field['id']]['sort_order'])) ? $setting['step']['payment_address']['fields'][$field['id']]['sort_order'] : ''; ?>" 
																		name="<?php echo $id; ?>_setting[step][payment_address][fields][<?php echo $field['id']; ?>][sort_order]" />
																	</label>

																	
																</td>
																<td>
																	<?php 
																	 if($setting['step']['payment_address']['fields'][$field['id']]['type'] == 'checkbox'){ ?>
																		<input type="hidden" name="<?php echo $id; ?>_setting[step][payment_address][fields][<?php echo $field['id']; ?>][value]" value="0" />
																		<input class="form-control pull-right" type="<?php echo $setting['step']['payment_address']['fields'][$field['id']]['type'] ?>" name="<?php echo $id; ?>_setting[step][payment_address][fields][<?php echo $field['id']; ?>][value]" <?php echo (isset($setting['step']['payment_address']['fields'][$field['id']]['value']) && $setting['step']['payment_address']['fields'][$field['id']]['value']) ?  'checked="checked"' : ''; ?> value="1" />
																	<?php } ?>
																	<?php 
																	 if($setting['step']['payment_address']['fields'][$field['id']]['type'] == 'select'){ ?>
																	 	<?php if($setting['step']['payment_address']['fields'][$field['id']]['custom']) { ?>
																			<a class="btn btn-primary btn-block" href="<?php echo $custom_field; ?>"><i class="fa fa-pencil"></i> <?php echo $text_custom_field; ?></a>
																		<?php }else{ ?> 
																			<select class="form-control" name="<?php echo $id; ?>_setting[step][payment_address][fields][<?php echo $field['id']; ?>][value]">
																				<option value=""><?php echo $text_select; ?></option>
																				<?php foreach($setting['step']['payment_address']['fields'][$field['id']]['options'] as $option_item){ ?>
																					<option value="<?php echo $option_item['value']?>"  <?php echo (isset($setting['step']['payment_address']['fields'][$field['id']]['value']) && $setting['step']['payment_address']['fields'][$field['id']]['value'] == $option_item['value']) ?  'selected="selected"' : ''; ?> ><?php echo $option_item['name']?></option>
																				<?php } ?>
																			</select>
																		<?php } ?>
																	<?php } ?>
																	<?php if($setting['step']['payment_address']['fields'][$field['id']]['type'] == 'text' 
																		  || $setting['step']['payment_address']['fields'][$field['id']]['type'] == 'email'
																		  || $setting['step']['payment_address']['fields'][$field['id']]['type'] == 'date'
																		  || $setting['step']['payment_address']['fields'][$field['id']]['type'] == 'time'
																		  || $setting['step']['payment_address']['fields'][$field['id']]['type'] == 'datetime'){ ?>
																		<?php if($setting['step']['payment_address']['fields'][$field['id']]['custom']) { ?>
																			<a class="btn btn-primary btn-block" href="<?php echo $custom_field; ?>"><i class="fa fa-pencil"></i> <?php echo $text_custom_field; ?></a>
																		<?php }else{ ?> 
																			<input class="form-control pull-right" 
																				placeholder="<?php echo $setting['step']['payment_address']['fields'][$field['id']]['type'] ?>" 
																				type="<?php echo $setting['step']['payment_address']['fields'][$field['id']]['type'] ?>" 
																				<?php if($setting['step']['payment_address']['fields'][$field['id']]['type'] == "date"){ ?>data-date-format="YYYY-MM-DD" <?php } ?>
																	            <?php if($setting['step']['payment_address']['fields'][$field['id']]['type'] == "time"){ ?>data-date-format="HH:mm" <?php } ?>
																	            <?php if($setting['step']['payment_address']['fields'][$field['id']]['type'] == "datetime"){ ?>data-date-format="YYYY-MM-DD HH:mm" <?php } ?>
																				name="<?php echo $id; ?>_setting[step][payment_address][fields][<?php echo $field['id']; ?>][value]" 
																				value="<?php echo (isset($setting['step']['payment_address']['fields'][$field['id']]['value'])) ? $setting['step']['payment_address']['fields'][$field['id']]['value'] : ''; ?>" />
																		<?php } ?>
																		<input class="form-control pull-right" 
																			data-toggle="tooltip" 
																			title="<?php echo $help_maskedinput; ?>" 
																			placeholder="mask" 
																			type="text" 
																			name="<?php echo $id; ?>_setting[step][payment_address][fields][<?php echo $field['id']; ?>][mask]" 
																			value="<?php echo (isset($setting['step']['payment_address']['fields'][$field['id']]['mask'])) ? $setting['step']['payment_address']['fields'][$field['id']]['mask'] : ''; ?>" />
																	<?php } ?>
																</td>
																<?php foreach($options as $option) {?>
																<td >
																	<?php if(isset($setting['account'][$option]['payment_address']['fields'][$field['id']]['display'])) { ?>

																		<input type="hidden" value="0" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][payment_address][fields][<?php echo $field['id']; ?>][display]" />
																		<input type="checkbox" value="1" data-size="mini" data-label-text="<?php echo $text_display; ?>" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][payment_address][fields][<?php echo $field['id']; ?>][display]" <?php if(isset($setting['account'][$option]['payment_address']['fields'][$field['id']]['display']) && $setting['account'][$option]['payment_address']['fields'][$field['id']]['display'] == 1){ ?>checked="checked"<?php } ?> id="<?php echo $option; ?>_payment_address_fields_<?php echo $field['id']; ?>_display"/>
																		<?php if(isset($setting['account']['guest']['payment_address']['fields'][$field['id']]['require'])) { ?>
																			<input type="hidden" value="0" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][payment_address][fields][<?php echo $field['id']; ?>][require]" />
																			<input type="checkbox" value="1" data-size="mini" data-label-text="<?php echo $text_require; ?>" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][payment_address][fields][<?php echo $field['id']; ?>][require]" <?php if($setting['account']['guest']['payment_address']['fields'][$field['id']]['require'] == 1){ ?>checked="checked"<?php } ?> id="<?php echo $option; ?>_payment_address_fields_<?php echo $field['id']; ?>_require"/>
																		<?php } //require?>

																	<?php } //display ?>
																</td>
																<?php } ?>
															</tr>
															<?php } } /*foreach fields*/?>
														</tbody>
													</table>
												</div> <!-- /.table-responsive-->
												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_new_field; ?>"><?php echo $entry_new_field; ?></span>
													</label>
													<div class="col-sm-9">
														<a href="<?php echo $add_field; ?>" class="btn btn-primary">
															<i class="fa fa-plus-square"></i> <?php echo $button_new_field; ?>
														</a>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_icon; ?>">
															<?php echo $text_icon; ?>
														</span>
													</label>
													<div class="col-sm-3">
														<input type="text" name="<?php echo $id; ?>_setting[step][payment_address][icon]" id="login_icon; ?>" value="<?php echo $setting['step']['payment_address']['icon']; ?>" class="form-control" placeholder="<?php echo $text_icon; ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_title; ?>">
															<?php echo $text_title; ?>
														</span>
													</label>
													<div class="col-sm-9">
														
														<?php foreach ($languages as $language) { ?>
														<div id="tab_general_min_order_text_<?php echo $language['language_id']; ?>" class="input-group">
															<label class="input-group-addon" for="payment_address_title_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>
															<input type="text" name="<?php echo $id; ?>_setting[step][payment_address][title][<?php echo $language['language_id']; ?>]" 
															id="payment_address_title_<?php echo $language['language_id']; ?>" 
															value="<?php echo (isset($setting['step']['payment_address']['title'][$language['language_id']]) && is_array($setting['step']['payment_address']['title'])) ? $setting['step']['payment_address']['title'][$language['language_id']] : $setting['step']['payment_address']['title']; ?>" class="form-control" >
														</div>
														<?php } ?>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_description; ?>">
															<?php echo $text_description; ?>
														</span>
													</label>
													<div class="col-sm-9">
														
														<?php foreach ($languages as $language) { ?>
														<div id="tab_general_min_order_text_<?php echo $language['language_id']; ?>" class="input-group">
															<label class="input-group-addon" for="payment_address_description_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>
															<input type="text" name="<?php echo $id; ?>_setting[step][payment_address][description][<?php echo $language['language_id']; ?>]" id="payment_address_description_<?php echo $language['language_id']; ?>" value="<?php echo (isset($setting['step']['payment_address']['description'][$language['language_id']]) && is_array($setting['step']['payment_address']['description'])) ? $setting['step']['payment_address']['description'][$language['language_id']] : $setting['step']['payment_address']['description']; ?>" class="form-control" >
														</div>
														<?php } ?>
													</div>
												</div>

												
											</div><!-- /#payment_address-->

											<!---------------------------------- shipping_address ---------------------------------->
											<div id="d_shipping_address" class="tab-pane">

												<h3 class="page-header">
													<span class="fa fa-book fa-fw"></span> <span><?php echo $text_shipping_address; ?></span>
												</h3>
												<div class="table-responsive">
													<table class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th></th>
																<th><?php echo $text_defualt; ?></th>
																<th class="guest"><?php echo $text_guest; ?></th>
																<th class="register"><?php echo $text_register; ?></th>
																<th class="login"><?php echo $text_logged; ?></th>
															</tr>
														</thead>
														<tbody>
															<tr id="shipping_address_display_input" >
																<td class="name">
																	<label class="control-label">
																		<span data-toggle="tooltip" title="<?php echo $help_shipping_address_display; ?>">
																			<?php echo $entry_shipping_address_display; ?>
																		</span>
																	</label>
																</td>
																<td>
																</td>
																<?php foreach($options as $option) {?>
																<td >
																	<input type="hidden" value="0" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][shipping_address][display]" />
																	<input type="checkbox" value="1" data-size="mini" data-label-text="<?php echo $text_display; ?>" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][shipping_address][display]" <?php if(isset($setting['account'][$option]['shipping_address']['display']) && $setting['account'][$option]['shipping_address']['display'] == 1){ ?> checked="checked" <?php } ?> id="<?php echo $option; ?>_shipping_address_display"/>

																	<input type="hidden" value="0" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][shipping_address][require]" />
																	<input type="checkbox" value="1" data-size="mini" data-label-text="<?php echo $text_require; ?>" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][shipping_address][require]" <?php if(isset($setting['account'][$option]['shipping_address']['require']) && $setting['account'][$option]['shipping_address']['require'] == 1){ ?>checked="checked"<?php } ?> id="<?php echo $option; ?>_shipping_address_require"/>

																</td>
																<?php } ?>
															</tr>
														</tbody>

														<tbody class="sortable table-sortable">  
															<?php foreach($setting['step']['shipping_address']['fields'] as $field){ if(isset($field['title'])){?>
															<tr id="shipping_address_<?php echo $field['id']; ?>_input" class="sort-item <?php echo ($field['type'] == 'system')? 'hide' : ''; ?>" sort-data="<?php echo (isset($setting['step']['shipping_address']['fields'][$field['id']]['sort_order']) ? $setting['step']['shipping_address']['fields'][$field['id']]['sort_order'] : ''); ?>">
																<td class="name">
																	
																	<label class="">
																		<span class="btn btn-link">
																		<i class="fa fa-bars"></i>
																	</span>
																		<span data-toggle="tooltip" title="<?php echo $text_type; ?> <?php echo $setting['step']['shipping_address']['fields'][$field['id']]['type']; ?>">
																			<?php echo $field['title']; ?> <i class="fa fa-question-circle"></i>
																		</span>
																		<input class="sort" 
																		type="hidden" 
																		value="<?php echo (isset($setting['step']['shipping_address']['fields'][$field['id']]['sort_order'])) ? $setting['step']['shipping_address']['fields'][$field['id']]['sort_order'] : ''; ?>" 
																		name="<?php echo $id; ?>_setting[step][shipping_address][fields][<?php echo $field['id']; ?>][sort_order]" />
																	</label>

																	
																</td>
																<td>
																	<?php 
																	 if($setting['step']['shipping_address']['fields'][$field['id']]['type'] == 'checkbox'){ ?>
																		<input type="hidden" name="<?php echo $id; ?>_setting[step][shipping_address][fields][<?php echo $field['id']; ?>][value]" value="0" />
																		<input class="form-control pull-right" type="<?php echo $setting['step']['shipping_address']['fields'][$field['id']]['type'] ?>" name="<?php echo $id; ?>_setting[step][shipping_address][fields][<?php echo $field['id']; ?>][value]" <?php echo (isset($setting['step']['shipping_address']['fields'][$field['id']]['value']) && $setting['step']['shipping_address']['fields'][$field['id']]['value']) ?  'checked="checked"' : ''; ?> value="1" />
																	<?php } ?>
																	<?php 
																	 if($setting['step']['shipping_address']['fields'][$field['id']]['type'] == 'select'){ ?>
																		<?php if($setting['step']['shipping_address']['fields'][$field['id']]['custom']) { ?>
																			<a class="btn btn-primary btn-block" href="<?php echo $custom_field; ?>"><i class="fa fa-pencil"></i> <?php echo $text_custom_field; ?></a>
																		<?php }else{ ?>
																			<select class="form-control" name="<?php echo $id; ?>_setting[step][shipping_address][fields][<?php echo $field['id']; ?>][value]">
																				<option value=""><?php echo $text_select; ?></option>
																				<?php foreach($setting['step']['shipping_address']['fields'][$field['id']]['options'] as $option_item){ ?>
																					<option value="<?php echo $option_item['value']?>"  <?php echo (isset($setting['step']['shipping_address']['fields'][$field['id']]['value']) && $setting['step']['shipping_address']['fields'][$field['id']]['value'] == $option_item['value']) ?  'selected="selected"' : ''; ?> ><?php echo $option_item['name']?></option>
																				<?php } ?>
																			</select>
																		<?php } ?>
																	<?php } ?>
																	<?php if($setting['step']['shipping_address']['fields'][$field['id']]['type'] == 'text' 
																		  || $setting['step']['shipping_address']['fields'][$field['id']]['type'] == 'email'
																		  || $setting['step']['shipping_address']['fields'][$field['id']]['type'] == 'date'
																		  || $setting['step']['shipping_address']['fields'][$field['id']]['type'] == 'time'
																		  || $setting['step']['shipping_address']['fields'][$field['id']]['type'] == 'datetime'){ ?>
																		<?php if($setting['step']['shipping_address']['fields'][$field['id']]['custom']) { ?>
																			<a class="btn btn-primary btn-block" href="<?php echo $custom_field; ?>"><i class="fa fa-pencil"></i> <?php echo $text_custom_field; ?></a>
																		<?php }else{ ?>
																			<input class="form-control pull-right" 
																				placeholder="<?php echo $setting['step']['shipping_address']['fields'][$field['id']]['type'] ?>" 
																				type="<?php echo $setting['step']['shipping_address']['fields'][$field['id']]['type'] ?>" 
																				name="<?php echo $id; ?>_setting[step][shipping_address][fields][<?php echo $field['id']; ?>][value]" 
																				<?php if($setting['step']['shipping_address']['fields'][$field['id']]['type'] == "date"){ ?>data-date-format="YYYY-MM-DD" <?php } ?>
																	            <?php if($setting['step']['shipping_address']['fields'][$field['id']]['type'] == "time"){ ?>data-date-format="HH:mm" <?php } ?>
																	            <?php if($setting['step']['shipping_address']['fields'][$field['id']]['type'] == "datetime"){ ?>data-date-format="YYYY-MM-DD HH:mm" <?php } ?>
																				value="<?php echo (isset($setting['step']['shipping_address']['fields'][$field['id']]['value'])) ? $setting['step']['shipping_address']['fields'][$field['id']]['value'] : ''; ?>" />
																			<?php }?>
																		<input class="form-control pull-right" 
																			placeholder="mask" 
																			type="text" 
																			name="<?php echo $id; ?>_setting[step][shipping_address][fields][<?php echo $field['id']; ?>][mask]" 
																			value="<?php echo (isset($setting['step']['shipping_address']['fields'][$field['id']]['mask'])) ? $setting['step']['shipping_address']['fields'][$field['id']]['mask'] : ''; ?>" />
																	<?php } ?>
																</td>
																<?php foreach($options as $option) {?>
																<td>
																	<?php if(isset($setting['account'][$option]['shipping_address']['fields'][$field['id']]['display'])) { ?>
																	
																		<input type="hidden" value="0" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][shipping_address][fields][<?php echo $field['id']; ?>][display]" />
																		<input type="checkbox" value="1" data-size="mini" data-label-text="<?php echo $text_display; ?>" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][shipping_address][fields][<?php echo $field['id']; ?>][display]" <?php if(isset($setting['account'][$option]['shipping_address']['fields'][$field['id']]['display']) && $setting['account'][$option]['shipping_address']['fields'][$field['id']]['display'] == 1){ ?>checked="checked"<?php } ?> id="<?php echo $option; ?>_shipping_address_fields_<?php echo $field['id']; ?>_display"/>

																		<?php if(isset($setting['account'][$option]['shipping_address']['fields'][$field['id']]['require'])) { ?>
																		
																			<input type="hidden" value="0" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][shipping_address][fields][<?php echo $field['id']; ?>][require]" />
																			<input type="checkbox" value="1" data-size="mini" data-label-text="<?php echo $text_require; ?>" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][shipping_address][fields][<?php echo $field['id']; ?>][require]" <?php if($setting['account'][$option]['shipping_address']['fields'][$field['id']]['require'] == 1){ ?>checked="checked"<?php } ?> id="<?php echo $option; ?>_shipping_address_fields_<?php echo $field['id']; ?>_require"/>
																			
																		<?php } ?>
																	<?php } ?>
																</td>
																<?php } ?>
																
															</tr>
															<?php } } /*foreach fields*/ ?>
														</tbody>
													</table>
												</div><!-- /.table-responsive-->
												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_new_field; ?>"><?php echo $entry_new_field; ?></span>
													</label>
													<div class="col-sm-9">
														<a href="<?php echo $add_field; ?>" class="btn btn-primary">
															<i class="fa fa-plus-square"></i> <?php echo $button_new_field; ?>
														</a>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_icon; ?>">
															<?php echo $text_icon; ?>
														</span>
													</label>
													<div class="col-sm-3">
														<input type="text" name="<?php echo $id; ?>_setting[step][shipping_address][icon]" id="login_icon; ?>" value="<?php echo $setting['step']['shipping_address']['icon']; ?>" class="form-control" placeholder="<?php echo $text_icon; ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_title; ?>">
															<?php echo $text_title; ?>
														</span>
													</label>
													<div class="col-sm-9">
														
														<?php foreach ($languages as $language) { ?>
														<div id="tab_general_min_order_text_<?php echo $language['language_id']; ?>" class="input-group">
															<label class="input-group-addon" for="shipping_address_title_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>
															<input type="text" name="<?php echo $id; ?>_setting[step][shipping_address][title][<?php echo $language['language_id']; ?>]" id="shipping_address_title_<?php echo $language['language_id']; ?>" value="<?php echo (isset($setting['step']['shipping_address']['title'][$language['language_id']]) && is_array($setting['step']['shipping_address']['title'])) ? $setting['step']['shipping_address']['title'][$language['language_id']] : $setting['step']['shipping_address']['title']; ?>" class="form-control" >
														</div>
														<?php } ?>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_description; ?>">
															<?php echo $text_description; ?>
														</span>
													</label>
													<div class="col-sm-9">
														
														<?php foreach ($languages as $language) { ?>
														<div id="tab_general_min_order_text_<?php echo $language['language_id']; ?>" class="input-group">
															<label class="input-group-addon" for="shipping_address_description_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>
															<input type="text" name="<?php echo $id; ?>_setting[step][shipping_address][description][<?php echo $language['language_id']; ?>]" id="shipping_address_description_<?php echo $language['language_id']; ?>" value="<?php echo (isset($setting['step']['shipping_address']['description'][$language['language_id']]) && is_array($setting['step']['shipping_address']['description'])) ? $setting['step']['shipping_address']['description'][$language['language_id']] : $setting['step']['shipping_address']['description']; ?>" class="form-control" >
														</div>
														<?php } ?>
													</div>
												</div>
											</div><!-- /#shipping_address-->

											<!---------------------------------- shipping_method ---------------------------------->
											<div id="d_shipping_method" class="tab-pane">

												<h3 class="page-header">
													<span class="fa fa-truck fa-fw"></span> <span><?php echo $text_shipping_method; ?></span>
												</h3>

												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_shipping_method_display; ?>"><?php echo $entry_shipping_method_display; ?></span>
													</label>
													<div class="col-sm-9">
														<input type="hidden" value="0" name="<?php echo $id; ?>_setting[step][shipping_method][display]" />
														<input type="checkbox" value="1" name="<?php echo $id; ?>_setting[step][shipping_method][display]" <?php if(isset($setting['step']['shipping_method']['display']) && $setting['step']['shipping_method']['display'] == 1){ ?>checked="checked"<?php } ?> id="shipping_method_display" />
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_shipping_method_display_options; ?>"><?php echo $entry_shipping_method_display_options; ?></span>
													</label>
													<div class="col-sm-9">
														<input type="hidden" value="0" name="<?php echo $id; ?>_setting[step][shipping_method][display_options]" />
														<input type="checkbox" value="1" name="<?php echo $id; ?>_setting[step][shipping_method][display_options]" <?php if(isset($setting['step']['shipping_method']['display']) && $setting['step']['shipping_method']['display_options'] == 1){ ?>checked="checked"<?php } ?> id="shipping_method_display_options" />
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_shipping_method_display_title; ?>"><?php echo $entry_shipping_method_display_title; ?></span>
													</label>
													<div class="col-sm-9">
														<input type="hidden" value="0" name="<?php echo $id; ?>_setting[step][shipping_method][display_title]" />
														<input type="checkbox" value="1" name="<?php echo $id; ?>_setting[step][shipping_method][display_title]" <?php if(isset($setting['step']['shipping_method']['display']) && $setting['step']['shipping_method']['display_title'] == 1){ ?>checked="checked"<?php } ?> id="shipping_method_display_title" />
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_shipping_method_input_style; ?>"><?php echo $entry_shipping_method_input_style; ?></span>
													</label>
													<div class="col-sm-9">
														<div class="btn-group" data-toggle="buttons">
															<label class="btn btn-success <?php if(isset($setting['step']['shipping_method']['input_style']) && $setting['step']['shipping_method']['input_style'] == 'radio'){ ?> active <?php } ?>">
																<input type="radio" value="radio" name="<?php echo $id; ?>_setting[step][shipping_method][input_style]" <?php if(isset($setting['step']['shipping_method']['input_style']) && $setting['step']['shipping_method']['input_style'] == 'radio'){ ?>checked="checked"<?php } ?> id="shipping_method_input_style_radio" />
																<?php echo $text_input_radio; ?>
															</label>
															<label class="btn btn-success <?php if(isset($setting['step']['shipping_method']['input_style']) && $setting['step']['shipping_method']['input_style'] == 'select'){ ?> active <?php } ?>">
																<input type="radio" value="select" name="<?php echo $id; ?>_setting[step][shipping_method][input_style]" <?php if(isset($setting['step']['shipping_method']['input_style']) && $setting['step']['shipping_method']['input_style'] == 'select'){ ?>checked="checked"<?php } ?> id="shipping_method_input_style_select" />
																<?php echo $text_input_select; ?>
															</label>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_shipping_method_default_option; ?>"><?php echo $entry_shipping_method_default_option; ?></span>
													</label>
													<div class="col-sm-9">
														<select name="<?php echo $id; ?>_setting[step][shipping_method][default_option]" class="form-control">
															<?php foreach ($shipping_methods as $shipping_method) {?>
															<?php if(isset($setting['step']['shipping_method']['default_option']) && $setting['step']['shipping_method']['default_option'] == $shipping_method['code']){ ?>

															<option value="<?php echo $shipping_method['code']; ?>" selected="selected"><?php echo $shipping_method['title']; ?></option>

															<?php }else{ ?>

															<option value="<?php echo $shipping_method['code']; ?>"><?php echo $shipping_method['title']; ?></option>

															<?php } ?>
															<?php } ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_icon; ?>">
															<?php echo $text_icon; ?>
														</span>
													</label>
													<div class="col-sm-3">
														<input type="text" name="<?php echo $id; ?>_setting[step][shipping_method][icon]" id="login_icon; ?>" value="<?php echo $setting['step']['shipping_method']['icon']; ?>" class="form-control" placeholder="<?php echo $text_icon; ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_title; ?>">
															<?php echo $text_title; ?>
														</span>
													</label>
													<div class="col-sm-9">
														
														<?php foreach ($languages as $language) { ?>
														<div id="tab_general_min_order_text_<?php echo $language['language_id']; ?>" class="input-group">
															<label class="input-group-addon" for="shipping_method_title_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>
															<input type="text" name="<?php echo $id; ?>_setting[step][shipping_method][title][<?php echo $language['language_id']; ?>]" id="shipping_method_title_<?php echo $language['language_id']; ?>" value="<?php echo (isset($setting['step']['shipping_method']['title'][$language['language_id']]) && is_array($setting['step']['shipping_method']['title'])) ? $setting['step']['shipping_method']['title'][$language['language_id']] : $setting['step']['shipping_method']['title']; ?>" class="form-control" >
														</div>
														<?php } ?>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_description; ?>">
															<?php echo $text_description; ?>
														</span>
													</label>
													<div class="col-sm-9">
														
														<?php foreach ($languages as $language) { ?>
														<div id="tab_general_min_order_text_<?php echo $language['language_id']; ?>" class="input-group">
															<label class="input-group-addon" for="shipping_method_description_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>
															<input type="text" name="<?php echo $id; ?>_setting[step][shipping_method][description][<?php echo $language['language_id']; ?>]" id="shipping_method_description_<?php echo $language['language_id']; ?>" value="<?php echo (isset($setting['step']['shipping_method']['description'][$language['language_id']]) && is_array($setting['step']['shipping_method']['description'])) ? $setting['step']['shipping_method']['description'][$language['language_id']] : $setting['step']['shipping_method']['description']; ?>" class="form-control" >
														</div>
														<?php } ?>
													</div>
												</div>
											</div><!-- /#shipping_method-->
									   
											<!---------------------------------- payment_method ---------------------------------->
											<div id="d_payment_method" class="tab-pane">

												<h3 class="page-header">
													<span class="fa fa-credit-card fa-fw"></span> <span><?php echo $text_payment_method; ?></span>
												</h3>
												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_payment_method_display; ?>"><?php echo $entry_payment_method_display; ?></span>
													</label>
													<div class="col-sm-9">
														<input type="hidden" value="0" name="<?php echo $id; ?>_setting[step][payment_method][display]" />
														<input type="checkbox" value="1" name="<?php echo $id; ?>_setting[step][payment_method][display]" <?php if(isset($setting['step']['payment_method']['display']) && $setting['step']['payment_method']['display'] == 1){ ?>checked="checked"<?php } ?> id="payment_method_display" />
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_payment_method_display_options; ?>"><?php echo $entry_payment_method_display_options; ?></span>
													</label>
													<div class="col-sm-9">
														<input type="hidden" value="0" name="<?php echo $id; ?>_setting[step][payment_method][display_options]" />
														<input type="checkbox" value="1" name="<?php echo $id; ?>_setting[step][payment_method][display_options]" <?php if(isset($setting['step']['payment_method']['display']) && $setting['step']['payment_method']['display_options'] == 1){ ?>checked="checked"<?php } ?> id="payment_method_display_options" />
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_payment_method_display_images; ?>"><?php echo $entry_payment_method_display_images; ?></span>
													</label>
													<div class="col-sm-9">
														<input type="hidden" value="0" name="<?php echo $id; ?>_setting[step][payment_method][display_images]" />
														<input type="checkbox" value="1" name="<?php echo $id; ?>_setting[step][payment_method][display_images]" <?php if(isset($setting['step']['payment_method']['display']) && $setting['step']['payment_method']['display_images'] == 1){ ?>checked="checked"<?php } ?> id="payment_method_display_images" />
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_payment_method_input_style; ?>"><?php echo $entry_payment_method_input_style; ?></span>
													</label>
													<div class="col-sm-9">
														<div class="btn-group" data-toggle="buttons">
															<label class="btn btn-success <?php if(isset($setting['step']['payment_method']['input_style']) && $setting['step']['payment_method']['input_style'] == 'radio'){ ?>active <?php } ?>">
																<input type="radio" value="radio" name="<?php echo $id; ?>_setting[step][payment_method][input_style]" <?php if(isset($setting['step']['payment_method']['input_style']) && $setting['step']['payment_method']['input_style'] == 'radio'){ ?>checked="checked"<?php } ?> id="payment_method_input_style_radio" />
																<?php echo $text_input_radio; ?>
															</label>
															<label class="btn btn-success <?php if(isset($setting['step']['payment_method']['input_style']) && $setting['step']['payment_method']['input_style'] == 'select'){ ?>active <?php } ?>">
																<input type="radio" value="select" name="<?php echo $id; ?>_setting[step][payment_method][input_style]" <?php if(isset($setting['step']['payment_method']['input_style']) && $setting['step']['payment_method']['input_style'] == 'select'){ ?>checked="checked"<?php } ?> id="payment_method_input_style_select" />
																<?php echo $text_input_select; ?>
															</label>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_payment_method_default_option; ?>"><?php echo $entry_payment_method_default_option; ?></span>
													</label>
													<div class="col-sm-9">
														<select name="<?php echo $id; ?>_setting[step][payment_method][default_option]" class="form-control">
															<?php foreach ($payment_methods as $payment_method) {?>
															<?php if(isset($setting['step']['payment_method']['default_option']) && ($setting['step']['payment_method']['default_option'] == $payment_method['code'])){ ?>

															<option value="<?php echo $payment_method['code']; ?>" selected="selected"><?php echo $payment_method['title']; ?></option>

															<?php }else{ ?>

															<option value="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['title']; ?></option>

															<?php } ?>
															<?php } ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_icon; ?>">
															<?php echo $text_icon; ?>
														</span>
													</label>
													<div class="col-sm-3">
														<input type="text" name="<?php echo $id; ?>_setting[step][payment_method][icon]" id="login_icon; ?>" value="<?php echo $setting['step']['payment_method']['icon']; ?>" class="form-control" placeholder="<?php echo $text_icon; ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_title; ?>">
															<?php echo $text_title; ?>
														</span>
													</label>
													<div class="col-sm-9">
														
														<?php foreach ($languages as $language) { ?>
														<div id="tab_general_min_order_text_<?php echo $language['language_id']; ?>" class="input-group">
															<label class="input-group-addon" for="payment_method_title_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>
															<input type="text" name="<?php echo $id; ?>_setting[step][payment_method][title][<?php echo $language['language_id']; ?>]" id="payment_method_title_<?php echo $language['language_id']; ?>" value="<?php echo (isset($setting['step']['payment_method']['title'][$language['language_id']]) && is_array($setting['step']['payment_method']['title'])) ? $setting['step']['payment_method']['title'][$language['language_id']] : $setting['step']['payment_method']['title']; ?>" class="form-control" >
														</div>
														<?php } ?>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_description; ?>">
															<?php echo $text_description; ?>
														</span>
													</label>
													<div class="col-sm-9">
														
														<?php foreach ($languages as $language) { ?>
														<div id="tab_general_min_order_text_<?php echo $language['language_id']; ?>" class="input-group">
															<label class="input-group-addon" for="payment_method_description_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>
															<input type="text" name="<?php echo $id; ?>_setting[step][payment_method][description][<?php echo $language['language_id']; ?>]" id="payment_method_description_<?php echo $language['language_id']; ?>" value="<?php echo (isset($setting['step']['payment_method']['description'][$language['language_id']]) && is_array($setting['step']['payment_method']['description'])) ? $setting['step']['payment_method']['description'][$language['language_id']] : $setting['step']['payment_method']['description']; ?>" class="form-control" >
														</div>
														<?php } ?>
													</div>
												</div>

												<div class="bs-callout bs-callout-info"><?php echo $callout_payment_payment_popup; ?></div>
												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_payment_default_payment_popup; ?>"><?php echo $entry_payment_default_payment_popup; ?></span>
													</label>
													<div class="col-sm-9">
														<input type="hidden" value="0" name="<?php echo $id; ?>_setting[step][payment][default_payment_popup]" />
														<input type="checkbox" value="1" name="<?php echo $id; ?>_setting[step][payment][default_payment_popup]" <?php if(isset($setting['step']['payment']['default_payment_popup']) && $setting['step']['payment']['default_payment_popup'] == 1){ ?>checked="checked"<?php } ?> id="payment_default_payment_popup" />
													</div>
												</div>
												<div class="form-group">
												<?php $i = 0; foreach ($payment_methods as $payment_method) { ?>
													<?php if($i == 2) { $i = 0; ?> </div><div class="form-group"> <?php } ?>
													<label class="col-sm-3 control-label" >
														<?php echo $payment_method['title']; ?>
													</label>
													<div class="col-sm-3">
														<input type="hidden" value="0" name="<?php echo $id; ?>_setting[step][payment][payment_popups][<?php echo $payment_method['code']; ?>]" />
														<input type="checkbox" value="1" name="<?php echo $id; ?>_setting[step][payment][payment_popups][<?php echo $payment_method['code']; ?>]" <?php if(isset($setting['step']['payment']['payment_popups'][$payment_method['code']]) && $setting['step']['payment']['payment_popups'][$payment_method['code']] == 1){ ?>checked="checked"<?php } ?> id="payment_payment_popups_<?php echo $payment_method['code']; ?>" />
													</div>
													<?php $i ++; ?>
												<?php } ?>
												</div>
											</div><!-- /#payment_method-->

											<!---------------------------------- confirm ---------------------------------->
											<div id="d_confirm" class="tab-pane">

												<h3 class="page-header">
													<span class="fa fa-shopping-cart fa-fw"></span> <span><?php echo $text_cart; ?></span>
												</h3>

												<div class="table-responsive">
													<table class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th></th>
																<th class="guest"><?php echo $text_guest; ?></th>
																<th class="register"><?php echo $text_register; ?></th>
																<th class="login"><?php echo $text_logged; ?></th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td class="name">
																	<label class="control-label">
																		<span data-toggle="tooltip" title="<?php echo $help_cart_display; ?>">
																			<?php echo $entry_cart_display; ?>
																		</span>
																	</label>
																</td>
																<?php foreach($options as $option){?>
																<td>
																		<input type="hidden" value="0" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][cart][display]" />
																		<input type="checkbox" value="1" data-size="mini" data-label-text="<?php echo $text_display; ?>" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][cart][display]" <?php if(isset($setting['account'][$option]['cart']['display']) && $setting['account'][$option]['cart']['display'] == 1){ ?>checked="checked"<?php } ?> id="option_<?php echo $option; ?>_cart_display" />
																</td>
																<?php } ?>
															</tr>
															<?php $fields = array('image', 'name', 'model', 'quantity', 'price', 'total');?>
															<?php foreach($fields as $field){ ?>
															<tr>
																<td class="name">
																	<label>
																		<?php $field_name = 'entry_cart_columns_'.$field; echo $$field_name; ?>
																	</label>
																</td>
																<?php foreach($options as $option){?>
																<td>
																	<input type="hidden" value="0" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][cart][columns][<?php echo $field; ?>]" />
																	<input type="checkbox" value="1" data-size="mini" data-label-text="<?php echo $text_display; ?>" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][cart][columns][<?php echo $field; ?>]" <?php if(isset($setting['account'][$option]['cart']['columns'][$field]) && $setting['account'][$option]['cart']['columns'][$field] == 1){ ?>checked="checked"<?php } ?> id="option_<?php echo $option; ?>_cart_columns_<?php echo $field; ?>" />
																</td>
																<?php } ?>
															</tr>
															<?php } ?>

															<?php $fields = array('coupon', 'voucher', 'reward');?>
															<?php foreach($fields as $field){ ?>
															<tr>
																<td class="name">
																	<label  class="control-label">
																		<span data-toggle="tooltip" title="" data-original-title="<?php echo ${'help_cart_option_'.$field}; ?>">
																			<?php $field_name = 'entry_cart_option_'.$field; echo $$field_name; ?>
																		</span>
																	</label>
																</td>
																<?php foreach($options as $option){?>
																<td>
																	<input type="hidden" value="0" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][cart][option][<?php echo $field; ?>][display]" />
																	<input type="checkbox" value="1" data-size="mini" data-label-text="<?php echo $text_display; ?>" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][cart][option][<?php echo $field; ?>][display]" <?php if(isset($setting['account'][$option]['cart']['option'][$field]['display']) && $setting['account'][$option]['cart']['option'][$field]['display'] == 1){ ?>checked="checked"<?php } ?> id="option_<?php echo $option; ?>_cart_option_<?php echo $field; ?>_display" />
																</td>
																<?php } ?>
															</tr>  
															<?php } ?>
														</tbody>
													</table>
												</div><!-- /.table-responsive -->
												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_icon; ?>">
															<?php echo $text_icon; ?>
														</span>
													</label>
													<div class="col-sm-3">
														<input type="text" name="<?php echo $id; ?>_setting[step][cart][icon]" id="login_icon; ?>" value="<?php echo $setting['step']['cart']['icon']; ?>" class="form-control" placeholder="<?php echo $text_icon; ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_title; ?>">
															<?php echo $text_title; ?>
														</span>
													</label>
													<div class="col-sm-9">
														
														<?php foreach ($languages as $language) { ?>
														<div id="tab_general_min_order_text_<?php echo $language['language_id']; ?>" class="input-group">
															<label class="input-group-addon" for="cart_title_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>
															<input type="text" name="<?php echo $id; ?>_setting[step][cart][title][<?php echo $language['language_id']; ?>]" id="cart_title_<?php echo $language['language_id']; ?>" value="<?php echo (isset($setting['step']['cart']['title'][$language['language_id']]) && is_array($setting['step']['cart']['title'])) ? $setting['step']['cart']['title'][$language['language_id']] : $setting['step']['cart']['title']; ?>" class="form-control" >
														</div>
														<?php } ?>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_description; ?>">
															<?php echo $text_description; ?>
														</span>
													</label>
													<div class="col-sm-9">
														
														<?php foreach ($languages as $language) { ?>
														<div id="tab_general_min_order_text_<?php echo $language['language_id']; ?>" class="input-group">
															<label class="input-group-addon" for="cart_description_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>
															<input type="text" name="<?php echo $id; ?>_setting[step][cart][description][<?php echo $language['language_id']; ?>]" id="cart_description_<?php echo $language['language_id']; ?>" value="<?php echo (isset($setting['step']['cart']['description'][$language['language_id']]) && is_array($setting['step']['cart']['description'])) ? $setting['step']['cart']['description'][$language['language_id']] : $setting['step']['cart']['description']; ?>" class="form-control" >
														</div>
														<?php } ?>
													</div>
												</div>

												<h3 class="page-header">
													<span class="fa fa-check fa-fw"></span> <span><?php echo $text_confirm; ?></span>
												</h3>
												<div class="table-responsive">
													<table class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th></th>
																<th><?php echo $text_defualt; ?></th>
																<th class="guest"><?php echo $text_guest; ?></th>
																<th class="register"><?php echo $text_register; ?></th>
																<th class="login"><?php echo $text_logged; ?></th>
															</tr>
														</thead>
														<tbody class="sortable table-sortable">
															<?php foreach($setting['step']['confirm']['fields'] as $field){ if(isset($field['title'])){?>
															<tr id="confirm_<?php echo $field['id']; ?>_input" class="sort-item <?php echo ($field['type'] == 'system')? 'hide' : ''; ?>" sort-data="<?php echo (isset($setting['step']['confirm']['fields'][$field['id']]['sort_order']) ? $setting['step']['confirm']['fields'][$field['id']]['sort_order'] : ''); ?>">
																<td class="name">
																	
																	<label class="">
																		<span class="btn btn-link">
																		<i class="fa fa-bars"></i>
																	</span>
																		<span data-toggle="tooltip" title="<?php echo $text_type; ?> <?php echo $setting['step']['confirm']['fields'][$field['id']]['type']; ?>">
																			<?php echo $field['title']; ?> <i class="fa fa-question-circle"></i>
																		</span>
																		<input class="sort" 
																		type="hidden" 
																		value="<?php echo (isset($setting['step']['confirm']['fields'][$field['id']]['sort_order'])) ? $setting['step']['confirm']['fields'][$field['id']]['sort_order'] : ''; ?>" 
																		name="<?php echo $id; ?>_setting[step][confirm][fields][<?php echo $field['id']; ?>][sort_order]" />
																	</label>

																	
																</td>
																<td>
																	<?php 
																	 if($setting['step']['confirm']['fields'][$field['id']]['type'] == 'checkbox'){ ?>
																		<input type="hidden" name="<?php echo $id; ?>_setting[step][confirm][fields][<?php echo $field['id']; ?>][value]" value="0" />
																		<input class="form-control pull-right" type="<?php echo $setting['step']['confirm']['fields'][$field['id']]['type'] ?>" data-size="small" name="<?php echo $id; ?>_setting[step][confirm][fields][<?php echo $field['id']; ?>][value]" <?php echo (isset($setting['step']['confirm']['fields'][$field['id']]['value']) && $setting['step']['confirm']['fields'][$field['id']]['value']) ?  'checked="checked"' : ''; ?> value="1" />
																	<?php } ?>
																	<?php if($setting['step']['confirm']['fields'][$field['id']]['type'] == 'text' 
																		  || $setting['step']['confirm']['fields'][$field['id']]['type'] == 'email'){ ?>
																		<input class="form-control pull-right" placeholder="<?php echo $setting['step']['confirm']['fields'][$field['id']]['type'] ?>" type="<?php echo $setting['step']['confirm']['fields'][$field['id']]['type'] ?>" name="<?php echo $id; ?>_setting[step][confirm][fields][<?php echo $field['id']; ?>][value]" value="<?php echo (isset($setting['step']['confirm']['fields'][$field['id']]['value'])) ? $setting['step']['confirm']['fields'][$field['id']]['value'] : ''; ?>" />
																	<?php } ?>
																</td>
																<?php foreach($options as $option){?>
																<td>
																	<?php if(isset($setting['account'][$option]['confirm']['fields'][$field['id']]['display'])) { ?>
																		<input type="hidden" value="0" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][confirm][fields][<?php echo $field['id']; ?>][display]" />
																		<input type="checkbox" data-size="mini" data-label-text="<?php echo $text_display; ?>"  value="1" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][confirm][fields][<?php echo $field['id']; ?>][display]" <?php if(isset($setting['account'][$option]['confirm']['fields'][$field['id']]['display']) && $setting['account'][$option]['confirm']['fields'][$field['id']]['display'] == 1){ ?>checked="checked"<?php } ?> id="<?php echo $option ?>_confirm_fields_<?php echo $field['id']; ?>_display"/>

																		<?php if(isset($setting['account'][$option]['confirm']['fields'][$field['id']]['require'])) { ?>
																			<input type="hidden" value="0" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][confirm][fields][<?php echo $field['id']; ?>][require]" />
																			<input type="checkbox" data-size="mini" data-label-text="<?php echo $text_require; ?>"  value="1" name="<?php echo $id; ?>_setting[account][<?php echo $option; ?>][confirm][fields][<?php echo $field['id']; ?>][require]" <?php if($setting['account'][$option]['confirm']['fields'][$field['id']]['require'] == 1){ ?>checked="checked"<?php } ?> id="<?php echo $option; ?>_confirm_fields_<?php echo $field['id']; ?>_require"/>
																		<?php } ?>
																	<?php } ?>
																</td>
																<?php } ?>
															</tr>     
															<?php } }/*foreach fields*/?>
														</tbody>
													</table>

												</div><!-- /.tabel-responcive -->
											</div><!-- /#confirm-->

											<!---------------------------------- design ---------------------------------->
											<div id="d_design" class="tab-pane">

												<h3 class="page-header">
													<span class="fa fa-paint-brush fa-fw"></span> <span><?php echo $text_design; ?></span>
												</h3>

												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_design_theme; ?>"><?php echo $entry_design_theme; ?></span>
													</label>
													<div class="col-sm-9">
														<select name="<?php echo $id; ?>_setting[design][theme]" class="form-control">
															<?php foreach ($themes as $theme) {?>
															<?php if($setting['design']['theme'] == $theme){ ?>
															<option value="<?php echo $theme; ?>" selected="selected"><?php echo $theme; ?></option>
															<?php }else{ ?>
															<option value="<?php echo $theme; ?>"><?php echo $theme; ?></option>
															<?php } ?>
															<?php } ?>
														</select>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_design_field; ?>"><?php echo $entry_design_field; ?></span>
													</label>
													<div class="col-sm-9">
														<div class="btn-group" data-toggle="buttons">
															<label class="btn btn-success <?php if(isset($setting['design']['block_style']) && $setting['design']['block_style'] == 'row'){ ?> active <?php } ?>">
																<input type="radio" value="row" name="<?php echo $id; ?>_setting[design][block_style]" <?php if(isset($setting['design']['block_style']) && $setting['design']['block_style'] == 'row'){ ?>checked="checked"<?php } ?> id="block_style_row" />
																<?php echo $text_row; ?>
															</label>
															<label class="btn btn-success <?php if(isset($setting['design']['block_style']) && $setting['design']['block_style'] == 'block'){ ?> active <?php } ?>">
																<input type="radio" value="block" name="<?php echo $id; ?>_setting[design][block_style]" <?php if(isset($setting['design']['block_style']) && $setting['design']['block_style'] == 'block'){ ?>checked="checked"<?php } ?> id="block_style_block"/>
															<?php echo $text_block; ?>
															</label>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_design_placeholder; ?>">
															<?php echo $entry_design_placeholder; ?>
														</span>
													</label>
													<div class="col-sm-9">
														<input type="hidden" value="0" name="<?php echo $id; ?>_setting[design][placeholder]" />
														<input type="checkbox" value="1" data-label-text="<?php echo $text_enable; ?>" name="<?php echo $id; ?>_setting[design][placeholder]" <?php echo (isset($setting['design']['placeholder']) && $setting['design']['placeholder'])? 'checked="checked"' : ''; ?> id="design_placeholder" />
													</div>
												</div>
												
												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_design_breadcrumb; ?>">
															<?php echo $entry_design_breadcrumb; ?>
														</span>
													</label>
													<div class="col-sm-9">
														<input type="hidden" value="0" name="<?php echo $id; ?>_setting[design][breadcrumb]" />
														<input type="checkbox" value="1" data-label-text="<?php echo $text_enable; ?>" name="<?php echo $id; ?>_setting[design][breadcrumb]" <?php echo (isset($setting['design']['breadcrumb']) && $setting['design']['breadcrumb'])? 'checked="checked"' : ''; ?> id="design_breadcrumb" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_design_address; ?>"><?php echo $entry_design_address; ?></span>
													</label>
													<div class="col-sm-9">
														<div class="btn-group" data-toggle="buttons">
															<label class="btn btn-success <?php if(isset($setting['design']['address_style']) && $setting['design']['address_style'] == 'radio'){ ?> active <?php } ?>">
																<input type="radio" value="radio" name="<?php echo $id; ?>_setting[design][address_style]" <?php if(isset($setting['design']['address_style']) && $setting['design']['address_style'] == 'radio'){ ?>checked="checked"<?php } ?> id="address_style_radio" />
																<?php echo $text_input_radio; ?>
															</label>
															<label class="btn btn-success <?php if(isset($setting['design']['address_style']) && $setting['design']['address_style'] == 'list'){ ?> active <?php } ?>">
																<input type="radio" value="list" name="<?php echo $id; ?>_setting[design][address_style]" <?php if(isset($setting['design']['address_style']) && $setting['design']['address_style'] == 'list'){ ?>checked="checked"<?php } ?> id="address_style_list"/>
																<?php echo $text_input_list; ?>
															</label>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_design_cart_image_size; ?>"><?php echo $entry_design_cart_image_size; ?></span>
													</label>
													<div class="col-sm-3">
														<div class="input-group">
															<label for="cart_image_size_width" class="input-group-addon"><?php echo $text_width; ?></label>
															<input id="cart_image_size_width" name="<?php echo $id; ?>_setting[design][cart_image_size][width]" value="<?php echo (isset($setting['design']['cart_image_size']['width'])) ? $setting['design']['cart_image_size']['width'] : '150'; ?>" class="form-control"/>
															<span class="input-group-addon">px</span>
														</div>
													</div>
													<div class="col-sm-3">
														<div class="input-group">
															<label for="cart_image_size_height" class="input-group-addon"><?php echo $text_height; ?></label>
															<input id="cart_image_size_height" name="<?php echo $id; ?>_setting[design][cart_image_size][height]" value="<?php echo (isset($setting['design']['cart_image_size']['height'])) ? $setting['design']['cart_image_size']['height'] : '150'; ?>" class="form-control"/>
															<span class="input-group-addon">px</span>
														</div>
													</div>
												</div>

												<div class="form-group hidden">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_design_max_width; ?>"><?php echo $entry_design_max_width; ?></span>
													</label>
													<div class="col-sm-3" >
														<div class="input-group">
															<input id="max_width" name="<?php echo $id; ?>_setting[design][max_width]" value="<?php echo (isset($setting['design']['max_width'])) ? $setting['design']['max_width'] : '960'; ?>" class="form-control" />
															<span class="input-group-addon">px</span>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_design_bootstrap; ?>">
															<?php echo $entry_design_bootstrap ?>
														</span>
													</label>
													<div class="col-sm-9">
														<input type="hidden" value="0" name="<?php echo $id; ?>_setting[design][bootstrap]" />
														<input type="checkbox" value="1" data-label-text="<?php echo $text_enable; ?>" name="<?php echo $id; ?>_setting[design][bootstrap]" <?php echo (isset($setting['design']['bootstrap']) && $setting['design']['bootstrap'])? 'checked="checked"' : ''; ?> id="design_bootstrap" />
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label" for="input-catalog-limit">
														<span data-toggle="tooltip" title="<?php echo $help_design_only_quickcheckout; ?>">
															<?php echo $entry_design_only_quickcheckout; ?>
														</span>
													</label>
													<div class="col-sm-9">
														<input type="hidden" value="0" name="<?php echo $id; ?>_setting[design][only_quickcheckout]" />
														<input type="checkbox" value="1" data-label-text="<?php echo $text_enable; ?>" name="<?php echo $id; ?>_setting[design][only_quickcheckout]" <?php echo (isset($setting['design']['only_quickcheckout']) && $setting['design']['only_quickcheckout'])? 'checked="checked"' : ''; ?> id="design_only_quickcheckout" />
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_design_column; ?>"><?php echo $entry_design_column; ?></span>
													</label>
													<div class="col-sm-9">
														<div id="column_design" style="position:static; ">

															<div class="column-width-group">
																<input id="column_width_1" type="text" class="column-width" name="<?php echo $id; ?>_setting[design][column_width][1]" value="<?php echo $setting['design']['column_width'][1]; ?>" 
																/><input id="column_width_2" type="text" class="column-width" name="<?php echo $id; ?>_setting[design][column_width][2]" value="<?php echo $setting['design']['column_width'][2]; ?>" 
																/><input id="column_width_3" type="text" class="column-width" name="<?php echo $id; ?>_setting[design][column_width][3]" value="<?php echo $setting['design']['column_width'][3]; ?>" 
																/><input id="column_width_4" type="hidden" class="column-width" name="<?php echo $id; ?>_setting[design][column_width][4]" value="<?php echo $setting['design']['column_width'][4]; ?>" />
															</div >
															<input id="column_slider" type="text" class="span2" value="" data-slider-min="0" data-slider-max="12" data-slider-step="1" tooltip="hide" data-slider-value="[<?php echo $setting['design']['column_width'][1] ?>,<?php echo intval($setting['design']['column_width'][1])+intval($setting['design']['column_width'][2]); ?>]"/>
															<div id="column_groups">

																	
																<ul class="column column-group column-group-1 qc-col-1" id="column_1">
																	<?php foreach ($steps as $step => $value){ ?>

																	<li class="portlet qc-step" id="step_<?php echo $step; ?>" data-col="<?php echo $setting['step'][$step]['column']; ?>" data-row="<?php echo $setting['step'][$step]['row']; ?>" data-id="<?php echo $step; ?>">
																			<div class="portlet-wrap">
																				<div class="portlet-header"><span class="fa fa-<?php echo $setting['step'][$step]['icon']; ?> fa-fw"></span> <?php echo ${'text_'.$step}; ?></div>
																				<div class="portlet-content">
																					
																					<div class="text"><?php echo ${'help_'.$step}; ?></div>
																					<div class="text"><i class="fa fa-drag"></i></div>
																					<input type="hidden" class="sort data-column" name="<?php echo $id; ?>_setting[step][<?php echo $step; ?>][column]" value="<?php echo $setting['step'][$step]['column']; ?>" />
																					<input type="hidden" class="sort data-row" name="<?php echo $id; ?>_setting[step][<?php echo $step; ?>][row]" value="<?php echo $setting['step'][$step]['row']; ?>" />
																				</div>
																			</div>
																	</li>

																	<?php } ?>
																</ul>
																<div class="column-group column-group-2"> 
																	<ul class="column qc-col-2" id="column_2">
																	</ul>
																	<ul class="column qc-col-3" id="column_3">
																	</ul>
												 					<ul class="column qc-col-4" id="column_4">
																	</ul>
																</div> <!-- /.column-group column-group-2 -->
															</div>
														</div> <!-- #column_design -->
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label" >
														<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_design_custom_style; ?>"><?php echo $entry_design_custom_style; ?></span>
													</label>
													<div class="col-sm-9">
														<?php if(isset($setting['design']['custom_style'])){ ?>
														<textarea name="<?php echo $id; ?>_setting[design][custom_style]" id="design_custom_style" class="form-control" rows="5"><?php echo $setting['design']['custom_style']; ?></textarea>
														<?php }else{ ?>
														<textarea name="<?php echo $id; ?>_setting[design][custom_style]" id="design_custom_style" class="form-control" rows="5"></textarea>
														<?php } ?>
													</div>
												</div>
											</div><!-- /#design-->

											<!---------------------------------- analytics ---------------------------------->
											<div id="d_analytics" class="tab-pane">
												
													<h3 class="page-header">
														<span class="fa fa-bar-chart fa-fw"></span> <span><?php echo $text_analytics; ?></span>
													</h3>
													<table class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th><?php echo $column_order_id; ?></th>
																<th><?php echo $column_customer; ?></th>
																<th><?php echo $column_account; ?></th>
																<th><?php echo $column_total; ?></th>
																<th><?php echo $column_status; ?></th>
																<th><?php echo $column_shipping_method; ?></th>
																<th><?php echo $column_payment_method; ?></th>
																<th><?php echo $column_data; ?></th>
																<th><?php echo $column_checkout_time; ?></th>
																<th><?php echo $column_rating; ?></th>
															</tr>
														</thead>
														<tbody>
															<?php foreach($statistics as $stat){ ?>
															<tr>
																<td><?php echo $stat['order_id']; ?></td>
															<?php if($stat['href_customer']) { ?>
																<td><a href="<?php echo $stat['href_customer']; ?>"> <?php echo $stat['firstname']; ?> <?php echo $stat['lastname']; ?></a></td>
															<?php }else{ ?>
																<td><?php echo $stat['firstname']; ?> <?php echo $stat['lastname']; ?></td>
															<?php } ?>
																<td><?php echo $stat['data']['account']; ?></td>
																<td><?php echo $stat['total']; ?></td>
																<td><?php echo $stat['order_status_id']; ?></td>
																<td><?php echo $stat['shipping_method']; ?></td>
																<td><?php echo $stat['payment_method']; ?></td>
																<td>
																	<?php if(!empty($stat['data']['field'])){ ?><span class="label label-default" data-toggle="tooltip" title="<samp><?php print_r($stat['data']['data']['field'][$stat['data']['account']]); ?></samp>"><i class="fa fa-bars"></i> <?php echo $stat['data']['field']; ?></span> <?php } ?>
																	<?php if(!empty($stat['data']['data']['update'])){ ?><span class="label label-info" data-toggle="tooltip" title="<samp><?php print_r($stat['data']['data']['update']); ?></samp>"><i class="fa fa-pencil"></i> <?php echo $stat['data']['total']['update']; ?></span> <?php } ?>
																	<?php if(!empty($stat['data']['data']['click'])){ ?><span class="label label-success" data-toggle="tooltip" title="<samp><?php print_r($stat['data']['data']['click']); ?></samp>"><i class="fa fa-hand-o-down"></i> <?php echo $stat['data']['total']['click']; ?></span> <?php } ?>
																	<?php if(!empty($stat['data']['data']['error'])){ ?><span class="label label-danger" data-toggle="tooltip" title="<samp><?php print_r($stat['data']['data']['error']); ?></samp>"><i class="fa fa-exclamation-triangle"></i> <?php echo $stat['data']['total']['error']; ?></span> <?php } ?>
																</td>
																<td><?php echo gmdate("H:i:s", $stat['checkout_time']); ?></td>
																<td><?php echo $stat['rating']; ?></td>
															</tr>
															<?php } ?>

															

															
														</tbody>
													</table>	
											</div><!-- /#analytics-->

										</div> <!-- /.tab-content -->
									</div> <!-- /.col-sm-10 -->
								</div> <!-- /.row -->
							</div><!-- /.tab-body -->
						</div><!-- /.tab-pane -->	


						
						<div class="tab-pane" id="debug" >
							<div class="tab-body">

								<textarea id="textarea_debug_log" wrap="off" rows="15" readonly="readonly" class="form-control"><?php echo $debug_log; ?></textarea>
								<br/>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input_debug_file"><?php echo $entry_debug_file; ?></label>
									<div class="col-sm-10">
										<input type="text" id="input_debug_file" name="<?php echo $id; ?>_debug_file" value="<?php echo $debug_file; ?>"  class="form-control"/>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-10 col-sm-offset-2">
										<a class="btn btn-danger" id="clear_debug_file"><?php echo $button_clear; ?></a>
									</div>
								</div>


							</div>
						</div>
						
						<div class="tab-pane" id="support" >
							<div class="tab-body">

							</div>
						</div>
						<div class="tab-pane" id="instruction" >
							<div class="tab-body">
								<?php echo $text_instruction; ?>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php 
$column_1 = ($setting['design']['column_width'][1]/12)*100; 
$column_2 = ($setting['design']['column_width'][2]/12)*100;
$column_3 = ($setting['design']['column_width'][3]/12)*100;
$column_4 = ((intval($setting['design']['column_width'][2]) + intval($setting['design']['column_width'][3]))/12)*100; 
?>

<style>
#column_width_1,
#column_1{ 
  width: <?php echo $column_1 ?>%;
}
#column_width_2,
#column_2{
  width: <?php echo $column_2 ?>%;
}
#column_width_3,
#column_3{
  width: <?php echo $column_3 ?>%;
}

#column_4{
  width: <?php echo $column_4 ?>%
}

</style>
<script type="text/javascript"><!--
	$('#column_slider').slider({
		'tooltip': 'hide'
	}).on('slide', function(ev){
	var	val1 = Number(ev.value[0]);
    var pos1 = (val1/12)*100
    var val2 = Number(ev.value[1]);
    var pos2 = (val2/12)*100
    $("#column_1, #column_width_1").css({'width' : pos1+'%'})
    $("#column_width_1").val(val1)
    $("#column_2, #column_width_2").css({'width' : pos2-pos1 +'%'})
    $("#column_width_2").val(val2-val1)
    $("#column_3, #column_width_3").css({'width' : Number(100-pos2) +'%'})
    $("#column_width_3").val(Number(12-val2))
    $("#column_4").css({'width' : Number(100-pos1) +'%'})
    $("#column_width_4").val(Number(12-val1))
    console.log(ev.value[0])
  })
var adjustment


	var group = $("#column_groups ul.column").sortable({
		group: 'column',
		pullPlaceholder: false,
	// animation on drop
	onDrop: function  (item, targetContainer, _super) {

		var clonedItem = $('<li/>').css({height: 0})
		item.before(clonedItem)
		clonedItem.animate({'height': item.height()})

		item.animate(clonedItem.position(), function  () {
			clonedItem.detach()
			_super(item)
		})

		var data = group.sortable("serialize").get();

		var jsonString = JSON.stringify(data, null, ' ');

		$.each(data, function( column, column_value ) {
			$.each(column_value, function( row, row_value ) {
				console.log(row_value['id'] + ' column: ' + column+1 + 'row: ' + row)
				$('#step_'+ row_value['id'] + ' .data-column').val(column+1)
				$('#step_'+ row_value['id'] + ' .data-row').val(row)
			});
		});


	},

	// set item relative to cursor position
	onDragStart: function ($item, container, _super) {
		var offset = $item.offset(),
		pointer = container.rootGroup.pointer

		adjustment = {
			left: pointer.left - offset.left,
			top: pointer.top - offset.top
		}

		_super($item, container)
	},
	onDrag: function ($item, position) {
		$item.css({
			left: position.left - adjustment.left,
			top: position.top - adjustment.top
		})
	}
	})

$('.sortable > tr').tsort({attr:'sort-data'});
$(function () {
	$("[type='checkbox']").bootstrapSwitch({
		'onColor': 'success',
		'onText': '<?php echo $text_yes; ?>',
		'offText': '<?php echo $text_no; ?>',
	});

	$('[data-toggle="popover"]').popover()

	$('.qc-step').each(function(){
		$(this).appendTo('.qc-col-' + $(this).attr('data-col'));	
	})
	$('.qc-step').tsort({attr:'data-row'});


  $(".table-sortable").sortable({
    containerSelector: 'table',
    itemPath: '',
    itemSelector: 'tr',
    distance: '10',
    pullPlaceholder: false,
    placeholder: '<tr class="placeholder"><td colspan="5" /></tr>',
    onDragStart: function (item, container, _super) {
      var offset = item.offset(),
      pointer = container.rootGroup.pointer

      adjustment = {
        left: pointer.left - offset.left,
        top: pointer.top - offset.top
      }

      _super(item, container)
    },
    onDrag: function (item, position) {
      item.css({
        left: position.left - adjustment.left,
        top: position.top - adjustment.top
      })
    },
    onDrop: function  (item, container, _super) {
      item.closest('tbody').find('tr').each(function (i, row) {
        console.log(i)
        $(row).find('.sort').val(i)
        
      })
   
      _super(item)
    }
  })


	$('body').on('change', '#select_config', function(){
		console.log('#select_config changed')
		var config = $(this).val();
		$('body').append('<form action="<?php echo $action; ?>" id="config_update" method="post" style="display:none;"><input type="text" name="config" value="' + config + '" /></form>');
		$('#config_update').submit();
	});

	$('body').on('click', '#save_and_stay', function(){

		$.ajax( {
			type: 'post',
			url: $('#form').attr('action') + '&save',
			data: $('#form').serialize(),
			beforeSend: function() {
				$('#form').fadeTo('slow', 0.5);
			},
			complete: function() {
				$('#form').fadeTo('slow', 1);   
			},
			success: function( response ) {
				console.log( response );
			}
		});  
	});

	$('body').on('click', '#button_update', function(){ 
		$.ajax( {
			url: '<?php echo $update; ?>',
			type: 'post',
			dataType: 'json',

			beforeSend: function() {
				$('#button_update').find('.fa-refresh').addClass('fa-spin');
			},

			complete: function() {
				$('#button_update').find('.fa-refresh').removeClass('fa-spin');   
			},

			success: function(json) {
				console.log(json);

				if(json['error']){
					$('#notification_update').html('<div class="alert alert-danger m-b-none">' + json['error'] + '</div>')
				}

				if(json['warning']){
					$html = '';

					if(json['update']){
						$.each(json['update'] , function(k, v) {
							$html += '<div>Version: ' +k+ '</div><div>'+ v +'</div>';
						});
					}
					$('#notification_update').html('<div class="alert alert-warning alert-inline">' + json['warning'] + $html + '</div>')
				}

				if(json['success']){
					$('#notification_update').html('<div class="alert alert-success alert-inline">' + json['success'] + '</div>')
				} 
			},
			error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('body').on('click', '#clear_debug_file', function(){ 
		$.ajax( {
			url: '<?php echo $clear_debug_file; ?>',
			type: 'post',
			dataType: 'json',
			data: 'debug_file=<?php echo $debug_file; ?>',

			beforeSend: function() {
				$('#form').fadeTo('slow', 0.5);
			},

			complete: function() {
				$('#form').fadeTo('slow', 1);   
			},

			success: function(json) {
				$('.alert').remove();
				console.log(json);

				if(json['error']){
					$('#debug .tab-body').prepend('<div class="alert alert-danger">' + json['error'] + '</div>')
				
				}

				if(json['success']){
					$('#debug .tab-body').prepend('<div class="alert alert-success">' + json['success'] + '</div>')
					$('#textarea_debug_log').val('');
				} 
			}
		});
	});
	$('body').on('click', '.view-setting', function(){ 
		var setting_id = $(this).attr('data-setting-id');
		location.href = updateURLParameter($(location).attr('href'), 'setting_id', setting_id);
		return false;
	});

	$('body').on('click', '#create_setting', function(){ 
	
		$.ajax( {
			url: '<?php echo $create_setting; ?>',
			type: 'post',
			dataType: 'json',
			data:  $('#form').serialize(),

			beforeSend: function() {
				$('#form').fadeTo('slow', 0.5);
			},

			complete: function() {
				$('#form').fadeTo('slow', 1);   
			},

			success: function(json) {
				$('.alert').remove();
				console.log(json);

				if(json['error']){
					$('#content > .container-fluid').prepend('<div class="alert alert-warning">' + json['error'] + '</div>')
				
				}

				if(json['redirect']){
					location.href = json['redirect'];
				}
			}
		});
		return false;
	});

	$('body').on('click', '#generate_setting', function(){
		$('#bulk_setting').val(JSON.stringify($('#form').serializeObject().<?php echo $id; ?>_setting));
		return false;
	})
	$('body').on('click', '#save_bulk_setting', function(){ 
	
		$.ajax( {
			url: '<?php echo $save_bulk_setting; ?>',
			type: 'post',
			dataType: 'json',
			data:  'setting_id=<?php echo $setting_id; ?>&setting=' + $('#bulk_setting').val(),

			beforeSend: function() {
				$('#form').fadeTo('slow', 0.5);
			},

			complete: function() {
				$('#form').fadeTo('slow', 1);   
			},

			success: function(json) {
				$('.alert').remove();
				console.log(json);

				if(json['error']){
					$('#notification_setting').prepend('<div class="alert alert-warning alert-inline">' + json['error'] + '</div>')
				}

				if(json['redirect']){
					location.href = json['redirect'];
				}
			}
		});
		return false;
	});


});
function updateURLParameter(url, param, paramVal)
{
    var TheAnchor = null;
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";

    if (additionalURL) 
    {
        var tmpAnchor = additionalURL.split("#");
        var TheParams = tmpAnchor[0];
            TheAnchor = tmpAnchor[1];
        if(TheAnchor)
            additionalURL = TheParams;

        tempArray = additionalURL.split("&");

        for (i=0; i<tempArray.length; i++)
        {
            if(tempArray[i].split('=')[0] != param)
            {
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }        
    }
    else
    {
        var tmpAnchor = baseURL.split("#");
        var TheParams = tmpAnchor[0];
            TheAnchor  = tmpAnchor[1];

        if(TheParams)
            baseURL = TheParams;
    }

    if(TheAnchor)
        paramVal += "#" + TheAnchor;

    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
}

$('body').on('change', '#payment_address_country_id_input select', function() {
	$.ajax({
		url: 'index.php?route=module/d_quickcheckout/getZone&token=<?php echo $token; ?>&country_id=' + this.value,
		dataType: 'json',
		
		success: function(json) {
			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json && json != '') {
				for (i = 0; i < json.length; i++) {
					html += '<option value="' + json[i]['value'] + '"';
					html += '>' + json[i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}

			$('#payment_address_zone_id_input select').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
$('body').on('change', '#shipping_address_country_id_input select', function() {
	$.ajax({
		url: 'index.php?route=module/d_quickcheckout/getZone&token=<?php echo $token; ?>&country_id=' + this.value,
		dataType: 'json',
		
		success: function(json) {
			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json && json != '') {
				for (i = 0; i < json.length; i++) {
					html += '<option value="' + json[i]['value'] + '"';
					html += '>' + json[i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}

			$('#shipping_address_zone_id_input select').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
//--></script> 
<?php echo $footer; ?>