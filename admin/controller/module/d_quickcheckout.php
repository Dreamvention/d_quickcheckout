<?php
/*
 *	location: admin/controller
 *  author: dreamvention
 */

class ControllerModuleDQuickcheckout extends Controller 
{
	private $error = array(); 
	private $texts = array('title', 'tooltip', 'description', 'text');

	private $id = 'd_quickcheckout';
	private $sub_id = '';
	private $route  = 'module/d_quickcheckout';
	private $mbooth  = 'mbooth_d_quickcheckout.xml';
	private $data = array();

	public function index() {   
		$this->load->language($this->route);
		$this->load->model('setting/setting');
		
		//stores
		if (isset($this->request->get['store_id'])) { 
			$store_id = $this->request->get['store_id']; 
		}else{  
			$store_id = 0;
		}
		
		//save settings	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->session->data['success'] = $this->language->get('text_success');
			unset($this->session->data['aqc_settings']);

			if(isset($this->request->post[$store_id]['general']['settings']['value']))
			{
				$settings = str_replace("amp;", "", urldecode($this->request->post[$store_id]['general']['settings']['bulk']));
				parse_str($settings, $this->request->post );	
			}
			
			$this->model_setting_setting->editSetting($this->id, $this->request->post, $store_id);

			if(!isset($this->request->post['save'])){
				$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		//scripts
		$this->document->addLink('//fonts.googleapis.com/css?family=PT+Sans:400,700,700italic,400italic&subset=latin,cyrillic-ext,latin-ext,cyrillic', "stylesheet");
		$this->document->addStyle('view/stylesheet/shopunity/bootstrap.css');

		$this->document->addScript('view/javascript/shopunity/tinysort/jquery.tinysort.min.js');	
		$this->document->addScript('view/javascript/shopunity/bootstrap-sortable.js');
		$this->document->addScript('view/javascript/shopunity/bootstrap-slider/js/bootstrap-slider.js');
		$this->document->addStyle('view/javascript/shopunity/bootstrap-slider/css/slider.css');

		$this->document->addStyle('view/stylesheet/d_quickcheckout.css');

		//languages
		$this->document->setTitle($this->language->get('heading_title_main'));
		$this->data['heading_title'] = $this->language->get('heading_title_main');
		$this->data['text_edit'] = $this->language->get('text_edit');
		$this->data['id'] = $this->id;
		$this->data['version'] = $this->get_version();
		$this->data['token'] =  $this->session->data['token'];
		$this->data['route'] = $this->route;
		$this->data['store_id'] = $store_id;
		$this->data['shopunity'] = $this->check_shopunity();
		$this->data['module_link'] = $this->url->link($this->route, 'token=' . $this->session->data['token'], 'SSL');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_display'] = $this->language->get('text_display');
		$this->data['text_require'] = $this->language->get('text_require');
		$this->data['text_always_show'] = $this->language->get('text_always_show');
		$this->data['text_enable'] = $this->language->get('text_enable');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_register'] = $this->language->get('text_register');
		$this->data['text_guest'] = $this->language->get('text_guest');
		$this->data['text_logged_in'] = $this->language->get('text_logged_in');
		$this->data['text_guest'] = $this->language->get('text_guest');
		$this->data['text_register'] = $this->language->get('text_register');
		$this->data['text_logged_in'] = $this->language->get('text_logged_in');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['text_input_radio'] = $this->language->get('text_input_radio');	
		$this->data['text_input_select'] = $this->language->get('text_input_select');
		$this->data['text_row'] = $this->language->get('text_row');
		$this->data['text_block'] = $this->language->get('text_block');
		$this->data['text_popup'] = $this->language->get('text_popup');
		$this->data['text_width'] = $this->language->get('text_width');
		$this->data['text_height'] = $this->language->get('text_height');

		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_limit'] = $this->language->get('entry_limit');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		// Tabs
		$this->data['text_home'] = $this->language->get('text_home');
		$this->data['text_general'] = $this->language->get('text_general');
		$this->data['text_login'] = $this->language->get('text_login');
		$this->data['text_payment_address'] = $this->language->get('text_payment_address');	
		$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$this->data['text_payment_method'] = $this->language->get('text_payment_method');
		$this->data['text_cart'] = $this->language->get('text_cart');
		$this->data['text_confirm'] = $this->language->get('text_confirm');
		$this->data['text_payment'] = $this->language->get('text_payment');
		$this->data['text_design'] = $this->language->get('text_design');
		$this->data['text_analytics'] = $this->language->get('text_analytics');

		// Home
		$this->data['text_intro_home'] = $this->language->get('text_intro_home');
		$this->data['text_intro_general'] = $this->language->get('text_intro_general');
		$this->data['text_intro_login'] = $this->language->get('text_intro_login');
		$this->data['text_intro_payment_address'] = $this->language->get('text_intro_payment_address');
		$this->data['text_intro_shipping_address'] = $this->language->get('text_intro_shipping_address');
		$this->data['text_intro_shipping_method'] = $this->language->get('text_intro_shipping_method');
		$this->data['text_intro_payment_method'] = $this->language->get('text_intro_payment_method');
		$this->data['text_intro_confirm'] = $this->language->get('text_intro_confirm');
		$this->data['text_intro_design'] = $this->language->get('text_intro_design');
		$this->data['text_intro_plugins'] = $this->language->get('text_intro_plugins');
		$this->data['text_intro_analytics'] = $this->language->get('text_intro_analytics');	

		// General
		$this->data['entry_general_enable'] = $this->language->get('entry_general_enable');
		$this->data['help_general_enable'] = $this->language->get('help_general_enable');
		$this->data['entry_general_default_option'] = $this->language->get('entry_general_default_option');
		$this->data['help_general_default_option'] = $this->language->get('help_general_default_option');
		$this->data['entry_general_main_checkout'] = $this->language->get('entry_general_main_checkout');
		$this->data['help_general_main_checkout'] = $this->language->get('help_general_main_checkout');
		$this->data['entry_general_clear_session'] = $this->language->get('entry_general_clear_session');
		$this->data['help_general_clear_session'] = $this->language->get('help_general_clear_session');
		$this->data['entry_general_login_refresh'] = $this->language->get('entry_general_login_refresh');
		$this->data['help_general_login_refresh'] = $this->language->get('help_general_login_refresh');
		$this->data['entry_general_default_email'] = $this->language->get('entry_general_default_email');
		$this->data['help_general_default_email'] = $this->language->get('help_general_default_email');
		$this->data['entry_general_version'] = $this->language->get('entry_general_version');
		$this->data['help_general_version'] = $this->language->get('help_general_version');
		$this->data['button_version_check'] = $this->language->get('button_version_check');
		$this->data['entry_general_debug'] = $this->language->get('entry_general_debug');
		$this->data['help_general_debug'] = $this->language->get('help_general_debug');
		$this->data['entry_general_min_order'] = $this->language->get('entry_general_min_order');
		$this->data['help_general_min_order'] = $this->language->get('help_general_min_order');
		$this->data['text_value_min_order'] = $this->language->get('text_value_min_order');
		$this->data['entry_general_min_quantity'] = $this->language->get('entry_general_min_quantity');
		$this->data['help_general_min_quantity'] = $this->language->get('help_general_min_quantity');
		$this->data['text_value_min_quantity'] = $this->language->get('text_value_min_quantity');
		$this->data['entry_general_trigger'] = $this->language->get('entry_general_trigger');
		$this->data['help_general_trigger'] = $this->language->get('help_general_trigger');
		$this->data['text_position_module'] = $this->language->get('text_position_module');
		$this->data['help_position_module'] = $this->language->get('help_position_module');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['entry_general_settings'] = $this->language->get('entry_general_settings');
		$this->data['text_general_settings_value'] = $this->language->get('text_general_settings_value');
		$this->data['help_general_settings'] = $this->language->get('help_general_settings');	
		
		//social login
		$this->data['text_social_login_required'] = $this->language->get('text_social_login_required');
		$this->data['entry_socila_login_style'] = $this->language->get('entry_socila_login_style');
		$this->data['help_socila_login_style'] = $this->language->get('help_socila_login_style');
		$this->data['text_icons'] = $this->language->get('text_icons');
		$this->data['text_small'] = $this->language->get('text_small');
		$this->data['text_medium'] = $this->language->get('text_medium');		
		$this->data['text_large'] = $this->language->get('text_large');
		$this->data['text_huge'] = $this->language->get('text_huge');
		$this->data['button_social_login_edit'] = $this->language->get('button_social_login_edit');
		$this->data['link_social_login_edit'] = $this->url->link('module/d_social_login', 'token=' . $this->session->data['token'] . '&store_id='.$store_id, 'SSL');
		
		//Payment address
		$this->data['entry_payment_address_display'] = $this->language->get('entry_payment_address_display');
		$this->data['help_payment_address_display'] = $this->language->get('help_payment_address_display');

		//Shipping address
		$this->data['entry_shipping_address_display'] = $this->language->get('entry_shipping_address_display');
		$this->data['help_shipping_address_display'] = $this->language->get('help_shipping_address_display');
		
		//Shipping method
		$this->data['entry_shipping_method_display'] = $this->language->get('entry_shipping_method_display');	
		$this->data['help_shipping_method_display'] = $this->language->get('help_shipping_method_display');
		$this->data['entry_shipping_method_display_options'] = $this->language->get('entry_shipping_method_display_options');
		$this->data['help_shipping_method_display_options'] = $this->language->get('help_shipping_method_display_options');	
		$this->data['entry_shipping_method_display_title'] = $this->language->get('entry_shipping_method_display_title');
		$this->data['help_shipping_method_display_title'] = $this->language->get('help_shipping_method_display_title');	
		$this->data['entry_shipping_method_input_style'] = $this->language->get('entry_shipping_method_input_style');	
		$this->data['help_shipping_method_input_style'] = $this->language->get('help_shipping_method_input_style');
		$this->data['entry_shipping_method_default_option'] = $this->language->get('entry_shipping_method_default_option');
		$this->data['help_shipping_method_default_option'] = $this->language->get('help_shipping_method_default_option');
		
		//Payment method
		$this->data['entry_payment_method_display'] = $this->language->get('entry_payment_method_display');
		$this->data['help_payment_method_display'] = $this->language->get('help_payment_method_display');
		$this->data['entry_payment_method_display_options'] = $this->language->get('entry_payment_method_display_options');
		$this->data['help_payment_method_display_options'] = $this->language->get('help_payment_method_display_options');
		$this->data['entry_payment_method_display_images'] = $this->language->get('entry_payment_method_display_images');
		$this->data['help_payment_method_display_images'] = $this->language->get('help_payment_method_display_images');
		$this->data['entry_payment_method_display_title'] = $this->language->get('entry_payment_method_display_title');
		$this->data['help_payment_method_display_title'] = $this->language->get('help_payment_method_display_title');
		$this->data['entry_payment_method_input_style'] = $this->language->get('entry_payment_method_input_style');
		$this->data['help_payment_method_input_style'] = $this->language->get('help_payment_method_input_style');
		$this->data['entry_payment_method_default_option'] = $this->language->get('entry_payment_method_default_option');
		$this->data['help_payment_method_default_option'] = $this->language->get('help_payment_method_default_option');
		
		//Cart
		$this->data['entry_cart_display'] = $this->language->get('entry_cart_display');
		$this->data['help_cart_display'] = $this->language->get('help_cart_display');
		$this->data['entry_cart_columns_image'] = $this->language->get('entry_cart_columns_image');
		$this->data['entry_cart_columns_name'] = $this->language->get('entry_cart_columns_name');
		$this->data['entry_cart_columns_model'] = $this->language->get('entry_cart_columns_model');
		$this->data['entry_cart_columns_quantity'] = $this->language->get('entry_cart_columns_quantity');
		$this->data['entry_cart_columns_price'] = $this->language->get('entry_cart_columns_price');
		$this->data['entry_cart_columns_total'] = $this->language->get('entry_cart_columns_total');
		$this->data['entry_cart_option_coupon'] = $this->language->get('entry_cart_option_coupon');
		$this->data['help_cart_option_coupon'] = $this->language->get('help_cart_option_coupon');
		$this->data['entry_cart_option_voucher'] = $this->language->get('entry_cart_option_voucher');
		$this->data['help_cart_option_voucher'] = $this->language->get('help_cart_option_voucher');
		$this->data['entry_cart_option_reward'] = $this->language->get('entry_cart_option_reward');
		$this->data['help_cart_option_reward'] = $this->language->get('help_cart_option_reward');

		//Design
		$this->data['entry_design_theme'] = $this->language->get('entry_design_theme');
		$this->data['help_design_theme'] = $this->language->get('help_design_theme');
		$this->data['entry_design_field'] = $this->language->get('entry_design_field');
		$this->data['help_design_field'] = $this->language->get('help_design_field');
		$this->data['entry_design_login_option'] = $this->language->get('entry_design_login_option');
		$this->data['help_design_login_option'] = $this->language->get('help_design_login_option');
		$this->data['entry_design_login'] = $this->language->get('entry_design_login');
		$this->data['help_design_login'] = $this->language->get('help_design_login');
		$this->data['entry_design_address'] = $this->language->get('entry_design_address');
		$this->data['help_design_address'] = $this->language->get('help_design_address');
		$this->data['entry_design_cart_image_size'] = $this->language->get('entry_design_cart_image_size');
		$this->data['help_design_cart_image_size'] = $this->language->get('help_design_cart_image_size');
		$this->data['entry_design_max_width'] = $this->language->get('entry_design_max_width');
		$this->data['help_design_max_width'] = $this->language->get('help_design_max_width');
		$this->data['entry_design_uniform'] = $this->language->get('entry_design_uniform');
		$this->data['help_design_uniform'] = $this->language->get('help_design_uniform');	
		$this->data['entry_design_only_quickcheckout'] = $this->language->get('entry_design_only_quickcheckout');
		$this->data['help_design_only_quickcheckout'] = $this->language->get('help_design_only_quickcheckout');
		$this->data['entry_design_column'] = $this->language->get('entry_design_column');
		$this->data['help_design_column'] = $this->language->get('help_design_column');
		$this->data['help_payment_address'] = $this->language->get('help_payment_address');
		$this->data['help_shipping_address'] = $this->language->get('help_shipping_address');
		$this->data['help_shipping_method'] = $this->language->get('help_shipping_method');
		$this->data['help_payment_method'] = $this->language->get('help_payment_method');
		$this->data['help_cart'] = $this->language->get('help_cart');
		$this->data['help_payment'] = $this->language->get('help_payment');
		$this->data['help_confirm'] = $this->language->get('help_confirm');
		$this->data['entry_design_custom_style'] = $this->language->get('entry_design_custom_style');
		$this->data['help_design_custom_style'] = $this->language->get('help_design_custom_style');

		//Plugins
		$this->data['text_plugins'] = $this->language->get('text_plugins');

		$this->data['action'] = $this->url->link($this->route, 'token=' . $this->session->data['token'] . '&store_id='.$store_id, 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		// breadcrumbs
  		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_main'),
			'href'      => $this->url->link($this->route, 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		//shopunity
		// if(!$this->check_shopunity()){
		// 	$this->data['error_warning'] =  $this->language->get('error_shopunity_required');
		// }
		//extra positions
		if (!file_exists(DIR_CATALOG.'../vqmod/xml/vqmod_extra_positions.xml')) {
       		$this->data['positions_needed'] = $this->language->get('positions_needed');
        }

        //settings
        $settings = array();
        if ($this->model_setting_setting->getSetting($this->id, $store_id))
		{ 
			$settings = $this->model_setting_setting->getSetting($this->id, $store_id);
		}

		$this->config->load($this->id);
		$config[$this->id] = $this->config->get($this->id.'_settings');
		$config[$this->id]['general']['default_email'] = $this->config->get('config_email');
		$config[$this->id]['step']['payment_address']['fields']['agree']['information_id'] = $this->config->get('config_account_id');
		$config[$this->id]['step']['payment_address']['fields']['agree']['error'][0]['information_id'] = $this->config->get('config_account_id');
		$config[$this->id]['step']['confirm']['fields']['agree']['information_id'] = $this->config->get('config_checkout_id');
		$config[$this->id]['step']['confirm']['fields']['agree']['error'][0]['information_id'] = $this->config->get('config_checkout_id');
		
		$settings = $this->array_merge_recursive_distinct($config, $settings);

		$this->data[$this->id] = array();
		if (isset($this->request->post[$this->id]))
		{
			$this->data[$this->id] = $this->request->post[$this->id];

		} elseif(isset($settings[$this->id])) 
		{
			$this->data[$this->id] = $settings[$this->id]; 
		}
	
		//modules
		$this->data[$this->id.'_modules'] = array();
		if (isset($this->request->post[$this->id.'_module']))
		{
			$this->data[$this->id.'_modules'] = $this->request->post[$this->id.'_module'];

		} elseif(isset($settings[$this->id.'_module']))
		{
			$this->data[$this->id.'_modules'] = $settings[$this->id.'_module'];	
		}

		//These are default settings (located in system/config/d_quickcheckout.php)
		// $settings = $this->config->get('quickcheckout_settings');
		// if(empty($settings)){
		// 	$this->config->load('quickcheckout_settings');
		// 	$settings = $this->config->get('quickcheckout_settings');
		// }

		//System settings
	
		// if(!empty($this->data[$this->id])){
		// 	$this->data[$this->id] = $this->array_merge_recursive_distinct($settings, $this->data['quickcheckout']);
		// }else{
		// 	$this->data[$this->id] = $settings[$this->id];
		// }

		$this->data[$this->id]['general']['store_id'] = $store_id;
		
		$lang = $this->language_merge($this->data[$this->id]['step']['payment_address']['fields'], $this->texts);
		$this->data[$this->id]['step']['payment_address']['fields'] = $this->array_merge_recursive_distinct($this->data[$this->id]['step']['payment_address']['fields'], $lang);
		
		$lang = $this->language_merge($this->data[$this->id]['step']['shipping_address']['fields'], $this->texts);
		$this->data[$this->id]['step']['shipping_address']['fields'] = $this->array_merge_recursive_distinct($this->data[$this->id]['step']['shipping_address']['fields'], $lang);
		
		$lang = $this->language_merge($this->data[$this->id]['step']['confirm']['fields'], $this->texts);
		$this->data[$this->id]['step']['confirm']['fields'] = $this->array_merge_recursive_distinct($this->data[$this->id]['step']['confirm']['fields'], $lang);
		
		//Get Design Steps
		$this->data['steps'] = array();

		


		foreach($this->data[$this->id]['step'] as $step => $value){
			if(isset($value['column'])){
				$this->data['steps'][$step] = array('column' => $value['column'], 'row' => $value['row']);
			}	
		}

		$this->data['steps']['payment_address']['icon'] = 'book'; 
        $this->data['steps']['shipping_address']['icon'] = 'book';
        $this->data['steps']['shipping_method']['icon'] = 'truck';
        $this->data['steps']['payment_method']['icon'] = 'credit-card';
        $this->data['steps']['cart']['icon'] = 'shopping-cart';
        $this->data['steps']['payment']['icon'] = 'money';
        $this->data['steps']['confirm']['icon'] = 'check'; 
        unset($this->data['steps']['login']); 


        $sort_order = array(); 
		foreach ($this->data['steps'] as $key => $value) {

      			$sort_order[$key]['column'] = $value['column'];
      			$sort_order[$key]['row'] = $value['row'];
    	}
		array_multisort($sort_order, SORT_ASC, $this->data['steps'] );
		

		//Get Shipping methods
		$this->data['shipping_methods'] = $this->get_shipping_methods();

		//Get Payment methods
		$this->data['payment_methods'] = $this->get_payment_methods();
		
		//Get designes
		$this->data['themes'] = $this->get_themes();
		
		//Get stores
		$this->data['stores'] = $this->get_stores();
		
		//Social login
		$this->data['social_login'] = false;
		if($this->check_d_social_login()){
			$this->data['social_login'] = true;
			$this->load->language('module/d_social_login');
			
			$this->config->load($this->check_d_social_login());
			$social_login_settings = $this->config->get('d_social_login_module');
			$social_login_settings = $social_login_settings['setting'];

			if($social_login_settings){ 

				if(!isset($this->data[$this->id]['general']['social_login'])){
					$this->data[$this->id]['general']['social_login'] = array();
				}

				$this->data[$this->id]['general']['social_login'] = $this->array_merge_recursive_distinct($social_login_settings, $this->data[$this->id]['general']['social_login']);
			}
			$sort_order = array(); 
			foreach ($this->data[$this->id]['general']['social_login']['providers'] as $key => $value) {
				if(isset($value['sort_order'])){
	      			$sort_order[$key] = $value['sort_order'];
				}else{
					unset($providers[$key]);
				}
	    	}
			array_multisort($sort_order, SORT_ASC, $this->data[$this->id]['general']['social_login']['providers']);
			
			foreach($this->data[$this->id]['general']['social_login']['providers'] as $provoder){
				if(isset($provoder['id'])){
					$this->data['text_'.$provoder['id']] = $this->language->get('text_'.$provoder['id']);
				}
			}
			
		}

		$this->data['socila_login_styles'] = array( 'icons', 'small', 'medium', 'large', 'huge');
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if(preg_match("/1.5/i", VERSION)){
			$this->template = $this->route.'.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
			$this->data['column_left'] = '';
					
			$this->response->setOutput($this->render());
		}else{
			$this->data['header'] = $this->load->controller('common/header');
			$this->data['column_left'] = $this->load->controller('common/column_left');
			$this->data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view($this->route.'.tpl', $this->data));
		}
		
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', $this->route)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	private function get_shipping_methods() {
		$shipping_methods = glob(DIR_APPLICATION . 'controller/shipping/*.php');
		$result = array();
		foreach ($shipping_methods as $shipping){
			$shipping = basename($shipping, '.php');
			$this->load->language('shipping/' . $shipping);
			$result[] = array(
				'code' => $shipping,
				'title' => $this->language->get('heading_title')
			);
		}
		return $result;
	}

	private function get_payment_methods(){
		$payment_methods = glob(DIR_APPLICATION . 'controller/payment/*.php');
		$result = array();
		foreach ($payment_methods as $payment){
			$payment = basename($payment, '.php');
			$this->load->language('payment/' . $payment);
			$result[] = array(
				'code' => $payment,
				'title' => $this->language->get('heading_title')
			);
		}
		return $result;
	}

	private function get_themes(){
		$dir = DIR_CATALOG.'view/theme/default/stylesheet/d_quickcheckout/theme';
		$files = scandir($dir);
		$result = array();
		foreach($files as $file){
			if(strlen($file) > 6){
				$result[] = substr($file, 0, -4);
			}
		}
		return $result;
	}

	private function get_stores(){
		$this->load->model('setting/store');
		$stores = $this->model_setting_store->getStores();
		$result = array();
		if($stores){
			$result[] = array(
				'store_id' => 0, 
				'name' => $this->config->get('config_name')
			);
			foreach ($stores as $store) {
				$result[] = array(
					'store_id' => $store['store_id'],
					'name' => $store['name']	
				);
			}	
		}
		return $result;
	}
	
	public function check_d_social_login(){
		$result = false;
			if($this->isInstalled('d_social_login')){
				$full = DIR_SYSTEM . "config/d_social_login.php";
				$light = DIR_SYSTEM . "config/d_social_login_lite.php"; 
				if (file_exists($full)) { 
					$result = 'd_social_login';
				} elseif (file_exists($light)) {
					$result =  'd_social_login_lite';
				}
			}
		return $result;
	}
	
	public function install() {
		  $this->load->model('setting/setting');
		  $from = str_replace("system", "vqmod/xml", DIR_SYSTEM) . "a_vqmod_d_quickcheckout.xml_"; 
		  $to = str_replace("system", "vqmod/xml", DIR_SYSTEM) . "a_vqmod_d_quickcheckout.xml";
		  if (file_exists($from)) rename($from, $to);
		  $this->version_check(1);
		  
	}
		 
	public function uninstall() {
		  $this->load->model('setting/setting');
		  $from = str_replace("system", "vqmod/xml", DIR_SYSTEM) . "a_vqmod_d_quickcheckout.xml"; 
		  $to = str_replace("system", "vqmod/xml", DIR_SYSTEM) . "a_vqmod_d_quickcheckout.xml_";
		  if (file_exists($from)) rename($from, $to);
		  $this->version_check(0);
		  
	}
	
	public function language_merge($array, $texts){
		$this->load->model('catalog/information');
		$array_full = $array; 
		$result = array();
		foreach ($array as $key => $value){
			foreach ($texts as $text){
				if(isset($array_full[$text])){
					if(!is_array($array_full[$text])){
						$result[$text] = $this->language->get($array_full[$text]);	
					}else{
						if(isset($array_full[$text][(int)$this->config->get('config_language_id')])){
							$result[$text] = $array_full[$text][(int)$this->config->get('config_language_id')];
						}else{
							$result[$text] = current($array_full[$text]);
						}
					}
					if((strpos($result[$text], '%s') !== false) && isset($array_full['information_id'])){
						$information_info = $this->model_catalog_information->getInformation($array_full['information_id']);
						$result[$text] = sprintf($result[$text], $information_info['title']);	
					}
				}						
			}
			if(is_array($array_full[$key])){	
						$result[$key] = $this->language_merge($array_full[$key], $texts);	
			}
			
		}

		return $result;
		
	}
	
	public function array_merge_recursive_distinct( array &$array1, array &$array2 )
	{
	  $merged = $array1;	
	  foreach ( $array2 as $key => &$value )
		  {
			if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
			{
			  $merged [$key] = $this->array_merge_recursive_distinct ( $merged [$key], $value );
			}
			else
			{
			  $merged [$key] = $value;
			}
		  }
		
	  return $merged;
	}

	public function check_shopunity(){
		$file1 = DIR_SYSTEM . "mbooth/xml/mbooth_shopunity_admin.xml"; 
		$file2 = DIR_SYSTEM . "mbooth/xml/mbooth_shopunity_admin_patch.xml"; 
		if (file_exists($file1) || file_exists($file2)) { 
			return true;
		} else {
			return false;
		}

	}

	public function get_version(){
		$xml = file_get_contents(DIR_SYSTEM . 'mbooth/xml/' . $this->mbooth);

		$mbooth = new SimpleXMLElement($xml);

		return $mbooth->version ;
	}

	public function version_check($status = 1){
		$json = array();
		$this->load->language($this->route);
		$this->mboot_script_dir = DIR_SYSTEM . 'mbooth/xml/';
		$str = file_get_contents($this->mboot_script_dir . $this->mbooth);
		$xml = new SimpleXMLElement($str);
	
		$current_version = $xml->version ;
      
		if (isset($this->request->get['mbooth'])) { 
			$mbooth = $this->request->get['mbooth']; 
		} else { 
			$mbooth = $this->mbooth; 
		}

		$customer_url = HTTP_SERVER;
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE language_id = " . (int)$this->config->get('config_language_id') ); 
		$language_code = $query->row['code'];
		$ip = $this->request->server['REMOTE_ADDR'];

		$check_version_url = 'http://opencart.dreamvention.com/api/1/index.php?route=extension/check&mbooth=' . $mbooth . '&store_url=' . $customer_url . '&module_version=' . $current_version . '&language_code=' . $language_code . '&opencart_version=' . VERSION . '&ip='.$ip . '&status=' .$status;
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $check_version_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$return_data = curl_exec($curl);
		$return_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

      if ($return_code == 200) {
         $data = simplexml_load_string($return_data);
	
         if ((string) $data->version == (string) $current_version || (string) $data->version <= (string) $current_version) {
			 
           $json['success']   = $this->language->get('text_no_update') ;

         } elseif ((string) $data->version > (string) $current_version) {
			 
			$json['attention']   = $this->language->get('text_new_update');
				
			foreach($data->updates->update as $update){

				if((string) $update->attributes()->version > (string)$current_version){
					$version = (string)$update->attributes()->version;
					$json['update'][$version] = (string) $update[0];
				}
			}
         } else {
			 
            $json['error']   = $this->language->get('text_error_update');
         }
      } else { 
         $json['error']   =  $this->language->get('text_error_failed');

      }

      if (file_exists(DIR_SYSTEM.'library/json.php')) { 
         $this->load->library('json');
         $this->response->setOutput(Json::encode($json));
      } else {
         $this->response->setOutput(json_encode($json));
      }
   }

   public function isInstalled($code) {
		$extension_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `code` = '" . $this->db->escape($code) . "'");
		
		if($query->row) {
			return true;
		}else{
			return false;
		}	
	}
}
?>