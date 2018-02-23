<?php

/*
 *  location: admin/controller
 */

class ControllerExtensionModuleDQuickcheckout extends Controller {
    private $codename = 'd_quickcheckout';
    private $id = 'd_quickcheckout';
    private $route = 'extension/module/d_quickcheckout';
    private $config_file = '';
    private $prefix = '';
    private $sub_versions = array('lite', 'light', 'free');
    private $store_id = 0;
    private $error = array();

    public function __construct($registry) {
        parent::__construct($registry);

        $this->load->model('extension/module/d_quickcheckout');
        $this->load->language($this->route);
        $this->load->model($this->route);
        $this->load->model('setting/setting');
        $this->load->model('extension/d_opencart_patch/module');
        $this->load->model('extension/d_opencart_patch/url');
        $this->load->model('extension/d_opencart_patch/load');
        $this->load->model('extension/d_opencart_patch/user');
        $this->d_shopunity = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_shopunity.json'));
        $this->d_opencart_patch = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_opencart_patch.json'));
        $this->extension = json_decode(file_get_contents(DIR_SYSTEM.'library/d_shopunity/extension/'.$this->codename.'_lite.json'), true);
        $this->d_twig_manager = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_twig_manager.json'));

        if (isset($this->request->get['store_id'])) {
            $this->store_id = $this->request->get['store_id'];
        }
    }


    public function index() {

        if($this->d_shopunity){
            $this->load->model('extension/d_shopunity/mbooth');
            $this->model_extension_d_shopunity_mbooth->validateDependencies($this->codename);
            $this->model_extension_d_shopunity_mbooth->installDependencies($this->codename);
        }


        if($this->d_twig_manager){
            $this->load->model('extension/module/d_twig_manager');
            if(!$this->model_extension_module_d_twig_manager->isCompatible()){
                $this->model_extension_module_d_twig_manager->installCompatibility();
                $this->session->data['success'] = $this->language->get('success_twig_compatible');
                $this->response->redirect($this->model_extension_d_opencart_patch_url->getExtensionLink('module'));
            }
        }
        $this->model_extension_module_d_quickcheckout->installDatabase();

        $this->config_file = $this->model_extension_module_d_quickcheckout->getConfigFile($this->codename, $this->sub_verisons);

        //dependencies
        $this->load->language($this->route);
        $this->load->language($this->route . '_instruction');
        $this->load->model('extension/d_opencart_patch/setting');
        $this->load->model('extension/d_opencart_patch/extension');
        $this->load->model('extension/d_opencart_patch/modification');
        $this->load->model('extension/d_opencart_patch/url');
        $this->load->model('extension/d_opencart_patch/user');
        $this->load->model('extension/d_opencart_patch/load');
        $this->load->model('setting/setting');

        //save post
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $new_post=array();
            foreach ($this->request->post as $key => $value) {
                $new_post['module_'.$key] = $value;
            }
            if (isset($this->request->get['setting_id'])) {
                $this->model_extension_module_d_quickcheckout->editSetting($this->request->get['setting_id'], $this->request->post[$this->codename . '_setting']);
            }
            if($this->request->post[$this->codename . '_status']){
                $this->model_extension_d_opencart_patch_modification->setModification('d_quickcheckout.xml', 1);
            } else {
                $this->model_extension_d_opencart_patch_modification->setModification('d_quickcheckout.xml', 0);
            }
            $this->model_extension_d_opencart_patch_modification->refreshCache();
            $this->model_extension_d_opencart_patch_setting->editSetting($this->codename, $this->request->post, $this->store_id);
            $this->model_extension_d_opencart_patch_setting->editSetting('module_'.$this->codename, $new_post, $this->store_id);
            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->model_extension_d_opencart_patch_url->getExtensionLink('module'));
        }

        // styles and scripts
        $this->document->addStyle('view/javascript/d_quickcheckout/bootstrap.css');
        // sortable
      $this->document->addScript('view/javascript/d_rubaxa_sortable/sortable.js');
      $this->document->addStyle('view/javascript/d_rubaxa_sortable/sortable.css');
      $this->document->addScript('view/javascript/d_quickcheckout/jquery.tinysort.min.js');
      $this->document->addScript('view/javascript/d_quickcheckout/bootstrap-sortable.js');
      $this->document->addScript('view/javascript/d_quickcheckout/bootstrap-slider/js/bootstrap-slider.js');
      $this->document->addStyle('view/javascript/d_quickcheckout/bootstrap-slider/css/slider.css');
      $this->document->addScript('view/javascript/d_bootstrap_switch/js/bootstrap-switch.min.js');
      $this->document->addStyle('view/javascript/d_bootstrap_switch/css/bootstrap-switch.css');
      $this->document->addScript('view/javascript/d_quickcheckout/serializeObject/serializeObject.js');
        // $this->document->addStyle('view/stylesheet/d_social_login/styles.css');
      $this->document->addStyle('view/stylesheet/d_quickcheckout.css');


        // $this->document->addScript('view/javascript/shopunity/bootstrap-editable/bootstrap-editable.js');
        // $this->document->addStyle('view/stylesheet/shopunity/bootstrap-editable/bootstrap-editable.css');
        // Add more styles, links or scripts to the project is necessary
        // $this->document->addLink('//fonts.googleapis.com/css?family=PT+Sans:400,700,700italic,400italic&subset=latin,cyrillic-ext,latin-ext,cyrillic', "stylesheet");
        // $this->document->addStyle('view/stylesheet/shopunity/normalize.css');
        // $this->document->addScript('view/javascript/shopunity/tooltip/tooltip.js');


      $url = '';
      $data['module_link'] = $this->url->link($this->route, $this->model_extension_d_opencart_patch_user->getUrlToken(), 'SSL');

      if (isset($this->request->get['store_id'])) {
        $url .= '&store_id=' . $this->store_id;
    }

    if (isset($this->request->get['setting_id'])) {
        $url .= '&setting_id=' . $this->request->get['setting_id'];
    } elseif ($this->model_extension_module_d_quickcheckout->getCurrentSettingId($this->codename, $this->store_id)) {
        $url .= '&setting_id=' . $this->model_extension_module_d_quickcheckout->getCurrentSettingId($this->codename, $this->store_id);
    }

    if (isset($this->request->get['config'])) {
        $url .= '&config=' . $this->request->get['config'];
    }

        // Breadcrumbs
    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->model_extension_d_opencart_patch_url->link('common/dashboard')
    );

    $data['breadcrumbs'][] = array(
        'text'      => $this->language->get('text_module'),
        'href'      => $this->model_extension_d_opencart_patch_url->getExtensionLink('module')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title_main'),
        'href' => $this->model_extension_d_opencart_patch_url->link($this->route)
    );

        // Notification
    foreach ($this->error as $key => $error) {
        $data['error'][$key] = $error;
    }

        // Heading
    $this->document->setTitle($this->language->get('heading_title_main'));
    $data['heading_title'] = $this->language->get('heading_title_main');
    $data['text_edit'] = $this->language->get('text_edit');

        // Variable
    $data['id'] = $this->codename;
    $data['route'] = $this->route;
    $data['store_id'] = $this->store_id;
    $data['stores'] = $this->model_extension_module_d_quickcheckout->getStores();
    $data['support_email'] = $this->extension['support']['email'];
    $data['version'] = $this->extension['version'];
    $data['token'] = $this->model_extension_d_opencart_patch_user->getToken();
    $data['text_need_full_version'] = $this->language->get('text_need_full_version');

        // Tab
    $data['tab_setting'] = $this->language->get('tab_setting');

    $data['text_home'] = $this->language->get('text_home');
    $data['text_general'] = $this->language->get('text_general');
    $data['text_login'] = $this->language->get('text_login');
    $data['text_payment_address'] = $this->language->get('text_payment_address');
    $data['text_shipping_address'] = $this->language->get('text_shipping_address');
    $data['text_shipping_method'] = $this->language->get('text_shipping_method');
    $data['text_payment_method'] = $this->language->get('text_payment_method');
    $data['text_cart'] = $this->language->get('text_cart');
    $data['text_confirm'] = $this->language->get('text_confirm');
    $data['text_payment'] = $this->language->get('text_payment');
    $data['text_design'] = $this->language->get('text_design');
    $data['text_analytics'] = $this->language->get('text_analytics');

        // Button
    $data['button_save'] = $this->language->get('button_save');
    $data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
    $data['button_cancel'] = $this->language->get('button_cancel');
    $data['button_clear'] = $this->language->get('button_clear');
    $data['button_add'] = $this->language->get('button_add');
    $data['button_remove'] = $this->language->get('button_remove');

        // Text
    $data['text_enabled'] = $this->language->get('text_enabled');
    $data['text_disabled'] = $this->language->get('text_disabled');
    $data['text_enable'] = $this->language->get('text_enable');
    $data['text_select'] = $this->language->get('text_select');
    $data['text_none'] = $this->language->get('text_none');
    $data['text_title'] = $this->language->get('text_title');
    $data['text_description'] = $this->language->get('text_description');
    $data['help_title'] = $this->language->get('help_title');
    $data['help_description'] = $this->language->get('help_description');
    $data['text_icon'] = $this->language->get('text_icon');
    $data['help_icon'] = $this->language->get('help_icon');
    $data['text_yes'] = $this->language->get('text_yes');
    $data['text_no'] = $this->language->get('text_no');
    $data['text_display'] = $this->language->get('text_display');
    $data['text_require'] = $this->language->get('text_require');
    $data['text_always_show'] = $this->language->get('text_always_show');
    $data['text_defualt'] = $this->language->get('text_defualt');
    $data['text_register'] = $this->language->get('text_register');
    $data['text_guest'] = $this->language->get('text_guest');
    $data['text_logged'] = $this->language->get('text_logged');

    $data['text_content_top'] = $this->language->get('text_content_top');
    $data['text_content_bottom'] = $this->language->get('text_content_bottom');
    $data['text_column_left'] = $this->language->get('text_column_left');
    $data['text_column_right'] = $this->language->get('text_column_right');
    $data['text_input_radio'] = $this->language->get('text_input_radio');
    $data['text_input_select'] = $this->language->get('text_input_select');
    $data['text_input_list'] = $this->language->get('text_input_list');
    $data['text_row'] = $this->language->get('text_row');
    $data['text_block'] = $this->language->get('text_block');
    $data['text_popup'] = $this->language->get('text_popup');
    $data['text_width'] = $this->language->get('text_width');
    $data['text_height'] = $this->language->get('text_height');
    $data['text_type'] = $this->language->get('text_type');
    $data['entry_new_field'] = $this->language->get('entry_new_field');
    $data['text_custom_field'] = $this->language->get('text_custom_field');
    $data['help_new_field'] = $this->language->get('help_new_field');
    $data['button_new_field'] = $this->language->get('button_new_field');
    $data['help_maskedinput'] = $this->language->get('help_maskedinput');
    $data['text_probability'] = $this->language->get('text_probability');
    $data['text_create_setting'] = $this->language->get('text_create_setting');
    $data['text_create_setting_heading'] = $this->language->get('text_create_setting_heading');
    $data['text_create_setting_probability'] = $this->language->get('text_create_setting_probability');

        //action

    $data['action'] = $this->url->link($this->route, $this->model_extension_d_opencart_patch_user->getUrlToken() . $url, 'SSL');
    $data['update_compress_url'] = $this->model_extension_d_opencart_patch_url->ajax('extension/module/d_quickcheckout/updateCompress');

    $data['cancel'] = $this->model_extension_d_opencart_patch_url->link('marketplace/extension','type=module');

    if(VERSION >= '2.1.0.1'){
        $data['add_field'] = $this->url->link('customer/custom_field/add', $this->model_extension_d_opencart_patch_user->getUrlToken(), 'SSL');
        $data['custom_field'] = $this->url->link('customer/custom_field', $this->model_extension_d_opencart_patch_user->getUrlToken(), 'SSL');
    }else{
        $data['add_field'] = $this->url->link('sale/custom_field/add', $this->model_extension_d_opencart_patch_user->getUrlToken(), 'SSL');
        $data['custom_field'] = $this->url->link('sale/custom_field', $this->model_extension_d_opencart_patch_user->getUrlToken(), 'SSL');
    }

        //update
    $data['entry_update'] = sprintf($this->language->get('entry_update'), $data['version']);
    $data['button_update'] = $this->language->get('button_update');
    $data['compress_update'] = $this->language->get('compress_update');
    $data['update'] = $this->model_extension_module_d_quickcheckout->ajax($this->url->link($this->route . '/getUpdate', $this->model_extension_d_opencart_patch_user->getUrlToken(), 'SSL'));

        //debug
    $data['tab_debug'] = $this->language->get('tab_debug');
    $data['entry_debug'] = $this->language->get('entry_debug');
    $data['entry_debug_file'] = $this->language->get('entry_debug_file');
    $data['clear_debug_file'] = $this->model_extension_module_d_quickcheckout->ajax($this->url->link($this->route . '/clearDebugFile', $this->model_extension_d_opencart_patch_user->getUrlToken(), 'SSL'));

        //support
    $data['tab_support'] = $this->language->get('tab_support');
    $data['text_support'] = $this->language->get('text_support');
    $data['entry_support'] = $this->language->get('entry_support');
    $data['button_support_email'] = $this->language->get('button_support_email');

        //instruction
    $data['tab_instruction'] = $this->language->get('tab_instruction');
    $data['text_instruction'] = $this->language->get('text_instruction');

        // Home
    $data['text_intro_home'] = $this->language->get('text_intro_home');
    $data['text_intro_general'] = $this->language->get('text_intro_general');
    $data['text_intro_login'] = $this->language->get('text_intro_login');
    $data['text_intro_payment_address'] = $this->language->get('text_intro_payment_address');
    $data['text_intro_shipping_address'] = $this->language->get('text_intro_shipping_address');
    $data['text_intro_shipping_method'] = $this->language->get('text_intro_shipping_method');
    $data['text_intro_payment_method'] = $this->language->get('text_intro_payment_method');
    $data['text_intro_confirm'] = $this->language->get('text_intro_confirm');
    $data['text_intro_design'] = $this->language->get('text_intro_design');
    $data['text_intro_plugins'] = $this->language->get('text_intro_plugins');
    $data['text_intro_analytics'] = $this->language->get('text_intro_analytics');
    $data['entry_status'] = $this->language->get('entry_status');
    $data['entry_name'] = $this->language->get('entry_name');
    $data['help_name'] = $this->language->get('help_name');
    $data['entry_enable'] = $this->language->get('entry_enable');
    $data['help_enable'] = $this->language->get('help_enable');
    $data['entry_trigger'] = $this->language->get('entry_trigger');
    $data['help_trigger'] = $this->language->get('help_trigger');
    $data['help_view_shop'] = $this->language->get('help_view_shop');
    $data['help_view_setting'] = $this->language->get('help_view_setting');
    $data['help_average_time'] = $this->language->get('help_average_time');
    $data['help_average_rating'] = $this->language->get('help_average_rating');
    $data['text_intro_create_setting'] = $this->language->get('text_intro_create_setting');

        // General
    $data['entry_general_default_option'] = $this->language->get('entry_general_default_option');
    $data['help_general_default_option'] = $this->language->get('help_general_default_option');
    $data['entry_general_main_checkout'] = $this->language->get('entry_general_main_checkout');
    $data['help_general_main_checkout'] = $this->language->get('help_general_main_checkout');
    $data['entry_general_clear_session'] = $this->language->get('entry_general_clear_session');
    $data['help_general_clear_session'] = $this->language->get('help_general_clear_session');
    $data['entry_general_login_refresh'] = $this->language->get('entry_general_login_refresh');
    $data['help_general_login_refresh'] = $this->language->get('help_general_login_refresh');
    $data['entry_general_default_email'] = $this->language->get('entry_general_default_email');
    $data['help_general_default_email'] = $this->language->get('help_general_default_email');
    $data['entry_general_min_order'] = $this->language->get('entry_general_min_order');
    $data['help_general_min_order'] = $this->language->get('help_general_min_order');
    $data['text_value_min_order'] = $this->language->get('text_value_min_order');
    $data['entry_general_min_quantity'] = $this->language->get('entry_general_min_quantity');
    $data['help_general_min_quantity'] = $this->language->get('help_general_min_quantity');
    $data['text_value_min_quantity'] = $this->language->get('text_value_min_quantity');
    $data['entry_delete_setting'] = $this->language->get('entry_delete_setting');
    $data['text_confirm_delete_setting'] = $this->language->get('text_confirm_delete_setting');
    $data['button_delete_setting'] = $this->language->get('button_delete_setting');
    $data['entry_config_files'] = $this->language->get('entry_config_files');
    $data['entry_action_bulk_setting'] = $this->language->get('entry_action_bulk_setting');
    $data['help_action_bulk_setting'] = $this->language->get('help_action_bulk_setting');
    $data['entry_bulk_setting'] = $this->language->get('entry_bulk_setting');
    $data['help_bulk_setting'] = $this->language->get('help_bulk_setting');
    $data['button_create_bulk_setting'] = $this->language->get('button_create_bulk_setting');
    $data['button_save_bulk_setting'] = $this->language->get('button_save_bulk_setting');
    $data['entry_general_analytics_event'] = $this->language->get('entry_general_analytics_event');
    $data['help_general_analytics_event'] = $this->language->get('help_general_analytics_event');
    $data['warning_analytics_event'] = $this->language->get('warning_analytics_event');
    $data['entry_general_compress'] = $this->language->get('entry_general_compress');
    $data['help_general_compress'] = $this->language->get('help_general_compress');
    $data['entry_general_update_mini_cart'] = $this->language->get('entry_general_update_mini_cart');
    $data['help_general_update_mini_cart'] = $this->language->get('help_general_update_mini_cart');


        //social login
    $data['text_social_login_required'] = $this->language->get('text_social_login_required');
        // $data['entry_socila_login_style'] = $this->language->get('entry_socila_login_style');
        // $data['help_socila_login_style'] = $this->language->get('help_socila_login_style');
    $data['entry_social_login'] = $this->language->get('entry_social_login');
    $data['help_social_login'] = $this->language->get('help_social_login');
    $data['text_icons'] = $this->language->get('text_icons');
    $data['text_small'] = $this->language->get('text_small');
    $data['text_medium'] = $this->language->get('text_medium');
    $data['text_large'] = $this->language->get('text_large');
    $data['text_huge'] = $this->language->get('text_huge');
    $data['button_social_login_edit'] = $this->language->get('button_social_login_edit');
    $data['link_social_login_edit'] = $this->url->link('module/d_social_login', $this->model_extension_d_opencart_patch_user->getUrlToken() . '&store_id=' . $this->store_id, 'SSL');

        //Payment address
    $data['title_payment_address'] = $this->language->get('title_payment_address');
    $data['entry_payment_address_display'] = $this->language->get('entry_payment_address_display');
    $data['help_payment_address_display'] = $this->language->get('help_payment_address_display');

        //Shipping address
    $data['title_shipping_address'] = $this->language->get('title_shipping_address');
    $data['entry_shipping_address_display'] = $this->language->get('entry_shipping_address_display');
    $data['help_shipping_address_display'] = $this->language->get('help_shipping_address_display');

        //Shipping method
    $data['entry_shipping_method_display'] = $this->language->get('entry_shipping_method_display');
    $data['help_shipping_method_display'] = $this->language->get('help_shipping_method_display');
    $data['entry_shipping_method_display_options'] = $this->language->get('entry_shipping_method_display_options');
    $data['help_shipping_method_display_options'] = $this->language->get('help_shipping_method_display_options');
    $data['entry_shipping_method_display_title'] = $this->language->get('entry_shipping_method_display_title');
    $data['help_shipping_method_display_title'] = $this->language->get('help_shipping_method_display_title');
    $data['entry_shipping_method_input_style'] = $this->language->get('entry_shipping_method_input_style');
    $data['help_shipping_method_input_style'] = $this->language->get('help_shipping_method_input_style');
    $data['entry_shipping_method_default_option'] = $this->language->get('entry_shipping_method_default_option');
    $data['help_shipping_method_default_option'] = $this->language->get('help_shipping_method_default_option');

        //Payment method
    $data['entry_payment_method_display'] = $this->language->get('entry_payment_method_display');
    $data['help_payment_method_display'] = $this->language->get('help_payment_method_display');
    $data['entry_payment_method_display_options'] = $this->language->get('entry_payment_method_display_options');
    $data['help_payment_method_display_options'] = $this->language->get('help_payment_method_display_options');
    $data['entry_payment_method_display_images'] = $this->language->get('entry_payment_method_display_images');
    $data['help_payment_method_display_images'] = $this->language->get('help_payment_method_display_images');
    $data['entry_payment_method_display_title'] = $this->language->get('entry_payment_method_display_title');
    $data['help_payment_method_display_title'] = $this->language->get('help_payment_method_display_title');
    $data['entry_payment_method_input_style'] = $this->language->get('entry_payment_method_input_style');
    $data['help_payment_method_input_style'] = $this->language->get('help_payment_method_input_style');
    $data['entry_payment_method_default_option'] = $this->language->get('entry_payment_method_default_option');
    $data['help_payment_method_default_option'] = $this->language->get('help_payment_method_default_option');
    $data['entry_payment_default_payment_popup'] = $this->language->get('entry_payment_default_payment_popup');
    $data['help_payment_default_payment_popup'] = $this->language->get('help_payment_default_payment_popup');
    $data['callout_payment_payment_popup'] = $this->language->get('callout_payment_payment_popup');

        //Cart
    $data['title_shopping_СЃart'] = $this->language->get('title_shopping_СЃart');
    $data['description_shopping_СЃart'] = $this->language->get('description_shopping_СЃart');
    $data['entry_cart_display'] = $this->language->get('entry_cart_display');
    $data['help_cart_display'] = $this->language->get('help_cart_display');
    $data['entry_cart_columns_image'] = $this->language->get('entry_cart_columns_image');
    $data['entry_cart_columns_name'] = $this->language->get('entry_cart_columns_name');
    $data['entry_cart_columns_model'] = $this->language->get('entry_cart_columns_model');
    $data['entry_cart_columns_quantity'] = $this->language->get('entry_cart_columns_quantity');
    $data['entry_cart_columns_price'] = $this->language->get('entry_cart_columns_price');
    $data['entry_cart_columns_total'] = $this->language->get('entry_cart_columns_total');
    $data['entry_cart_option_coupon'] = $this->language->get('entry_cart_option_coupon');
    $data['help_cart_option_coupon'] = $this->language->get('help_cart_option_coupon');
    $data['entry_cart_option_voucher'] = $this->language->get('entry_cart_option_voucher');
    $data['help_cart_option_voucher'] = $this->language->get('help_cart_option_voucher');
    $data['entry_cart_option_reward'] = $this->language->get('entry_cart_option_reward');
    $data['help_cart_option_reward'] = $this->language->get('help_cart_option_reward');

        //Design
    $data['entry_design_theme'] = $this->language->get('entry_design_theme');
    $data['help_design_theme'] = $this->language->get('help_design_theme');
    $data['entry_design_field'] = $this->language->get('entry_design_field');
    $data['help_design_field'] = $this->language->get('help_design_field');
    $data['entry_design_placeholder'] = $this->language->get('entry_design_placeholder');
    $data['help_design_placeholder'] = $this->language->get('help_design_placeholder');
    $data['entry_design_breadcrumb'] = $this->language->get('entry_design_breadcrumb');
    $data['help_design_breadcrumb'] = $this->language->get('help_design_breadcrumb');
    $data['entry_design_login_option'] = $this->language->get('entry_design_login_option');
    $data['help_design_login_option'] = $this->language->get('help_design_login_option');
    $data['entry_design_login'] = $this->language->get('entry_design_login');
    $data['help_design_login'] = $this->language->get('help_design_login');
    $data['entry_design_address'] = $this->language->get('entry_design_address');
    $data['help_design_address'] = $this->language->get('help_design_address');
    $data['entry_design_cart_image_size'] = $this->language->get('entry_design_cart_image_size');
    $data['help_design_cart_image_size'] = $this->language->get('help_design_cart_image_size');
    $data['entry_design_max_width'] = $this->language->get('entry_design_max_width');
    $data['help_design_max_width'] = $this->language->get('help_design_max_width');
    $data['entry_design_bootstrap'] = $this->language->get('entry_design_bootstrap');
    $data['help_design_bootstrap'] = $this->language->get('help_design_bootstrap');
    $data['entry_design_only_d_quickcheckout'] = $this->language->get('entry_design_only_d_quickcheckout');
    $data['help_design_only_d_quickcheckout'] = $this->language->get('help_design_only_d_quickcheckout');
    $data['entry_design_telephone_countries'] = $this->language->get('entry_design_telephone_countries');
    $data['help_design_telephone_countries'] = $this->language->get('help_design_telephone_countries');
    $data['entry_design_telephone_preferred_countries'] = $this->language->get('entry_design_telephone_preferred_countries');
    $data['help_design_telephone_preferred_countries'] = $this->language->get('help_design_telephone_preferred_countries');
    $data['entry_design_telephone_validation'] = $this->language->get('entry_design_telephone_validation');
    $data['help_design_telephone_validation'] = $this->language->get('help_design_telephone_validation');


    $data['entry_design_column'] = $this->language->get('entry_design_column');
    $data['help_design_column'] = $this->language->get('help_design_column');
    $data['help_login'] = $this->language->get('help_login');
    $data['help_payment_address'] = $this->language->get('help_payment_address');
    $data['help_shipping_address'] = $this->language->get('help_shipping_address');
    $data['help_shipping_method'] = $this->language->get('help_shipping_method');
    $data['help_payment_method'] = $this->language->get('help_payment_method');
    $data['help_cart'] = $this->language->get('help_cart');
    $data['help_payment'] = $this->language->get('help_payment');
    $data['help_confirm'] = $this->language->get('help_confirm');
    $data['entry_design_custom_style'] = $this->language->get('entry_design_custom_style');
    $data['help_design_custom_style'] = $this->language->get('help_design_custom_style');
    $data['entry_design_autocomplete'] = $this->language->get('entry_design_autocomplete');
    $data['help_design_autocomplete'] = $this->language->get('help_design_autocomplete');

        //statistic
    $data['column_order_id'] = $this->language->get('column_order_id');
    $data['column_customer'] = $this->language->get('column_customer');
    $data['column_account'] = $this->language->get('column_account');
    $data['column_total'] = $this->language->get('column_total');
    $data['column_status'] = $this->language->get('column_status');
    $data['column_shipping_method'] = $this->language->get('column_shipping_method');
    $data['column_payment_method'] = $this->language->get('column_payment_method');
    $data['column_data'] = $this->language->get('column_data');
    $data['column_checkout_time'] = $this->language->get('column_checkout_time');
    $data['column_rating'] = $this->language->get('column_rating');

    if (isset($this->session->data['success'])) {
        $data['success'] = $this->session->data['success'];

        unset($this->session->data['success']);
    } else {
        $data['success'] = '';
    }

    if (isset($this->session->data['error'])) {
        $data['error']['warning'] = $this->session->data['error'];
        unset($this->session->data['error']);
    }

        //get currant setting
    $data[$this->codename . '_status'] = $this->model_extension_module_d_quickcheckout->getConfigData($this->codename, $this->codename . '_status', $this->store_id, $this->config_file);
    $data['debug'] = $this->model_extension_module_d_quickcheckout->getConfigData($this->codename, $this->codename . '_debug', $this->store_id, $this->config_file);
    $data['debug_file'] = $this->model_extension_module_d_quickcheckout->getConfigData($this->codename, $this->codename . '_debug_file', $this->store_id, $this->config_file);
    $data['setting'] = $this->model_extension_module_d_quickcheckout->getConfigSetting($this->codename, $this->codename . '_setting', $this->store_id, $this->config_file);
    $data[$this->codename . '_trigger'] = $this->model_extension_module_d_quickcheckout->getConfigData($this->codename, $this->codename . '_trigger', $this->store_id, $this->config_file);
    if(isset($data['setting']['general']['config'])){
        $this->config_file = $data['setting']['general']['config'];
    }
    $data['config'] = $this->config_file;
        //language for fields
    $data['setting'] = $this->model_extension_module_d_quickcheckout->languageFilter($data['setting']);


    if ($data['debug']) {
        $data['debug_log'] = $this->model_extension_module_d_quickcheckout->getFileContents(DIR_LOGS . $data['debug_file']);
    } else {
        $data['debug_log'] = '';
    }

        //get all settings
    $data['settings'] = $this->model_extension_module_d_quickcheckout->getSettings($this->store_id, true);
    foreach ($data['settings'] as $key => $setting) {
        $data['settings'][$key]['href'] = HTTP_CATALOG . 'index.php?route=checkout/checkout&setting_id=' . $setting['setting_id'];
    }

    $data['setting_id'] = $this->model_extension_module_d_quickcheckout->getCurrentSettingId($this->codename, $this->store_id);
    $data['create_setting'] = HTTPS_SERVER . 'index.php?route=' . $this->route . '/createSetting&'.$this->model_extension_d_opencart_patch_user->getUrlToken() . $url;
    $data['delete_setting'] = HTTPS_SERVER . 'index.php?route=' . $this->route . '/deleteSetting&'.$this->model_extension_d_opencart_patch_user->getUrlToken() . $url;
    $data['save_bulk_setting'] = $this->model_extension_module_d_quickcheckout->ajax($this->url->link($this->route . '/saveBulkSetting', $this->model_extension_d_opencart_patch_user->getUrlToken(), 'SSL'));
    $data['setting_cycle'] = $this->config->get($this->codename . '_setting_cycle');
    $data['setting_name'] = $this->model_extension_module_d_quickcheckout->getSettingName($data['setting_id']);

    $data['options'] = array('guest', 'register', 'logged');
        //get config
    $data['config_files'] = $this->model_extension_module_d_quickcheckout->getConfigFiles($this->codename);

        //Get Shipping methods

    $data['shipping_methods'] = $this->model_extension_module_d_quickcheckout->getShippingMethods();
        //Get Payment methods
    $data['payment_methods'] = $this->model_extension_module_d_quickcheckout->getPaymentMethods();

        //Get designes
    $data['themes'] = $this->model_extension_module_d_quickcheckout->getThemes();

        //Get stores
    $data['stores'] = $this->model_extension_module_d_quickcheckout->getStores();

        //Social login
    $data['social_login'] = $this->model_extension_module_d_quickcheckout->isInstalled('d_social_login');

        //Google Analytics
    $data['analytics'] = $this->checkGoogleAnalytics();

        //Statistic
    $data['statistics'] = $this->model_extension_module_d_quickcheckout->getStatistics($data['setting_id']);
    foreach ($data['statistics'] as $key => $value) {
        if(!empty($value['currency_code'])){
            $data['statistics'][$key]['total'] = $this->currency->format($value['total'], $value['currency_code'], $value['currency_value']);
            $data['statistics'][$key]['rating'] = round($value['rating'] * 100) . '%';
        }

        if ($value['customer_id']) {
            if (VERSION >= '2.3.0.2'){
                $data['statistics'][$key]['href_customer'] = $this->url->link('customer/customer/edit', $this->model_extension_d_opencart_patch_user->getUrlToken() . '&customer_id=' . $value['customer_id'], 'SSL');
            }else{
                $data['statistics'][$key]['href_customer'] = $this->url->link('sale/customer/edit', $this->model_extension_d_opencart_patch_user->getUrlToken() . '&customer_id=' . $value['customer_id'], 'SSL');
            }
        } else {
            $data['statistics'][$key]['href_customer'] = '';
        }
    }
        //steps
    foreach ($data['setting']['step'] as $step => $value) {
        if (isset($value['column']) && isset($value['row'])) {
            $data['steps'][$step] = array('column' => $value['column'], 'row' => $value['row']);
        }
    }

    $sort_order = array();
    foreach ($data['steps'] as $key => $value) {

        $sort_order[$key]['column'] = $value['column'];
        $sort_order[$key]['row'] = $value['row'];
    }

    array_multisort($sort_order, SORT_ASC, $data['steps']);

        //languages
    $this->load->model('localisation/language');
    $data['languages'] = $this->model_localisation_language->getLanguages();
    foreach ($data['languages'] as $key =>  $language){
        if(VERSION >= '2.2.0.0'){
            $data['languages'][$key]['flag'] = 'language/'.$language['code'].'/'.$language['code'].'.png';
        }else{
            $data['languages'][$key]['flag'] = 'view/image/flags/'.$language['image'];
        }

    }

        //pagination of analytics

    if (isset($this->request->get['page'])) {
        $page = $this->request->get['page'];
    } else {
        $page = 1;
    }

    $limit = array (
        'start'     => ($page - 1) * $this->config->get('config_limit_admin'),
        'limit'     => $this->config->get('config_limit_admin')
    );

    $data['analytics_getAll'] = $this->model_extension_module_d_quickcheckout->getAnalytics($data['setting_id'], $limit);

    foreach ($data['analytics_getAll'] as $key => $value) {
        if(!empty($value['currency_code'])){
            $data['analytics_getAll'][$key]['total'] = $this->currency->format($value['total'], $value['currency_code'], $value['currency_value']);
            $data['analytics_getAll'][$key]['rating'] = round($value['rating'] * 100) . '%';
        }
        if(!empty($value['data']['data']['account']) && isset($value['data']['data']['account']) ){
            $data['analytics_getAll'][$key]['data']['data']['field']['account']=print_r($value['data']['data']['field'][$value['data']['data']['account']],true);
        }

        if(isset($value['data']['data']['update'])){
            $data['analytics_getAll'][$key]['data']['data']['update']=print_r($value['data']['data']['update'],true);
        }

        if(isset($value['data']['data']['click'])){
            $data['analytics_getAll'][$key]['data']['data']['click']=print_r($value['data']['data']['click'],true);
        }

        if(isset($value['data']['data']['error'])){
            $data['analytics_getAll'][$key]['data']['data']['error']=print_r($value['data']['data']['error'],true);
        }

        if ($value['customer_id']) {

            if (VERSION >= '2.3.0.2'){
                $data['analytics_getAll'][$key]['href_customer'] = $this->url->link('customer/customer/edit', $this->model_extension_d_opencart_patch_user->getUrlToken() . '&customer_id=' . $value['customer_id'], 'SSL');
            }else{
                $data['analytics_getAll'][$key]['href_customer'] = $this->url->link('sale/customer/edit', $this->model_extension_d_opencart_patch_user->getUrlToken() . '&customer_id=' . $value['customer_id'], 'SSL');
            }


        } else {
            $data['analytics_getAll'][$key]['href_customer'] = '';
        }

        if (isset($value['date_added']) && isset($value['date_modified'])) {
            // THANKS VANYA THE DEVELOPER
            $da = new DateTime($value['date_added']);
            $dm = new DateTime($value['date_modified']);
            $diff = $da->diff($dm);
            $data['analytics_getAll'][$key]['checkout_time'] = $diff->format('%H:%I:%S');
        }
    }

    $analytics_total = $this->model_extension_module_d_quickcheckout->getTotalStatistics($data['setting_id']);

    $pagination = new Pagination();
    $pagination->total = $analytics_total;
    $pagination->page = $page;
    $pagination->limit = $this->config->get('config_limit_admin');
    $pagination->url = $this->url->link('extension/module/d_quickcheckout', $this->model_extension_d_opencart_patch_user->getUrlToken() . $url . '&page={page}', 'SSL');

    $data['pagination'] = $pagination->render();
    $data['results'] = sprintf($this->language->get('text_pagination'), ($analytics_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($analytics_total - $this->config->get('config_limit_admin'))) ? $analytics_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $analytics_total, ceil($analytics_total / $this->config->get('config_limit_admin')));

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->model_extension_d_opencart_patch_load->view($this->route, $data));
}

    /**

      Add Assisting functions here

     * */
      public function createSetting() {
        if(VERSION>='3.0.0.0'){
            $this->db->query("ALTER TABLE " .DB_PREFIX."session MODIFY data LONGTEXT NOT NULL");
        }

        $json = array();
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $setting_name = date('m/d/Y h:i:s a', time());
            $setting_id = $this->model_extension_module_d_quickcheckout->setSetting($setting_name, $this->request->post[$this->codename . '_setting'], $this->store_id);
        }
        $this->load->language($this->route);
        if ($setting_id) {
            $this->session->data['success'] = $this->language->get('success_setting_created');
            $json['redirect'] = $this->model_extension_module_d_quickcheckout->ajax($this->url->link($this->route, 'store_id=' . $this->store_id . '&setting_id=' . $setting_id .'&'.$this->model_extension_d_opencart_patch_user->getUrlToken(), 'SSL'));
        } else {
            $json['error'] = $this->language->get('error_setting_not_created');
        }

        $this->response->setOutput(json_encode($json));
    }

    public function deleteSetting() {
        $this->load->language($this->route);
        $this->load->model('extension/d_opencart_patch/user');

        if (($this->request->server['REQUEST_METHOD'] == 'GET') && $this->validate() && isset($this->request->get['setting_id'])) {

            $this->model_extension_module_d_quickcheckout->deleteSetting($this->request->get['setting_id']);
            $this->session->data['success'] = $this->language->get('success_setting_deleted');
            $this->response->redirect($this->url->link($this->route, 'store_id=' . $this->store_id . '&'.$this->model_extension_d_opencart_patch_user->getUrlToken(), 'SSL'));
        } else {
            $setting_id = $this->request->get['setting_id'];
            $this->session->data['error'] = $this->language->get('error_setting_not_deleted');
            $this->response->redirect($this->url->link($this->route, 'store_id=' . $this->store_id . '&setting_id=' . $setting_id .'&'.$this->model_extension_d_opencart_patch_user->getUrlToken(), 'SSL'));
        }
    }

    public function saveBulkSetting() {

        $json = array();
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && !empty($this->request->post['setting'])) {
            $setting_id = $this->request->post['setting_id'];
            $setting = json_decode(html_entity_decode($this->request->post['setting']), true);
            $this->model_extension_module_d_quickcheckout->editSetting($setting_id, $setting);
            $json['data'] = $setting;
        }
        $this->load->language($this->route);
        if (isset($setting_id)) {
            $this->session->data['success'] = $this->language->get('success_setting_saved');
            $json['redirect'] = $this->model_extension_module_d_quickcheckout->ajax($this->url->link($this->route, 'store_id=' . $this->store_id . '&setting_id=' . $setting_id . $this->model_extension_d_opencart_patch_user->getUrlToken(), 'SSL'));
        } else {
            $json['error'] = $this->language->get('error_setting_not_saved');
        }

        $this->response->setOutput(json_encode($json));
    }

    private function validate($permission = 'modify') {

        $this->language->load($this->route);

        if (!$this->user->hasPermission($permission, $this->route)) {
            $this->error['warning'] = $this->language->get('error_permission');
            return false;
        }

        if (isset($this->request->post['config'])) {
            return false;
        }

        return true;
    }

    public function install() {
        if($this->validate()){

         if(VERSION>='3.0.0.0'){
            $this->db->query("ALTER TABLE " .DB_PREFIX."session MODIFY data LONGTEXT NOT NULL");
        }

        $this->load->model('extension/module/d_quickcheckout');
        $this->model_extension_module_d_quickcheckout->installDatabase();
        if($this->d_shopunity){
            $this->load->model('extension/d_shopunity/mbooth');
            $this->model_extension_d_shopunity_mbooth->installDependencies($this->codename);
        }
    }
}

public function uninstall() {
    if($this->validate()){
        $this->load->model('extension/module/d_quickcheckout');
        $this->model_extension_module_d_quickcheckout->uninstallDatabase();

    }
}

    /*
     *  Ajax: clear debug file.
     */

    public function clearDebugFile() {
        $this->load->language($this->route);
        $json = array();

        if (!$this->user->hasPermission('modify', $this->route)) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $file = DIR_LOGS . $this->request->post['debug_file'];

            $handle = fopen($file, 'w+');

            fclose($handle);

            $json['success'] = $this->language->get('success_clear_debug_file');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    /*
     *  Ajax: Get the update information on this module.
     */

    public function getZone() {
        $this->load->model('extension/module/d_quickcheckout');
        $json = $this->model_extension_module_d_quickcheckout->getZonesByCountryId($this->request->get['country_id']);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function updateCompress() {
        $this->load->language($this->route);
        require_once(DIR_SYSTEM . 'library/d_compress/compress.php');

        if ($result) {

            $json['success'] = $this->language->get('success_compress_file');
        } else {
            $json['error'] = $this->language->get('error_permission');
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function checkGoogleAnalytics() {
        $this->load->model('setting/setting');
        $analytics_config = false;

        if(VERSION > '2.3.0.2'){
            if ($this->config->get('analytics_google_status')) {

                $analytics_config = true;
            }
        }elseif(VERSION >= '2.1.0.1') {

            $analytics = $this->model_setting_setting->getSetting('google_analytics');

            if (isset($analytics['google_analytics_status']) && $analytics['google_analytics_status']) {
                $analytics_config = true;
            }
        }else{
            if ($this->config->get('config_google_analytics_status')) {
                $analytics_config = true;
            }
        }
    return  $analytics_config;
}

}

?>