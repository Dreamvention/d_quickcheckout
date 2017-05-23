<?php 
$_['d_quickcheckout_debug'] = 0;
$_['d_quickcheckout_debug_file'] = 'd_quickcheckout.log';
$_['d_quickcheckout_trigger'] = '#button-confirm, .button, .btn, .button_oc, input[type=submit]';
$_['d_quickcheckout_setting'] = array(
	'version' => '',
	'name' => 'default',
	'general' => array(
		'enable' => 0,
		'min_order' => array('value' => 0, 'text' => array(1 => 'You must have a sum more then %s to make an order ')),
		'min_quantity' => array('value' => 0, 'text' => array(1 => 'You must have a quantity more then %s to make an order ')),
		'main_checkout' => 1,
		//'default_email' => $this->config->get('config_email'),
		
		'social_login_style' => 'icons',
		'store_id' => 0,
		'clear_session' => 0,
		'login_refresh' => 0,
		'settings' => array('value' => 0, 'bulk' => ''),
		'social_login' => 0,
		'analytics_event' => 0,
		'loader' => 'catalog/d_quickcheckout/svg-loaders/puff.svg',
		'compress' => 1,
	),
	'design' => array(
		'theme' => 'default',
		'column_width' => array(1 => '4', 2 => '4', 3 => '4', 4 => '8'),							
		'login_style' => 'popup',
		'address_style' => 'radio',
		'placeholder' => 1,
		'breadcrumb' => 1,
		'block_style' => 'row',
		'uniform' => 0,
		'max_width' => '',
		'only_quickcheckout' => 0,
		'telephone_countries' => '',
		'telephone_preferred_countries' => '',
		'telephone_validation' => 0,
		'cart_image_size' => array('width' => 80, 'height' => 80),				
		'custom_style' => '',
		'bootstrap' => 1,
	),
	'step' => array(
			'login' => array(
				'id' => 'login',
				'icon' => 'fa fa-user',
				'description' => '',
				'sort_order' => 1,
				'column' => 1,
				'row' => 1,
				'width' => '50',
				'default_option' => 'guest',
				'option' => array('register' => array('title' => 'text_register',
												 	  'description' => 'text_register_account',
													  'display' => 1
								 				),
								   'guest' => array('title' => 'text_guest',
												 	'description' => 'step_option_guest_desciption',
													 'display' => 1
								 				),
								   'login' => array('title' => 'text_login',
								   					'display' => 1
								 				)
				)
				
			),
			'payment_address' => array(
					'id' => 'payment_address',
					'title' => 'title_payment_address',
					'description' => 'description_payment_address',
					'icon' => 'fa fa-book',
					'sort_order' => '2',
					'column' => 1,
					'row' => 2,  
					'width' => '50',
					'fields' => array(
						'firstname' => array(
								'id' => 'firstname',
								'title' => 'entry_firstname', 
								'tooltip' => '',
								'error' => array(0 => array('min_length' => 1, 
															 'max_length' => 32, 
															 'text' => 'error_firstname')),
								'type' => 'text',
								'refresh' => '0',
								'custom' => 0,
								'sort_order' => 0,
								'class' => '',
								'mask' => '',
						),
						'lastname' => array(
								'id' => 'lastname',
								 'title' => 'entry_lastname', 
								 'tooltip' => '',
								 'error' => array(0 => array('min_length' => 1, 
															 'max_length' => 32, 
															 'text' => 'error_lastname')),
								 'type' => 'text',
								 'refresh' => '0',
								 'custom' => 0,
								 'sort_order' => 1,
								 'class' => '',
								 'mask' => '',
						),
						'email' => array(
								'id' => 'email',
								'title' => 'entry_email', 
								'tooltip' => '',
								'error' => array(),
								'type' => 'email',
								'refresh' => '0',
								'custom' => 0,
								'sort_order' => 2,
								'class' => '',
								'mask' => '',
						),
						'email_confirm' => array(
								'id' => 'email_confirm',
								'title' => 'entry_email_confirm', 
								'tooltip' => '',
								'error' => array(0 => array('compare_to' => '#payment_address_email',
															 'text' => 'error_email_confirm')),
								
								'type' => 'text',
								'refresh' => '0',
								'custom' => 0,
								'sort_order' => 3,
								'class' => '',
								'mask' => '',
						),
						'telephone' => array(
								'id' => 'telephone',
								'title' => 'entry_telephone', 
								'tooltip' => '',
								'error' => array(0 => array('min_length' => 3, 
															'max_length' => 32,
															  
															'text' => 'error_telephone'),
												1 => array('telephone' => true,
															'text' => 'error_valid_telephone')
												),
								'placeholder' => 'placeholder_telephone', 
								'type' => 'tel',
								'refresh' => '0',
								'custom' => 0,
								'sort_order' => 4,
								'class' => '',
								'mask' => '',
						),
						
						'fax' => array(
								'id' => 'fax',
								'title' => 'entry_fax',
								'tooltip' => '',
								'error' => array(0 => array('min_length' => 3, 
															 'max_length' => 32, 
															 'text' => 'error_telephone')),
								'type' => 'text',
								'refresh' => '0',
								'custom' => 0,
								'sort_order' => 5,
								'class' => '',
								'mask' => '',
						),
						'password' => array(
								'id' => 'password',
								'title' => 'entry_password', 
								'tooltip' => '',
								'error' => array(0 => array('min_length' => 3, 
															 'max_length' => 20, 
															 'text' => 'error_password')),
								'type' => 'password',
								'refresh' => '3',
								'custom' => 0,
								'sort_order' => 6,
								'class' => ''
						),
						'confirm' => array(
								'id' => 'confirm',
								'title' => 'entry_confirm',
								'tooltip' => '',
								'error' => array(0 => array('compare_to' => '#payment_address_password',
															 'text' => 'error_confirm'),
												 1 => array('min_length' => '1',
															 'text' => 'error_confirm')),
								'type' => 'password',
								'refresh' => '3',
								'custom' => 0,
								'sort_order' => 7,
								'class' => ''
						),
						'heading' => array(
								'id' => 'heading',
								'title' => 'text_your_address', 
								'type' => 'heading',
								'sort_order' => 8,
								'class' => ''
						),
						'company' => array(
								'id' => 'company',
								'title' => 'entry_company',
								'tooltip' => '',
								'error' => array(0 => array('min_length' => 3, 
															 'max_length' => 34, 
															 'text' => 'error_step_payment_address_fields_company')),
								'type' => 'text',
								'refresh' => '0',
								'custom' => 0,
								'sort_order' => 9,
								'class' => '',
								'mask' => '',
						),
						'customer_group_id' => array(
								'id' => 'customer_group_id',
								'title' => 'entry_customer_group', 
								'tooltip' => '',
								'type' => 'radio',
								'refresh' => '2',
								'custom' => 0,
								'sort_order' => 10,
								'class' => ''
						),
						'address_1' => array(
								'id' => 'address_1',
								'title' => 'entry_address_1', 
								'tooltip' => '',
								'error' => array(0 => array('min_length' => 3, 
															 'max_length' => 128, 
															 'text' => 'error_address_1')),
								'type' => 'text',
								'refresh' => '0',
								'custom' => 0,
								'sort_order' => 11,
								'class' => '',
								'mask' => '',
						),
						'address_2' => array(
								'id' => 'address_2',
								'title' => 'entry_address_2', 
								'tooltip' => '',
								'error' => array(0 => array('min_length' => 3, 
															 'max_length' => 128, 
															 'text' => 'error_address_1')),
								'type' => 'text',
								'refresh' => '0',
								'custom' => 0,
								'sort_order' => 12,
								'class' => '',
								'mask' => '',
						 ),
						'city' => array(
								'id' => 'city',
								'title' => 'entry_city', 
								'tooltip' => '',
								'error' => array(0 => array('min_length' => 3, 
															 'max_length' => 128, 
															 'text' => 'error_city')),
								'type' => 'text',
								'refresh' => '0',
								'custom' => 0,
								'sort_order' => 13,
								'class' => ''
						),
						'postcode' => array(
								'id' => 'postcode',
								'title' => 'entry_postcode', 
								'tooltip' => '',
								'error' => array(0 => array('min_length' => 2, 
															 'max_length' => 10, 
															 'text' => 'error_postcode')),
								'type' => 'text',
								'refresh' => '3',
								'custom' => 0,
								'sort_order' => 14,
								'class' => '',
								'mask' => '',
						),
						'country_id' => array(
								'id' => 'country_id',
								'title' => 'entry_country', 
								'tooltip' => '',
								'error' => array(0 => array('not_empty' => true,																		
															 'text' => 'error_country')),
								'type' => 'select',
								'refresh' => '3',
								'custom' => 0,
								'sort_order' => 15,
								'class' => 'country'
						),
						'zone_id' => array(
								'id' => 'zone_id',
								'title' => 'entry_zone', 
								'tooltip' => '',
								'error' => array(0 => array('not_empty' => true,																		 																		 'text' => 'error_zone')),
								'type' => 'select',
								'refresh' => '3',
								'custom' => 0,
								'sort_order' => 16,
								'class' => 'zone'
						),
						'newsletter' => array(
								'id' => 'newsletter',
								'title' => 'entry_newsletter',
								'tooltip' => '',
								'error' => array(0 => array('checked' => true,
															 'text' => array(1 => 'You must subsribe to our newsletter to make the order'))),
								'type' => 'checkbox',
								'custom' => 0,
								'class' => '',
								'refresh' => '0',
								'sort_order' => 17,
								'value' => '1'
						),
						'shipping_address' => array(
								'id' => 'shipping_address',
								'title' => 'entry_shipping',
								'tooltip' => '',
								'error' => array(0 => array('checked' => true,
															 'text' => 'error_step_payment_address_fields_shipping_address')),
								'type' => 'checkbox',
								'refresh' => '3',
								'custom' => 0,
								'sort_order' => 18,
								'class' => '',
								
						),
						'agree' => array(
								'id' => 'agree',
								'title' => 'text_agree', 
								//'information_id' => $this->config->get('config_account_id'),
								'tooltip' => '',
								'error' => array(0 => array('checked' => true,
															 'text' => 'error_agree',
											 				//'information_id' => $this->config->get('config_account_id')
															)),
								'type' => 'checkbox',
								'refresh' => '0',
								'custom' => 0,
								'sort_order' => 19,
								'class' => ''
						)
						
					)
			),
			'shipping_address' => array(
					'id' => 'shipping_address',
					'title' => 'title_shipping_address',
					'description' => 'description_shipping_address',
					'icon' => 'fa fa-book',
					'sort_order' => '3',
					'column' => 1,
					'row' => 3,
					'width' => '30', 
					'fields' => array(
						'firstname' => array(
							'id' => 'firstname',
							'title' => 'entry_firstname', 
							'tooltip' => '',
							'error' => array(0 => array('min_length' => 1, 
														 'max_length' => 32, 
														 'text' => 'error_firstname')),
							'type' => 'text',
							'refresh' => '0',
							'custom' => 0,
							// 'display' => 0,
							// 'require' => 0,
							'sort_order' => 1,
							'class' => '',
							'mask' => '',
						),
						'lastname' => array(
							'id' => 'lastname',
							'title' => 'entry_lastname', 
							'tooltip' => '',
							'error' => array(0 => array('min_length' => 1, 
														 'max_length' => 32, 
														 'text' => 'error_lastname')),
							'type' => 'text',
							'refresh' => '0',
							'custom' => 0,
							// 'display' => 0,
							// 'require' => 0,
							'sort_order' => 2,
							'class' => '',
							'mask' => '',
						),
						'company' => array(
							'id' => 'company',
							'title' => 'entry_company',  
							'tooltip' => '',
							'error' => array(0 => array('min_length' => 3, 
														 'max_length' => 32, 
														 'text' => 'error_step_shipping_address_fields_company')),
							'type' => 'text',
							'refresh' => '0',
							'custom' => 0,
							// 'display' => 0,
							// 'require' => 0,
							'sort_order' => 3,
							'class' => '',
							'mask' => '',
						),
						'address_1' => array(
							'id' => 'address_1',
							'title' => 'entry_address_1', 
							'tooltip' => '',
							'error' => array(0 => array('min_length' => 3, 
														 'max_length' => 128, 
														 'text' => 'error_address_1')),
							'type' => 'text',
							'refresh' => '0',
							'custom' => 0,
							// 'display' => 0,
							// 'require' => 0,
							'sort_order' => 4,
							'class' => '',
							'mask' => '',
						),
						'address_2' => array(
							'id' => 'address_2',
							'title' => 'entry_address_2', 
							'tooltip' => '',
							'error' => array(0 => array('min_length' => 3, 
														 'max_length' => 128, 
														 'text' => 'error_address_1')),
							'type' => 'text',
							'refresh' => '0',
							'custom' => 0,
							// 'display' => 0,
							// 'require' => 0,
							'sort_order' => 5,
							'class' => '',
							'mask' => '',
						),
						'city' => array(
							'id' => 'city',
							'title' => 'entry_city',
							'tooltip' => '',
							'error' => array(0 => array('min_length' => 3, 
														 'max_length' => 128, 
														 'text' => 'error_city')),
							'type' => 'text',
							'refresh' => '0',
							'custom' => 0,
							// 'display' => 0,
							// 'require' => 0,
							'sort_order' => 6,
							'class' => '',
							'mask' => '',
						),
						'postcode' => array(
							'id' => 'postcode',
							'title' => 'entry_postcode',
							'tooltip' => '',
							'error' => array(0 => array('min_length' => 2, 
														'max_length' => 10, 
														'text' => 'error_postcode')),
							'type' => 'text',
							'refresh' => '0',
							'custom' => 0,
							// 'display' => 0,
							// 'require' => 0,
							'sort_order' => 7,
							'class' => '',
							'mask' => '',
						),
						'country_id' => array(
							'id' => 'country_id',
							'title' => 'entry_country', 
							'tooltip' => '',
							'error' => array(0 => array('min_length' => 1,
														 'text' => 'error_country')),
							'type' => 'select',
							'refresh' => '4',
							'custom' => 0,
							// 'display' => 0,
							// 'require' => 0,
							'sort_order' => 8,
							'class' => '',
						),
						'zone_id' => array(
							'id' => 'zone_id',
							'title' => 'entry_zone',
							'tooltip' => '',
							'error' => array(0 => array('min_length' => 1,
													 'text' => 'error_zone')),
							'type' => 'select',
							'refresh' => '4',
							'custom' => 0,
							// 'display' => 0,
							// 'require' => 0,
							'sort_order' => 9,
							'class' => ''
						)
					)
			),
			'shipping_method' => array(
				'id' => 'shipping_method',
				'title' => 'title_shipping_method',
				'description' => 'description_shipping_method',
				'icon' => 'fa fa-truck',
				'sort_order' => 4,
				'column' => 2,
				'row' => 1,
				'display' => 1,
			    'input_style' => 'radio',
			    'display_title' => 1,
		  	    'display_desciption' => 1,
		        'display_options' => 1,
				'width' => '30',
				'default_option' => ''
				
			),
			'payment_method' =>array(
				'id' => 'payment_method',
				'title' => 'title_payment_method',
				'description' => 'description_payment_method',
				'icon' => 'fa fa-credit-card',
				'sort_order' => 5,
				'column' => 3,
				'row' => 1,
				'display' => 1,
			    'display_options' => 1,
				'display_images' => 1,
			    'input_style' => 'radio',
			    'display_logos' => 1,
				'width' => '30',
				'default_option' => ''
			),
			'cart' => array(
				'id' => 'cart',
				'title' => 'title_shopping_cart',
				'description' => 'description_shopping_сart',
				'icon' => 'fa fa-shopping-cart',
				'sort_order' => 6,
				'column' => 4,
				'row' => 2,
				'display_title' => 1,
				'display_description' => 1,
				'width' => '50',											
				'option' => array(
							'voucher' => array(
										'id' => 'voucher',
										'title' => array(1 => 'voucher'),
										'tooltip' => array(1 => 'voucher'),
										'type' => 'text',
										'refresh' => '3',
										'custom' => 0,
										'class' => ''
							),
							'coupon' => array(
										'id' => 'coupon',
										'title' => array(1 => 'coupon'),
										'tooltip' => array(1 => 'coupon'),
										'type' => 'text',
										'refresh' => '3',
										'custom' => 0,
										'class' => ''
							),
							'reward' => array(
										'id' => 'reward',
										'title' => array(1 => 'reward'),
										'tooltip' => array(1 => 'reward'),
										'type' => 'text',
										'refresh' => '3',
										'custom' => 0,
										'class' => ''
							)
				),
			),
			'payment' => array(
				'id' => 'payment',
				'title' => '',
				'icon' => '',
				'sort_order' => 7,
				'column' => 4,
				'row' => 2,
				'default_payment_popup' => 0,
				'payment_popups' => array(),
				'width' => '50'
			),
			'confirm' => array(
				'id' => 'confirm',
				'icon' => '',
				'display_confirm' => 1,
				'sort_order' => 8,
				'column' => 4,
				'row' => 2,
				'width' => '50',
				'fields' => array(
					'comment' => array(
							'id' => 'comment',
							'title' => 'text_comments',
							'tooltip' => '',
							'error' => array(0 => array('min_length' => 1,
													 'text' => 'error_step_confirm_fields_comment')),
							'type' => 'textarea',
							'refresh' => '0',
							'custom' => 0,
							'class' => '',
							'sort_order' => 0,
					),
					'agree' => array(
							'id' => 'agree',
							'title' => 'text_agree',
							//'information_id' => $this->config->get('config_checkout_id'),
							'tooltip' => '',
							'error' => array(0 => array('checked' => true,
													 'text' => 'error_agree',
											 		  //'information_id' => $this->config->get('config_checkout_id')
													  )),
							'type' => 'checkbox',
							'refresh' => '0',
							'value' => 0, 
							'custom' => 0,
							'class' => '',
							'sort_order' => 1,
					)
					
					
				)
			)
	),
	'account' => array(
		'register' => array(
			'display' => 1,
			'login' => array(),
			'payment_address' => array(
				'display' => 1,
				'fields' => array(
					'firstname' => array('display' => 1, 
										 'require' => 1,
										 'value' => ''
										 ),
					'lastname' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''													
										 ), 
					'email' => array(	 'display' => 1, 
										 'require' => 1,
										 'error' => array(0 => array('min_length' => 3, 
															 'max_length' => 96, 
															 'text' => array(1 => 'Sorry, but you need to have the length of the text more then 3 and less then 96 ')),
												  
												  2 => array('email_exists' => true, 
															 'text' => 'error_exists'),
												  3 => array('not_empty' => true, 
															 'text' => 'error_email')
												  ),
										 'value' => ''
										 ),
					'email_confirm' => array(	 
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''
										 ),
					'heading' => array(
										 'display' => 1,
										 'value' => ''														
										 ),
					'telephone' => array(
										 'display' => 1, 
										 'require' => 0,
										 'value' => ''	
										 ),
					'fax' => array(
										 'display' => 1, 
										 'require' => 0,
										 'value' => ''	
										 ),
					'password' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'confirm' => array(
									     'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'company' => array(
										 'display' => 1, 
										 'require' => 0,
										 'value' => ''	
										 ),
					'customer_group_id' => array(
										 'display' => 1, 
										 'require' => 0,
										 'value' => ''	
										 ),
					'address_1' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'address_2' => array(
										 'display' => 0, 
										 'require' => 0,
										 'value' => ''	
										 ),
					'city' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'postcode' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'country_id' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'zone_id' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'newsletter' => array( 
										 'display' => 1, 
										 'require' => 0,
										 'value' => '0'
										 ),
					'shipping_address' => array( 
										 'display' => 1,
										 'value' => 1
										 ),
					'agree' => array( 
										 'display' => 1, 
										 'require' => 1,
										 'value' => '0'
										 )
					
				)
			),
			'shipping_address' => array(
				'display' => 1,
				'require' => 0,
				'fields' => array(
					'firstname' => array( 
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'lastname' => array(
										 'display' => 0, 
										 'require' => 0,
										 'value' => ''	
										 ),
					'company' => array(
										 'display' => 1, 
										 'require' => 0,
										 'value' => ''	
										 ),
					'address_1' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'address_2' => array(
										 'display' => 0, 
										 'require' => 0,
										 'value' => ''	
										 ),
					'city' => array( 
										 'display' => 1, 
										 'require' => 0,
										 'value' => ''	
										 ),
					'postcode' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'country_id' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'zone_id' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 )
					)
			),
			'shipping_method' => array(
					 											  
					  ),
			'payment_method' =>array(
				
					  ),
			'cart' => array(
					  'display' => 1,
					  'option' => array(
							'voucher' => array(
										'display' => 1,
										 'value' => ''	
							),
							'coupon' => array(
										'display' => 1,
										 'value' => ''	
							),
							'reward' => array(
										'display' => 1,
										 'value' => ''	
							)
						),
					  'columns' => array(
							 'image' => 1,
							 'name' => 1,
							 'model' => 0,
							 'quantity' => 1,
							 'price' => 1,
							 'total' => 1
								)
			),										
			'confirm' => array(
					  'display' => 1,
					  'fields' => array(
					  		'comment' => array( 
								 'display' => 1, 
								 'require' => 0,
								 'value' => ''	
							 ),
							'agree' => array( 
								 'display' => 1, 
								 'require' => 1,
								 'value' => ''	
							 )
					  )	   
			)
		),
		'guest' => array(
			'display' => 1,
			'login' => array(),
			'payment_address' => array(
				'display' => 1,
				'fields' => array(
					'firstname' => array('display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'lastname' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''														
										 ), 
					'email' => array(	 'display' => 1, 
										 'require' => 1,
										 'error' => array(0 => array('min_length' => 3, 
															 'max_length' => 96, 
															 'text' => array(1 => 'Sorry, but you need to have the length of the text more then 3 and less then 96 ')),
												  1 => array('regex' => '/^[^\@]+@.*\.[a-z]{2,6}$/i', 
															 'text' => 'error_email'),
												  2 => array('not_empty' => true, 
															 'text' => 'error_email')
												  ),
										 'value' => ''	
										 ),
					'email_confirm' => array(	 
										 'display' => 1, 
										 'require' => 1,
										  'value' => ''
										 ),
					'heading' => array(
										 'display' => 1,
										 'value' => ''														
										 ),
					'telephone' => array(
										 'display' => 1, 
										 'require' => 0,
										 'value' => ''	
										 ),
					'fax' => array(
										 'display' => 1, 
										 'require' => 0,
										 'value' => ''	
										 ),
					'company' => array(
										 'display' => 1, 
										 'require' => 0,
										 'value' => ''	
										 ),
					'customer_group_id' => array(
										 'display' => 1, 
										 'require' => 0,
										 'value' => ''	
										 ),
					'address_1' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'address_2' => array(
										 'display' => 0, 
										 'require' => 0,
										 'value' => ''	
										 ),
					'city' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'postcode' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'country_id' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'zone_id' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'shipping_address' => array( 
										 'display' => 1,
										 'value' => 1
										 ),
					'agree' => array( 
										 'display' => 1, 
										 'require' => 1,
										 'value' => '0'
										 )
				)
			),
			'shipping_address' => array(
				'display' => 1,
				'require' => 0,
				'fields' => array(
					'firstname' => array( 
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'lastname' => array(
										 'display' => 0, 
										 'require' => 0,
										 'value' => ''	
										 ),
					'company' => array(
										 'display' => 1, 
										 'require' => 0,
										 'value' => ''	
										 ),
					'address_1' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''
										 ),
					'address_2' => array(
										 'display' => 0, 
										 'require' => 0,
										 'value' => ''
										 ),
					'city' => array( 
										 'display' => 1, 
										 'require' => 0,
										 'value' => ''
										 ),
					'postcode' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''
										 ),
					'country_id' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''
										 ),
					'zone_id' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''
										 )
					)
			),
			'shipping_method' =>array( 
					  
			),
			'payment_method' =>array(
		
			),
			'cart' => array(
					  'display' => 1,
					  'option' => array(
							'voucher' => array(
										'display' => 1,
										 'value' => ''
							),
							'coupon' => array(
										'display' => 1,
										 'value' => ''
							),
							'reward' => array(
										'display' => 1,
										 'value' => ''
							)
						),
					  'columns' => array(
							 'image' => 1,
							 'name' => 1,
							 'model' => 0,
							 'quantity' => 1,
							 'price' => 1,
							 'total' => 1
								)
			),										
			'confirm' => array(
					  'display' => 1,
					  'fields' => array(
					  		'comment' => array( 
								 'display' => 1, 
								 'require' => 0,
								 'value' => ''	
							 ),
							'agree' => array( 
								 'display' => 1, 
								 'require' => 1,
								 'value' => ''	
							 )
					  )	   
			)
		),
		'logged'=> array(
			'login' => array(),
			'payment_address' => array(
				'display' => 1,
				'fields' => array(
					'firstname' => array('display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'lastname' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''															
										 ),
					'company' => array(
										 'display' => 1, 
										 'require' => 0,
										 'value' => ''	
										 ),
					'address_1' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'address_2' => array(
										 'display' => 0, 
										 'require' => 0,
										 'value' => ''	
										 ),
					'city' => array(
										 'display' => 1, 
										 'require' => 0,
										 'value' => ''	
										 ),
					'postcode' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''	
										 ),
					'country_id' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''
										 ),
					'zone_id' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''
										 ),
					'shipping_address' => array( 
										 'display' => 1,
										 'value' => 1
										 ),
					'address_id' => array()
				)
			),
			'shipping_address' => array(
				'display' => 1,
				'require' => 0,
				'fields' => array(
					'firstname' => array( 
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''
										 ),
					'lastname' => array(
										 'display' => 0, 
										 'require' => 0,
										 'value' => ''
										 ),
					'company' => array(
										 'display' => 1, 
										 'require' => 0,
										 'value' => ''
										 ),
					'address_1' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''
										 ),
					'address_2' => array(
										 'display' => 0, 
										 'require' => 0,
										 'value' => ''
										 ),
					'city' => array( 
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''
										 ),
					'postcode' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''
										 ),
					'country_id' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''
										 ),
					'zone_id' => array(
										 'display' => 1, 
										 'require' => 1,
										 'value' => ''
										 )
					)
			),
			'shipping_method' =>array(
					  
			),
			'payment_method' =>array(

			),
			'cart' => array(
					  'display' => 1,
					  'option' => array(
							'voucher' => array(
										'display' => 1,
										'value' => ''
							),
							'coupon' => array(
										'display' => 1,
										'value' => ''
							),
							'reward' => array(
										'display' => 1,
										'value' => ''
							)
						),
					  'columns' => array(
							 'image' => 1,
							 'name' => 1,
							 'model' => 0,
							 'quantity' => 1,
							 'price' => 1,
							 'total' => 1
								)
			),										
			'confirm' => array(
					  'display' => 1,
					  'fields' => array(
					  		'comment' => array( 
								 'display' => 1, 
								 'require' => 0,
								 'value' => ''
							 ),
							'agree' => array( 
								 'display' => 1, 
								 'require' => 1,
								 'value' => ''
							 )
					  )	   
			)
		)
	)
);