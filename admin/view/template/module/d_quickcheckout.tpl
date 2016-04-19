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
                <select class="form-control" onChange="location = '<?php echo $module_link; ?>&store_id=' + $(this).val()">
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
        <?php if (!empty($error['shopunity'])) { ?>
        <div class="alert alert-danger">
            <i class="fa fa-exclamation-circle"></i> <?php echo $error['shopunity']; ?>
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
																<span data-toggle="tooltip" title="<?php echo $help_general_compress; ?>">
																	<?php echo $entry_general_compress; ?>
																</span>
															</label>
															<div class="col-sm-2">
																<input type="hidden" value="0" name="<?php echo $id; ?>_setting[general][compress]" />
																<input type="checkbox" value="1" name="<?php echo $id; ?>_setting[general][compress]" <?php if(isset($setting['general']['compress']) && $setting['general']['compress'] == 1){ ?>checked="checked"<?php } ?> id="general_compress" />
															</div>
                                                            <div class="col-sm-6">
                                                                <button class="btn btn-primary btn-block" id="compress_update"><i class="fa fa-refresh"></i> <?php echo $compress_update; ?></button>
                                                        	</div>
                                                            <div id="compress-notification" class="col-sm-offset-4 help-block col-sm-8" >
                                                            </div>
                                                   		</div>
                                                    	<?php if ($config_files) { ?>
                                                            <div class="form-group">
                                                            <label class="col-sm-4 control-label" for="select_config"><?php echo $entry_config_files; ?></label>
                                                            <div class="col-sm-8">
                                                                <div class="input-group">
                                                                    <select id="select_config"  class="form-control" name="<?php echo $id;?>_setting[general][config]">
                                                                        <?php foreach ($config_files as $config_file) { ?>
                                                                        <option value="<?php echo $config_file; ?>" <?php echo ($config_file == $config)? 'selected="selected"' : ''; ?>><?php echo $config_file; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                    <span class="input-group-btn">
                                                                        <button class="btn btn-primary" id="select_config_submit" type="button">Apply</button>
                                                                    </span>
                                                                </div><!-- /input-group -->
                                                                
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
																	<label class="input-group-addon" for="general_min_order_text_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="<?php echo $language['flag']; ?>" title="<?php echo $language['name']; ?>" /></label>
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
																	<label class="input-group-addon" for="general_min_quantity_text_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="<?php echo $language['flag']; ?>" title="<?php echo $language['name']; ?>" /></label>
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
									
											</div><!-- /#general-->

									        <!---------------------------------- login ---------------------------------->
									        <div id="d_login" class="tab-pane">
									          
									         	<h3 class="page-header">
									         		<span class="fa fa-key fa-fw"></span> <span><?php echo $text_login; ?></span>
									         	</h3>
												<div class="bs-callout bs-callout-warning"><?php echo $text_need_full_version; ?></div>
									         	<?php if($social_login) { ?>
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

											</div><!-- /#login-->

											<!---------------------------------- payment_address ---------------------------------->
											<div id="d_payment_address" class="tab-pane">

												<h3 class="page-header">
													<span class="fa fa-book fa-fw"></span> <span><?php echo $text_payment_address; ?></span>
												</h3>
												 <div class="bs-callout bs-callout-warning"><?php echo $text_need_full_version; ?></div>
												 
											</div><!-- /#payment_address-->

											<!---------------------------------- shipping_address ---------------------------------->
											<div id="d_shipping_address" class="tab-pane">

												<h3 class="page-header">
													<span class="fa fa-book fa-fw"></span> <span><?php echo $text_shipping_address; ?></span>
												</h3>
												<div class="bs-callout bs-callout-warning"><?php echo $text_need_full_version; ?></div>
												
											</div><!-- /#shipping_address-->

											<!---------------------------------- shipping_method ---------------------------------->
											<div id="d_shipping_method" class="tab-pane">

												<h3 class="page-header">
													<span class="fa fa-truck fa-fw"></span> <span><?php echo $text_shipping_method; ?></span>
												</h3>
												<div class="bs-callout bs-callout-warning"><?php echo $text_need_full_version; ?></div>
												
											</div><!-- /#shipping_method-->
									   
											<!---------------------------------- payment_method ---------------------------------->
											<div id="d_payment_method" class="tab-pane">

												<h3 class="page-header">
													<span class="fa fa-credit-card fa-fw"></span> <span><?php echo $text_payment_method; ?></span>
												</h3>
												<div class="bs-callout bs-callout-warning"><?php echo $text_need_full_version; ?></div>
											</div><!-- /#payment_method-->

											<!---------------------------------- confirm ---------------------------------->
											<div id="d_confirm" class="tab-pane">

												<h3 class="page-header">
													<span class="fa fa-shopping-cart fa-fw"></span> <span><?php echo $text_cart; ?></span>
												</h3>
												<div class="bs-callout bs-callout-warning"><?php echo $text_need_full_version; ?></div>
												
											</div><!-- /#confirm-->

											<!---------------------------------- design ---------------------------------->
											<div id="d_design" class="tab-pane">

												<h3 class="page-header">
													<span class="fa fa-paint-brush fa-fw"></span> <span><?php echo $text_design; ?></span>
												</h3>
												 <div class="bs-callout bs-callout-warning"><?php echo $text_need_full_version; ?></div>
												 
											</div><!-- /#design-->

											<!---------------------------------- analytics ---------------------------------->
											<div id="d_analytics" class="tab-pane">
												
													<h3 class="page-header">
														<span class="fa fa-bar-chart fa-fw"></span> <span><?php echo $text_analytics; ?></span>
													</h3>
													 <div class="bs-callout bs-callout-warning"><?php echo $text_need_full_version; ?></div>
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


	$('#select_config_submit').on('click', function(){
	    var config = $('#select_config').val();
	    $('#content').append('<form action="<?php echo $module_link; ?><?php echo ($stores) ? "&store_id=' + $('#store').val() + '" : ''; ?>" id="config_update" method="post" style="display:none;"><input type="text" name="config" value="' + config + '" /></form>');
	    $('#config_update').submit();
	})

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

                                                                                        $('body').on('click', '#compress_update', function(e){

                                                                                $.ajax({
                                                                                //url: '<?php echo HTTP_CATALOG; ?>compress.php',
                                                                                url: 'index.php?route=module/d_quickcheckout/updateCompress&token=<?php echo $token; ?>',
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
                                                                                                if (json['success']){
                                                                                        $('#compress-notification').prepend('<div class="alert alert-success alert-inline">' + json['success'] + '</div>')
                                                                                        }
                                                                                        if (json['error']){
                                                                                        $('#compress-notification').prepend('<div class="alert alert-warning alert-inline">' + json['error'] + '</div>')
                                                                                        }


                                                                                        }
                                                                                });
                                                                                        e.preventDefault();
                                                                                });
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