<?php
/**
 * payment_address
 * Returns payment address form
 * Allows the user to modify the payment address form
 * Returns payment address state
 * Modifies payment address state
 * Emits evetns after update.
 */
class ControllerExtensionDQuickcheckoutPaymentAddress extends Controller {
    private $route = 'd_quickcheckout/payment_address';

    public $action = array(
        'payment_address/update',
        'account/update/after',
        'confirm/update'
    );

    public function __construct($registry){
      parent::__construct($registry);
      $this->load->model('extension/d_quickcheckout/account');
      $this->load->model('extension/d_quickcheckout/address');
      $this->load->model('extension/d_quickcheckout/order');
      $this->load->model('extension/d_quickcheckout/store');
    }

    /**
     *  Initialization
     *
     *  Loaded in the extension/module/d_quickcheckout controller once.
     *  Sets default values to state
     *
     */
    public function index($config){

        $this->model_extension_d_quickcheckout_store->updateState(array('config', 'payment_address', 'fields', 'zone_id', 'options'), array());
        
        $state = $this->model_extension_d_quickcheckout_store->getState();

        if($state['session']['account'] == 'logged'){
            $addresses = $this->model_extension_d_quickcheckout_address->getAddresses();
            
            $this->model_extension_d_quickcheckout_store->updateState(array('session','addresses'), $addresses);
            if ((isset($state['session']['payment_address']['address_id']) && (int)$state['session']['payment_address']['address_id'] != 0 && !isset($addresses[$state['session']['payment_address']['address_id']])) || !isset($state['session']['payment_address']['address_id'])) {
                if ($addresses) {
                    reset($addresses);
                    $this->model_extension_d_quickcheckout_store->updateState(array('session','payment_address', 'address_id'), key($addresses));
                } else {
                    $this->model_extension_d_quickcheckout_store->updateState(array('session','payment_address', 'address_id'), 0);
                }
            }
            $state = $this->model_extension_d_quickcheckout_store->getState();
        }
        $captcha_status = VERSION < '3.0.0.0' ? $this->config->get($this->config->get('config_captcha') . '_status')  : $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status');
        $config_captcha_page = (array)$this->config->get('config_captcha_page');
        if(VERSION < '2.2.0.0'){
            $config_captcha_page[] = 'guest';
        }
        if($this->config->get('config_captcha') == 'google' || $this->config->get('config_captcha') == 'google_captcha'){
            if ($captcha_status && in_array($state['session']['account'], $config_captcha_page)) {
                if(VERSION < '2.3.0.0'){
                    $state['captcha'] = $this->load->controller('captcha/' . $this->config->get('config_captcha'));
                }
                else{
			        $state['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
                }
            }
		}elseif($this->config->get('config_captcha') == 'basic' || $this->config->get('config_captcha') == 'basic_captcha'){
            if ($captcha_status) {
                $state['captcha'] = $this->load->controller('extension/d_quickcheckout_captcha/basic_captcha');
            }
        }
        $state = $this->model_extension_d_quickcheckout_store->getState();
        
        $state['config'] = $this->getConfig();
        $state['language']['payment_address'] = $this->getLanguages();
        $state['action']['payment_address'] = $this->action;
        $this->model_extension_d_quickcheckout_store->setState($state);
        
        $state['session']['payment_address'] = $this->getDefault();

        $state['config'][$state['session']['account']]['payment_address']['fields']['zone_id']['options'] = $this->model_extension_d_quickcheckout_address->getZonesByCountryId($state['session']['payment_address']['country_id']);
        
        $this->model_extension_d_quickcheckout_store->setState($state);
        $this->model_extension_d_quickcheckout_order->updateOrder();

    }

    /**
     *  Update
     *
     *  Called via AJAX to update state by current module.
     *  Returns updated state.
     *
     */
    public function update(){
        $rawData = file_get_contents('php://input');
        $post = json_decode($rawData, true);
        if(!$post){
            $post = $this->request->post;
        }

        $this->model_extension_d_quickcheckout_store->loadState();

        $this->model_extension_d_quickcheckout_store->dispatch('payment_address/update/before', $post);
        $this->model_extension_d_quickcheckout_store->dispatch('payment_address/update', $post);
    
        $data = $this->model_extension_d_quickcheckout_store->getStateUpdated();

        $this->model_extension_d_quickcheckout_store->setState($data);
        $this->model_extension_d_quickcheckout_order->updateOrder();
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    /**
     *  Receiver
     *
     *  Receiver listens to dispatch of events and accepts data with action and state.
     *  Receivers are parsed with the first initialization of Store in extension/module/d_quickcheckout controller
     *
     */
    public function receiver($data){
        $update = false;
        
        //updating payment_address field values
        if($data['action'] == 'payment_address/update'){
            if(!empty($data['data']['session']['payment_address'])){
                if (isset($data['data']['session']['payment_address']['address_id']) && $data['data']['session']['payment_address']['address_id'] != 0) {
                    $address_id = (int)$data['data']['session']['payment_address']['address_id'];
                    $address_data = $this->getAddress($address_id);
    
                    $address_data['shipping_address'] = 0;

                    $state['session']['payment_address']['customer_group_id'] = $this->customer->getGroupId();
                    $this->model_extension_d_quickcheckout_store->updateState(array('config', 'logged', 'shipping_address', 'display'),  1);
                    $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_address'),  $address_data);
                } else {
                    if (isset($data['data']['session']['payment_address']['address_id']) && $data['data']['session']['payment_address']['address_id'] == 0) {
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_address', 'address_id'),  0);
                    }
                    foreach($data['data']['session']['payment_address'] as $field => $value){
                        $this->updateField($field, $value);
                    }
                }
                $update = true;
            }
        }
        //updating customer group when changing account
        if($data['action'] == 'account/update/after' 
        && $this->model_extension_d_quickcheckout_store->isUpdated('account')
        ){  

            $state = $this->model_extension_d_quickcheckout_store->getState();
            $zones = $this->model_extension_d_quickcheckout_address->getZonesByCountryId($state['session']['payment_address']['country_id']);
            $this->model_extension_d_quickcheckout_store->updateState(array('config', 'payment_address', 'fields', 'zone_id', 'options'), $zones);

            //If just logged in
            if($state['session']['account'] == 'logged'){

                $update = ($update ? $update : array());
                $addresses = $this->model_extension_d_quickcheckout_address->getAddresses();
                $update['session']['addresses'] = $addresses;
                reset($addresses);
                $address_id = $addresses ? key($addresses) : 0;

                $update['session']['payment_address'] = $this->getAddress($address_id);
                $update['session']['payment_address']['address_id'] = $address_id;
                $update['session']['payment_address']['customer_group_id'] = $this->customer->getGroupId();
                
                if($state['session']['payment_address']['shipping_address'] == 1){
                    $update['session']['payment_address']['shipping_address'] = 0;
                }

                $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_address'), $update['session']['payment_address']);
                $this->model_extension_d_quickcheckout_store->updateState(array('session', 'addresses'), $update['session']['addresses']);
            }else{
                $customer_groups = $this->model_extension_d_quickcheckout_account->getCustomerGroups();

                if($state['config'][$state['session']['account']]['payment_address']['fields']['customer_group_id']['options'] != $customer_groups) {

                    $this->model_extension_d_quickcheckout_store->updateState(array('config', 'payment_address', 'fields', 'customer_group_id', 'options'), $customer_groups);

                    $update = true;
                }  

                $default_customer_group_id = $this->model_extension_d_quickcheckout_account->getDefaultCustomerGroup();
                
                if(in_array($default_customer_group_id, $customer_groups) && empty($state['session']['payment_address']['customer_group_id'])){
                    $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_address', 'customer_group_id' ), $default_customer_group_id);
                }
                
                $zones = $this->model_extension_d_quickcheckout_address->getZonesByCountryId($state['session']['payment_address']['country_id']);

                $this->model_extension_d_quickcheckout_store->updateState(array('config', 'payment_address', 'fields', 'zone_id', 'options'), $zones);
            }
            
        }

        if($data['action'] == 'confirm/update'){
            if($this->model_extension_d_quickcheckout_error->isCheckoutValid()){
                $state = $this->model_extension_d_quickcheckout_store->getState();
                $update = ($update ? $update : array());
                if($state['session']['account'] == 'register'){
                    $this->load->model('account/customer');
                    $this->model_account_customer->addCustomer($state['session']['payment_address']);

                    if($this->customer->login($state['session']['payment_address']['email'], $state['session']['payment_address']['password'])){

                        $this->load->model('extension/d_quickcheckout/order');
                        $this->model_extension_d_quickcheckout_order->initCart();

                        $this->model_extension_d_quickcheckout_store->updateState(array('text_account_login'), $this->getAccountLoginText());

                        $update['session']['account'] = 'logged';

                        $addresses = $this->model_extension_d_quickcheckout_address->getAddresses();
                        if(!empty($addresses)){
                            $update['session']['addresses'] = $addresses;
                            reset($addresses);
                            $address_id = key($addresses);
                        }else{
                            //starting from v3 addCustomer does not create an address.
                            $address_id = $this->model_extension_d_quickcheckout_address->addAddress($state['session']['payment_address']);
                            $update['session']['addresses'] = $this->model_extension_d_quickcheckout_address->getAddresses();
                        }
                        
                        $update['session']['payment_address']['address_id'] = $address_id; 
                        
                        $update['session']['payment_address'] = $this->model_extension_d_quickcheckout_address->getAddress($address_id);
                        $update['session']['payment_address']['customer_group_id'] = $this->customer->getGroupId();

                        if ($state['session']['payment_address']['shipping_address'] == 1) {
                            $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address'), $update['session']['payment_address']);
                            $update['session']['payment_address']['shipping_address'] = 0; 
                        }
                        
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_address'), $update['session']['payment_address']);
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'addresses'), $update['session']['addresses']);
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'account'), $update['session']['account']);
            
                    }
                }
                
                if ($state['session']['account'] == 'logged' && $state['session']['payment_address']['address_id'] == 0) {
                    $address_id = $this->model_extension_d_quickcheckout_address->addAddress($state['session']['payment_address']);
                    $state['session']['payment_address']['address_id'] = $address_id; 
                    $update['session']['addresses'] = $this->model_extension_d_quickcheckout_address->getAddresses();

                    $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_address'), $state['session']['payment_address']);
                    $this->model_extension_d_quickcheckout_store->updateState(array('session', 'addresses'), $update['session']['addresses']);
            
                }
            }
            
        }

        if($update){
            /*info about this action, it is need for shipping address receiver */
            $data['data']['previous_action'] = $data['action'];
            $this->model_extension_d_quickcheckout_store->dispatch('payment_address/update/after', $data['data']);
        }
    }

    /**
     *  validate
     *
     *  Validate method validates the whole step. Used to check the step before confirming the order.
     *
     */
    public function validate(){
        $this->load->model('extension/d_quickcheckout/error');
        $this->load->language('checkout/checkout');
        $state = $this->model_extension_d_quickcheckout_store->getState();
        $step = 'payment_address';
        $result = true;

        if($result && $state['session']['account'] == 'logged' && $state['session']['payment_address']['address_id'] != 0){
            $this->model_extension_d_quickcheckout_error->clearStepErrors($step);
            return $result;
        }

        if(!$state['config'][$state['session']['account']][$step]['display']){
            $this->model_extension_d_quickcheckout_error->clearStepErrors($step);
            return $result;
        }

        foreach($state['session'][$step] as $field_id => $value){
            if(!empty($state['config'][$state['session']['account']][$step]['fields'][$field_id]['display'])
            && !empty($state['config'][$state['session']['account']][$step]['fields'][$field_id]['require'])
            && !empty($state['config'][$state['session']['account']][$step]['fields'][$field_id]['errors'])
            ){ 
                $errors = $state['config'][$state['session']['account']][$step]['fields'][$field_id]['errors'];
            
                $no_errors = true;
                
                if(empty($state['config'][$state['session']['account']][$step]['fields'][$field_id]['depends'])){
                    foreach($errors as $error){
                        
                        if(is_array($error)){
                            foreach($error as $validate => $rule){
                                
                                    if(!$this->model_extension_d_quickcheckout_error->$validate($rule, $value)){
                                        $state['errors'][$step][$field_id] = $this->model_extension_d_quickcheckout_error->text($error['text'], $value);
                                        $result = false;
                                        $no_errors = false;
                                        break;
                                    }
                            
                            }
                        }
                        
                        if($no_errors){
                            $state['errors'][$step][$field_id] = '';
                        }
                    }
                }else{
                    foreach($state['config'][$state['session']['account']][$step]['fields'][$field_id]['depends'] as $dependent_field_id => $dependency){
                        
                        $errors = $state['config'][$state['session']['account']][$step]['fields'][$field_id]['errors'];

                        foreach($dependency as $dependency_setting){
                            if($state['session'][$step][$dependent_field_id] == $dependency_setting['value'] && $dependency_setting['display'] && $dependency_setting['require']){
                                    
                                foreach($errors as $error){                                                                  
                                    if(is_array($error)){
                                        foreach($error as $validate => $rule){
                                            
                                            if(!$this->model_extension_d_quickcheckout_error->$validate($rule, $value)){
                                                $state['errors'][$step][$field_id] = $this->model_extension_d_quickcheckout_error->text($error['text'], $value);
                                                $result = false;
                                                $no_errors = false;
                                                break;
                                            }
            
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }else if (!empty($state['config'][$state['session']['account']][$step]['fields'][$field_id]['depends'])){
                $state['errors'][$step][$field_id] = '';
                foreach($state['config'][$state['session']['account']][$step]['fields'][$field_id]['depends'] as $dependent_field_id => $dependency){

                    $errors = $state['config'][$state['session']['account']][$step]['fields'][$field_id]['errors'];

                    foreach($dependency as $dependency_setting){
                        if($state['session'][$step][$dependent_field_id] == $dependency_setting['value'] && $dependency_setting['display'] && $dependency_setting['require']){
                                
                            foreach($errors as $error){ 
                                                            
                                if(is_array($error)){
                                    foreach($error as $validate => $rule){
                                        
                                        if(!$this->model_extension_d_quickcheckout_error->$validate($rule, $value)){
                                            $state['errors'][$step][$field_id] = $this->model_extension_d_quickcheckout_error->text($error['text'], $value);
                                            $result = false;
                                            $no_errors = false;
                                            break;
                                        }
                                        
                                    
                                    }
                                }
                            }
                        }
                    }
                }
            } else{
                $state['errors'][$step][$field_id] = '';
            } 
        }

        $this->model_extension_d_quickcheckout_store->updateState(array('errors','payment_address'), $state['errors']['payment_address']);

        return $result;
    }

    /****************************************************************************************************
     *
     *  Private Methods
     *
     ****************************************************************************************************/
    
    /**
     * logic for updating fields
     */
    private function updateField($field, $value){
        if (in_array($field, array('country', 'iso_code_2', 'iso_code_3', 'address_format', 'zone', 'zone_code', 'custom_field'))) {
            return ;
        }

        if($this->validateField($field, $value)){

            $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_address', $field),  $value);
            $state = $this->model_extension_d_quickcheckout_store->getState();

            switch ($field){

                //if country_id is modified
                case 'country_id' :
                    if($this->model_extension_d_quickcheckout_store->isUpdated('payment_address_'.$field)){
                        $country_data = $this->model_extension_d_quickcheckout_address->getAddressCountry($value);
                        $state['session']['payment_address'] = array_merge($state['session']['payment_address'], $country_data);
                   
                        $state['session']['payment_address']['zone_id'] = '';

                        $zone_data = $this->model_extension_d_quickcheckout_address->getAddressZone(0);

                        $state['session']['payment_address'] = array_replace_recursive($state['session']['payment_address'], $zone_data);
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_address'),  $state['session']['payment_address']);

                        $zones = $this->model_extension_d_quickcheckout_address->getZonesByCountryId($value);
                        
                        $this->model_extension_d_quickcheckout_store->updateState(array('config', 'payment_address', 'fields', 'zone_id', 'options'), $zones);
                    }
                    break;

                //if zone_id is modified
                case 'zone_id' :
                    if($this->model_extension_d_quickcheckout_store->isUpdated('payment_address_'.$field) && !$this->model_extension_d_quickcheckout_store->isUpdated('payment_address_country_id')){
                        $zone_data = $this->model_extension_d_quickcheckout_address->getAddressZone($value);
                        $state['session']['payment_address'] = array_merge($state['session']['payment_address'], $zone_data);
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_address'),  $state['session']['payment_address']);
                    } else if($this->model_extension_d_quickcheckout_store->isUpdated('payment_address_'.$field) && $this->model_extension_d_quickcheckout_store->isUpdated('payment_address_country_id')) {
                        $state['session']['payment_address']['zone_id'] = '';

                        $zone_data = $this->model_extension_d_quickcheckout_address->getAddressZone(0);

                        $state['session']['payment_address'] = array_replace_recursive($state['session']['payment_address'], $zone_data);
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_address'),  $state['session']['payment_address']);
                    }
                    break;

                //validating telephone
                case 'telephone_iso2':
                    $config = $this->model_extension_d_quickcheckout_store->getState();
                    foreach($value as $iso2_field_id => $iso2_value){
                        $errors = $config['config'][$config['session']['account']]['payment_address']['fields'][$iso2_field_id]['errors'];
                        foreach($errors as $error_id => $error_value){
                            if(isset($error_value['telephone'])){
                                $state['config'][$config['session']['account']]['payment_address']['fields'][$iso2_field_id]['errors'][$error_id]['telephone'] = $iso2_value;
                            }
                            
                        }
                    }

                    $this->model_extension_d_quickcheckout_store->setState($state);
                    break;

                case 'customer_group_id' :
                   
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_address', 'customer_group_id'), $value);
                        $this->model_extension_d_quickcheckout_store->dispatch('total/update','');
                    break;

                default: 
                    
                    if(isset($state['config']['guest']['payment_address']['fields'][$field])){
                        if(!empty($state['config']['guest']['payment_address']['fields'][$field]['custom'])){
                            $location = $state['config']['guest']['payment_address']['fields'][$field]['location'];
                            $custom_field_id = $state['config']['guest']['payment_address']['fields'][$field]['custom_field_id'];
                        }
                    }else{
                        $part = explode('-', $field);
                        if(isset($part[2]) && is_numeric($part[2])){
                            if($part[0] == 'custom'){
                                $location = $part[1];
                                $custom_field_id = $part[2];
                            }
                        }
                        
                    }
                    if(isset($location)){
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_address', 'custom_field', $location, $custom_field_id),  $value);
                    }
                    //nothing at the moment;
                    break;
            }
            
        }else{
            $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_address', $field),  $value);
	        if ($field == 'country_id') {
                $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_address', 'zone_id'),  '');
		        $this->model_extension_d_quickcheckout_store->updateState(array('config', 'payment_address', 'fields', 'zone_id', 'options'), '');
            }
        }

    }

    private function getConfig(){
        
        $this->load->model('extension/d_quickcheckout/view');
        $this->load->model('extension/d_quickcheckout/store');
        $this->load->model('localisation/language');

        $this->load->config('d_quickcheckout/payment_address');
        $config = $this->config->get('d_quickcheckout_payment_address');

        $state = $this->model_extension_d_quickcheckout_store->getState();
        $settings = $this->model_extension_d_quickcheckout_store->getSetting();
        $result = array();

        $captcha_status =  $state['captcha_status'] = VERSION < '3.0.0.0' ? $this->config->get($this->config->get('config_captcha') . '_status')  : $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status');
        $config_captcha_page = (array)$this->config->get('config_captcha_page');
        if(VERSION < '2.2.0.0'){
            $config_captcha_page[] = 'guest';
        }
        foreach($config['account'] as $account => $value){
            if(!empty($settings['config'][$account]['payment_address'])){
                $result[$account]['payment_address'] = $settings['config'][$account]['payment_address'];
            }else{
                $result[$account]['payment_address'] = array_replace_recursive($config, $value);
            }
            
            $result[$account]['payment_address']['fields']['country_id']['options'] = $this->model_extension_d_quickcheckout_address->getCountries();
            $result[$account]['payment_address']['fields']['customer_group_id']['options'] = $this->model_extension_d_quickcheckout_account->getCustomerGroups();
            if(!empty($state['captcha_type'])){
                if ($captcha_status && in_array($account, $config_captcha_page) && $state['captcha_type'] == 'basic' || $state['captcha_type'] == 'basic_captcha') {
                
                    $result[$account]['payment_address']['fields']['captcha']['errors']['error0']['wrong_captcha'] = !empty($state['session']['captcha']) ? $state['session']['captcha'] : '';
                
                }
                else{
                    $result[$account]['payment_address']['fields']['captcha']['errors']['error0']['wrong_captcha'] = '';
                }
                if($captcha_status && in_array($account, $config_captcha_page) && $state['captcha_type'] == 'google'){
                
                    $result[$account]['payment_address']['fields']['google_recaptcha']['errors']['error0']['false_google_recaptcha'] = 1;
                
                    
                }
                else{
                    $result[$account]['payment_address']['fields']['google_recaptcha']['errors']['error0']['false_google_recaptcha'] = 0;
                }
            }
            else{
                $result[$account]['payment_address']['fields']['captcha']['errors']['error0']['wrong_captcha'] = '';
                $result[$account]['payment_address']['fields']['google_recaptcha']['errors']['error0']['false_google_recaptcha'] = 0;
            }
            
            foreach ($result[$account]['payment_address']['fields'] as $key => &$field) {
                if (stripos($key, 'custom-') !== false) {
                    if (isset($field['type']) && ($field['type'] == 'select' || $field['type'] == 'radio')) {
                        $custom_field_options = $this->model_extension_d_quickcheckout_address->getCustomFieldOptions($field['custom_field_id']);
                        if ($custom_field_options) {
                            $field['options'] = $custom_field_options;
                        }
                    }
                }
            }
            
            $result[$account]['payment_address']['fields']['country_id']['options'] = $this->model_extension_d_quickcheckout_address->getCountries();
        }

        return $result;
    }

    private function getLanguages(){
        $this->load->language('checkout/checkout');
        $this->load->language('extension/d_quickcheckout/payment_address');
        $result = array();
        $languages = $this->config->get('d_quickcheckout_payment_address_language');

        

        foreach ($languages as $key => $language) {
            $result[$key] = $this->language->get($language);
        }

        $language = $this->model_extension_d_quickcheckout_store->getLanguage();
        if(isset($language['payment_address'])){
            $result = array_replace_recursive($result, $language['payment_address']);
        }

        //links in default texts.
        $result['entry_newsletter'] = sprintf($result['entry_newsletter'], $this->config->get('config_name'));

        if ($this->config->get('config_account_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

            if ($information_info) {
                $result['entry_agree'] = sprintf($result['entry_agree'], $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_account_id'), true), htmlspecialchars_decode($information_info['title']), $information_info['title']);

                $result['error_agree_checked'] = sprintf($result['error_agree_checked'], htmlspecialchars_decode($information_info['title']));
            }
        }

        $this->load->model('account/custom_field');

        $result['image'] = HTTPS_SERVER.'image/catalog/d_quickcheckout/step/payment_address.svg';

        return $result;

    }


    private function getAddress($address_id){
        $resutl = $this->model_extension_d_quickcheckout_address->getAddress($address_id);

        if($resutl){
            return $resutl;
        }else{
            return $this->getDefault();
        }
    }


    /**
     * Default state
     */
    private function getDefault($populate = true){

        $clear_session = $this->config->get('d_quickcheckout_clear_session');

        $payment_address = array();
        $state = $this->model_extension_d_quickcheckout_store->getState();
    
        if($populate){
            if(
                isset($state['session']) && $state['session']['account'] == 'logged'
                && !empty(current($state['session']['addresses'])['address_id'])
            ){
                if (
                    isset($state['session']['payment_address']['address_id']) 
                    && (int)$state['session']['payment_address']['address_id'] != 0 
                    && !empty($state['session']['addresses'][$state['session']['payment_address']['address_id']]) 
                    && !$clear_session
                ) {
                    $address_data = $state['session']['addresses'][$state['session']['payment_address']['address_id']];
                    foreach($address_data as $field_id => $value){
                        $state['session']['payment_address'][$field_id] = $value;
                    }
                } else if (
                    (
                    (
                        isset($state['session']['payment_address']['address_id']) 
                        && (int)$state['session']['payment_address']['address_id'] != 0 
                        && empty($state['session']['addresses'][$state['session']['payment_address']['address_id']]
                    )
                    ) 
                    || !isset($state['session']['payment_address']['address_id'])) 
                    && !$clear_session
                ) {
                    foreach(current($state['session']['addresses']) as $field_id => $value){
                        $state['session']['payment_address'][$field_id] = $value;
                    }
                }

                $payment_address = $state['session']['payment_address'];

            }
        }



        $default = $state['config'][$state['session']['account']]['payment_address']['fields'];

        $address = array(
            'firstname' => '',
            'lastname' => '',
            'email' => '',
            'email_confirm' => '',
            'telephone' => '',
            'fax' => '',
            'password' => '',
            'confirm' => '',
            'company' => '',
            'address_1' => '',
            'address_2' => '',
            'postcode' => '',
            'city' =>  '',
            'country_id' => '',
            'zone_id' => '',
            'country' => '',
            'iso_code_2' => '',
            'iso_code_3' => '',
            'address_format' => '',
            'custom_field' => array(),
            'zone' => '',
            'zone_code' => '',
            'agree' => '',
            'shipping_address' => 0,
            'newsletter' => 0,
            'address_id' => 0,
            'captcha' => '',
            'google_recaptcha' => ''
        );
        
        //init custom fields
        foreach($default as $key => $field){
            if(!empty($field['custom'])){
                $address[$key] = (!$clear_session && isset($state['session']['payment_address'][$key])) ? $state['session']['payment_address'][$key] : $field['value'];

                $part = explode('-', $key);
                if(isset($part[2]) && is_numeric($part[2])){
                    if($part[0] == 'custom'){
                        $location = $part[1];
                        $custom_field_id = $part[2];
                    }
                }

                $custom_field = array( 
                    $location => array( 
                        $custom_field_id => ((!$clear_session && isset($state['session']['payment_address'][$key])) ? $state['session']['payment_address'][$key] : $field['value'])
                    )
                );
                $address['custom_field'] = array_merge($address['custom_field'], $custom_field);
            }
        }


        foreach($address as $key => $value){
            if(isset($payment_address[$key])){
                $address[$key] = $payment_address[$key];
            }elseif(isset($default[$key]) && isset($default[$key]['value'])){
                $address[$key] = (!$clear_session && isset($state['session']['payment_address'][$key])) ? $state['session']['payment_address'][$key] : $default[$key]['value'];
            }
        }
        $address['customer_group_id'] = $this->model_extension_d_quickcheckout_account->getDefaultCustomerGroup();
        
        if(isset($address['country_id'])){
            $country_data = $this->model_extension_d_quickcheckout_address->getAddressCountry($address['country_id']);
            $address = array_merge($address, $country_data);
            unset($country_data);
        }

        if(isset($address['zone_id'])){
            $zone_data = $this->model_extension_d_quickcheckout_address->getAddressZone($address['zone_id']);
            $address = array_merge($address, $zone_data);
            unset($zone_data);
        }


        return $address;

    }

    private function validateField($field, $value){
        $this->load->language('checkout/checkout');
        $this->load->model('extension/d_quickcheckout/error');
        $valid = $this->model_extension_d_quickcheckout_error->validateField('payment_address', $field, $value);
        return $valid;
    }

    private function getAccountLoginText(){
        $output = $this->load->controller('common/header');
        $html_dom = new d_simple_html_dom();
        $html_dom->load((string)$output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);
        $html_element = $html_dom->find('#top-links > ul > li', 1);
        if($html_element){
            return (string)$html_element->innertext;
        }
        return null;
    }
}
