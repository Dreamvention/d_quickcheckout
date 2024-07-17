<?php

class ControllerExtensionDQuickcheckoutShippingAddress extends Controller {
    private $route = 'd_quickcheckout/shipping_address';
    private $hasShipping = null;

    public $action = array(
        'shipping_address/update',
        'payment_address/update/after',
        'account/update/after',
        'cart/update/after',
        'confirm/update'
    );

    public function __construct($registry){
        parent::__construct($registry);

        $this->load->model('extension/d_quickcheckout/store');
        $this->load->model('extension/d_quickcheckout/address');
        $this->load->model('extension/d_quickcheckout/account');
        $this->load->model('extension/d_quickcheckout/order');
    }

    /**
    * Initialization
    */
    public function index($config){
        
        $this->model_extension_d_quickcheckout_store->updateState(array('config', 'shipping_address', 'fields', 'zone_id', 'options'), array());
        $state = $this->model_extension_d_quickcheckout_store->getState();

        if($state['session']['account'] == 'logged'){
            $addresses = $this->model_extension_d_quickcheckout_address->getAddresses();
            
            $this->model_extension_d_quickcheckout_store->updateState(array('session','addresses'), $addresses);
            
            if ((int)$state['session']['shipping_address']['address_id'] != 0 && !isset($addresses[$state['session']['shipping_address']['address_id']])) {
                if ($addresses) {
                    reset($addresses);
                    $this->model_extension_d_quickcheckout_store->updateState(array('session','shipping_address', 'address_id'), key($addresses));
                } else {
                    $this->model_extension_d_quickcheckout_store->updateState(array('session','shipping_address', 'address_id'), 0);
                }
            }
            $state = $this->model_extension_d_quickcheckout_store->getState();
        }

        $state['config'] = $this->getConfig();
        $state['language']['shipping_address'] = $this->getLanguages();
        $state['action']['shipping_address'] = $this->action;
        $this->model_extension_d_quickcheckout_store->setState($state);

        $state['session']['shipping_address'] = $this->getDefault();
        $state['session']['has_shipping'] = $this->hasShipping();

        $state['config'][$state['session']['account']]['shipping_address']['fields']['zone_id']['options'] = $this->model_extension_d_quickcheckout_address->getZonesByCountryId($state['session']['shipping_address']['country_id']);

        $this->model_extension_d_quickcheckout_store->setState($state);
        $this->model_extension_d_quickcheckout_order->updateOrder();
    }

    /**
    * update via ajax
    */
    public function update(){
        $rawData = file_get_contents('php://input');
        $post = json_decode($rawData, true);
        if(!$post){
            $post = $this->request->post;
        }
        $this->model_extension_d_quickcheckout_store->loadState();
        $this->model_extension_d_quickcheckout_store->dispatch('shipping_address/update/before', $post);
        $this->model_extension_d_quickcheckout_store->dispatch('shipping_address/update', $post);

        $this->model_extension_d_quickcheckout_order->updateOrder();
        
        $data = $this->model_extension_d_quickcheckout_store->getStateUpdated();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }


    /**
    * Receiver
    * Receiver listens to dispatch of events and accepts data array with action and state
    */
    public function receiver($data){
        $update = false;

        if($data['action'] == 'shipping_address/update'){
            if(!empty($data['data']['session']['shipping_address'])){
                if (isset($data['data']['session']['shipping_address']['address_id']) && $data['data']['session']['shipping_address']['address_id'] != 0) {
                    $address_id = (int)$data['data']['session']['shipping_address']['address_id'];
                    $address_data = $this->getAddress($address_id);
                    
                    $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address'),  $address_data);
                } else {
                    if (isset($data['data']['session']['shipping_address']['address_id']) && $data['data']['session']['shipping_address']['address_id'] == 0) {
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address', 'address_id'),  0);
                    }
                    foreach($data['data']['session']['shipping_address'] as $field => $value){
                        $this->updateField($field, $value);
                    }
                }
                $update = true;
            }
        }

        if($data['action'] == 'cart/update/after'){
            $this->model_extension_d_quickcheckout_store->updateState(array('config', 'shipping_address', 'display'), $this->getDisplayShippingAddress());
        }

        if($data['action'] == 'payment_address/update/after'){
            $display_shipping_address = $this->getDisplayShippingAddress();

            //is shipping show
            if($display_shipping_address){
                $this->load->model('extension/d_quickcheckout/error');
                
                if($this->model_extension_d_quickcheckout_error->isCheckoutValid()){
                    $state = $this->model_extension_d_quickcheckout_store->getState();

                    //guest
                    if($state['session']['account'] == 'guest'){
                        $this->model_extension_d_quickcheckout_store->updateState(array('config', 'shipping_address', 'display'), 1);

                        $this->load->model('extension/d_quickcheckout/address');
                        $zones = $this->model_extension_d_quickcheckout_address->getZonesByCountryId($state['session']['shipping_address']['country_id']);

                        $this->model_extension_d_quickcheckout_store->updateState(array('config', 'shipping_address', 'fields', 'zone_id', 'options'), $zones);
                    }

                    //register
                    if($state['session']['account'] == 'register'){
                        if($this->model_extension_d_quickcheckout_store->isUpdated('payment_address_shipping_address')){
                            $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address'), $this->getDefault($populate = false));
                            $this->model_extension_d_quickcheckout_store->updateState(array('config', 'shipping_address', 'display'), 1);
                            $update = true;
                        }
                    }

                    //register -> login
                    if(isset($data['data']['previous_action']) && $data['data']['previous_action'] == 'confirm/update' && $this->model_extension_d_quickcheckout_store->isUpdated('account') && $state['session']['account'] == 'logged' && (int)$state['session']['shipping_address']['address_id'] == 0){
                        $this->load->model('extension/d_quickcheckout/address');
                        $address_id = $this->model_extension_d_quickcheckout_address->addAddress($state['session']['shipping_address']);
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address','address_id'), $address_id);

                        $addresses = $this->model_extension_d_quickcheckout_address->getAddresses();

                        $address_data = $this->model_extension_d_quickcheckout_address->getAddress($address_id);

                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address'), $address_data);
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'addresses'), $addresses);
                    } 
                    
                    //login
                    if (isset($data['data']['previous_action']) && $data['data']['previous_action'] == 'account/update/after' && $this->model_extension_d_quickcheckout_store->isUpdated('account') && $state['session']['account'] == 'logged' && (int)$state['session']['shipping_address']['address_id'] == 0) {
                        $addresses = $this->model_extension_d_quickcheckout_address->getAddresses();
                        
                        reset($addresses);
                        $address_id = $addresses ? key($addresses) : 0;

                        if ((int)$address_id != 0) {
                            $address_data = $this->model_extension_d_quickcheckout_address->getAddress($address_id);
                        } else {
                            $address_data = $this->getDefault();
                        }

                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address', 'address_id'), $address_id);
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address'), $address_data);
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'addresses'), $addresses);
                    }

                    //logged in change
                    if(!$this->model_extension_d_quickcheckout_store->isUpdated('account') && $state['session']['account'] == 'logged'){
                        if(!$this->model_extension_d_quickcheckout_store->isUpdated('payment_address_address_id') 
                            && $state['config'][$state['session']['account']]['shipping_address']['display'] == 0 
                            && $state['session']['payment_address']['shipping_address'] == 1){
                                
                            $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address'), $this->getShippingAddressFromPaymentAddress());
                            $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address', 'address_id'), $state['session']['payment_address']['address_id']);
                        }else if ($state['session']['payment_address']['shipping_address'] == 0 && $state['config'][$state['session']['account']]['shipping_address']['display'] == 1) {
                            if (isset($data['data']['session']['shipping_address']['address_id']) && $data['data']['session']['shipping_address']['address_id'] != 0) {
                                $address_id = (int)$data['data']['session']['shipping_address']['address_id'];
                                $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address'), $this->getAddress($address_id));
                                $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address', 'address_id'), $address_id);
                            }
                            $this->model_extension_d_quickcheckout_store->updateState(array('config', 'shipping_address', 'display'), 1);
                        }
                    }
                }else{
                    $this->model_extension_d_quickcheckout_store->updateState(array('config', 'shipping_address', 'display'), 1);
                }

            }else{
                $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address'), $this->getShippingAddressFromPaymentAddress());
                $this->model_extension_d_quickcheckout_store->updateState(array('config', 'shipping_address', 'display'), 0);
                $update = true;
            }
            
        }


        //update zone options on account change
        if($data['action'] == 'account/update/after' 
        && $this->model_extension_d_quickcheckout_store->isUpdated('account')
        ){ 

            $state = $this->model_extension_d_quickcheckout_store->getState();
            $this->load->model('extension/d_quickcheckout/address');
            $zones = $this->model_extension_d_quickcheckout_address->getZonesByCountryId($state['session']['shipping_address']['country_id']);

            $this->model_extension_d_quickcheckout_store->updateState(array('config', 'shipping_address', 'fields', 'zone_id', 'options'), $zones);
        }
        if($data['action'] == 'confirm/update'){
            $display_shipping_address = $this->getDisplayShippingAddress();
            if($display_shipping_address){
                $this->load->model('extension/d_quickcheckout/error');
                if($this->model_extension_d_quickcheckout_error->isCheckoutValid()){
                    $state = $this->model_extension_d_quickcheckout_store->getState();

                    //logged in change
                    if(!$this->model_extension_d_quickcheckout_store->isUpdated('account') && $state['session']['account'] == 'logged'){
                        if($state['config'][$state['session']['account']]['shipping_address']['display'] == 1 && $state['session']['shipping_address']['address_id'] == 0){

                            $this->load->model('extension/d_quickcheckout/address');
                            $address_id = $this->model_extension_d_quickcheckout_address->addAddress($this->session->data['shipping_address']);
                            $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address','address_id'), $address_id);

                            $addresses = $this->model_extension_d_quickcheckout_address->getAddresses();
                            $this->model_extension_d_quickcheckout_store->updateState(array('session', 'addresses'), $addresses);
                        }
                        if($state['config'][$state['session']['account']]['shipping_address']['display'] == 0){
                        }
                    }
                }

                $this->model_extension_d_quickcheckout_store->updateState(array('config', 'shipping_address', 'display'), 1);
            }else{
                $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address'), $this->getShippingAddressFromPaymentAddress());
                $this->model_extension_d_quickcheckout_store->updateState(array('config', 'shipping_address', 'display'), 0);
                $update = true;
            }
        }

        if($update){
            $this->model_extension_d_quickcheckout_store->dispatch('shipping_address/update/after', $data);
        }
    }

    public function validate(){
        $this->load->model('extension/d_quickcheckout/error');
        $state = $this->model_extension_d_quickcheckout_store->getState();

        $step = 'shipping_address';
        $result = true;
        if($result 
            && (
                ($state['session']['account'] == 'logged' 
                    && $state['session']['shipping_address']['address_id'] != 0)
                || !$this->getDisplayShippingAddress() 
            )
        ){
            $this->model_extension_d_quickcheckout_error->clearStepErrors($step);
            return $result;
        }

        if(!$state['config'][$state['session']['account']][$step]['display']){
            $this->model_extension_d_quickcheckout_error->clearStepErrors($step);
            return $result;
        }

        foreach($state['session']['shipping_address'] as $field_id => $value){
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
                } else {
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
            }else{
                $state['errors'][$step][$field_id] = '';
            }
        }

        $this->model_extension_d_quickcheckout_store->updateState(array('errors','shipping_address'), $state['errors']['shipping_address']);

        return $result;
    }

    /**
    * logic for updating fields
    */
    private function updateField($field, $value){
        if (in_array($field, array('country', 'iso_code_2', 'iso_code_3', 'address_format', 'zone', 'zone_code', 'custom_field'))) {
            return ;
        }
        
        if($this->validateField($field, $value)){
           
            $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address', $field),  $value);
            $state = $this->model_extension_d_quickcheckout_store->getState();
            
            switch ($field){

                case 'country_id' :
                    if($this->model_extension_d_quickcheckout_store->isUpdated('shipping_address_'.$field)){
                        $country_data = $this->model_extension_d_quickcheckout_address->getAddressCountry($value);

                        $state['session']['shipping_address'] = array_replace_recursive($state['session']['shipping_address'], $country_data);
                        
                        $state['session']['shipping_address']['zone_id'] = '';

                        $zone_data = $this->model_extension_d_quickcheckout_address->getAddressZone(0);

                        $state['session']['shipping_address'] = array_replace_recursive($state['session']['shipping_address'], $zone_data);

                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address'),  $state['session']['shipping_address']);

                        $zones = $this->model_extension_d_quickcheckout_address->getZonesByCountryId($value);
                        $this->model_extension_d_quickcheckout_store->updateState(array('config', 'shipping_address', 'fields', 'zone_id', 'options'), $zones);
                    }
                break;

                case 'zone_id' :
                    if($this->model_extension_d_quickcheckout_store->isUpdated('shipping_address_'.$field) && !$this->model_extension_d_quickcheckout_store->isUpdated('shipping_address_country_id')){
                        $zone_data = $this->model_extension_d_quickcheckout_address->getAddressZone($value);

                        $state['session']['shipping_address'] = array_replace_recursive($state['session']['shipping_address'], $zone_data);

                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address'),  $state['session']['shipping_address']);
                    } else if($this->model_extension_d_quickcheckout_store->isUpdated('shipping_address_'.$field) && $this->model_extension_d_quickcheckout_store->isUpdated('shipping_address_country_id')) {
                        $state['session']['shipping_address']['zone_id'] = '';

                        $zone_data = $this->model_extension_d_quickcheckout_address->getAddressZone(0);

                        $state['session']['shipping_address'] = array_replace_recursive($state['session']['shipping_address'], $zone_data);
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address'),  $state['session']['shipping_address']);
                    }
                break;

                default: 
                    if(isset($state['config']['guest']['shipping_address']['fields'][$field])){
                        if(!empty($state['config']['guest']['shipping_address']['fields'][$field]['custom'])){
                            $location = $state['config']['guest']['shipping_address']['fields'][$field]['location'];
                            $custom_field_id = $state['config']['guest']['shipping_address']['fields'][$field]['custom_field_id'];
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
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address', 'custom_field', $location, $custom_field_id),  $value);
                    }
                    //nothing at the moment;
                break;
            }
        }else{
            $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address', $field),  $value);
	        if ($field == 'country_id') {
                $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address', 'zone_id'),  '');
		        $this->model_extension_d_quickcheckout_store->updateState(array('config', 'shipping_address', 'fields', 'zone_id', 'options'), '');
            }
        }
    }

    private function getConfig(){

        $this->load->model('extension/d_quickcheckout/view');
        $this->load->model('extension/d_quickcheckout/store');
        $this->load->config('d_quickcheckout/shipping_address');
        $config = $this->config->get('d_quickcheckout_shipping_address');

        $state = $this->model_extension_d_quickcheckout_store->getState();
        $settings = $this->model_extension_d_quickcheckout_store->getSetting();
        $result = array();
        foreach($config['account'] as $account => $value){
            if(!empty($settings['config'][$account]['shipping_address'])){
                $result[$account]['shipping_address'] = $settings['config'][$account]['shipping_address'];
            }else{
                $result[$account]['shipping_address'] = array_replace_recursive($config, $value);
            }

            foreach ($result[$account]['shipping_address']['fields'] as $key => &$field) {
                if (stripos($key, 'custom-') !== false) {
                    if (isset($field['type']) && ($field['type'] == 'select' || $field['type'] == 'radio')) {
                        $custom_field_options = $this->model_extension_d_quickcheckout_address->getCustomFieldOptions($field['custom_field_id']);
                        if ($custom_field_options) {
                            $field['options'] = $custom_field_options;
                        }
                    }
                }
            }

            $result[$account]['shipping_address']['fields']['country_id']['options'] = $this->model_extension_d_quickcheckout_address->getCountries();
            $result[$account]['shipping_address']['display'] = $this->getDisplayShippingAddress();
        }
        return $result;
    }

    private function getLanguages(){
        $this->load->language('checkout/checkout');
        $this->load->language('extension/d_quickcheckout/shipping_address');

        $result = array();
        $languages = $this->config->get('d_quickcheckout_shipping_address_language');

        foreach ($languages as $key => $language) {
            $result[$key] = $this->language->get($language);
        }

        $language = $this->model_extension_d_quickcheckout_store->getLanguage();
        if(isset($language['shipping_address'])){
            $result = array_replace_recursive($result, $language['shipping_address']);
        }

        $result['image'] = HTTPS_SERVER.'image/catalog/d_quickcheckout/step/shipping_address.svg';     

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

        $this->load->model('extension/d_quickcheckout/account');

        $shipping_address = array();
        $state = $this->model_extension_d_quickcheckout_store->getState();
        if($populate){
            if (
                !empty($state['session']['shipping_address']) 
                && is_array($state['session']['addresses'])
                && (int)$state['session']['shipping_address']['address_id'] != 0 
                && array_key_exists($state['session']['shipping_address']['address_id'], $state['session']['addresses']) 
                && !$clear_session
            ) {
                $shipping_address = $state['session']['addresses'][$state['session']['shipping_address']['address_id']];
            } elseif (
                isset($state['session']) 
                && $state['session']['account'] == 'logged'
                && (int)$state['session']['shipping_address']['address_id'] != 0 
                && !empty(current($state['session']['addresses'])['address_id'])
            ){
                foreach(current($state['session']['addresses']) as $field_id => $value){
                    $state['session']['shipping_address'][$field_id] = $value;
                }
                $shipping_address = $state['session']['shipping_address'];
            }
        }


        $default = $state['config'][$state['session']['account']]['shipping_address']['fields'];

        $address = array(
            'firstname' => '',
            'lastname' => '',
            'company' => '',
            'address_1' => '',
            'address_2' => '',
            'postcode' => '',
            'city' => '',
            'country_id' => '',
            'zone_id' => '',
            'country' => '',
            'iso_code_2' => '',
            'iso_code_3' => '',
            'address_format' => '',
            'custom_field' =>  array(),
            'zone' => '',
            'zone_code' => '',
            'address_id' => 0
            );

          
            
        //init custom fields
        foreach($default as $key => $field){
            if(!empty($field['custom'])){
                $address[$key] = (!$clear_session && isset($state['session']['shipping_address'][$key])) ? $state['session']['shipping_address'][$key] : $field['value'];

                $part = explode('-', $key);
                if(isset($part[2]) && is_numeric($part[2])){
                    if($part[0] == 'custom'){
                        $location = $part[1];
                        $custom_field_id = $part[2];
                    }
                }

                $custom_field = array( 
                    $location => array( 
                        $custom_field_id => ((!$clear_session && isset($state['session']['shipping_address'][$key])) ? $state['session']['shipping_address'][$key] : $field['value'])
                    )
                );
                $address['custom_field'] = array_merge($address['custom_field'], $custom_field);
            }
        }
        
        foreach($address as $key => $value){
            if(isset($shipping_address[$key])){
                $address[$key] = $shipping_address[$key];
            }elseif(isset($default[$key]) && isset($default[$key]['value'])){
                $address[$key] = (!$clear_session && isset($state['session']['shipping_address'][$key])) ? $state['session']['shipping_address'][$key] : $default[$key]['value'];
            }
        }

        if (isset($address['country_id'])) {
            $country_data = $this->model_extension_d_quickcheckout_address->getAddressCountry($address['country_id']);
            $address = array_replace_recursive($address, $country_data);
            unset($country_data);
        }

        if(isset($address['zone_id'])){
            $zone_data = $this->model_extension_d_quickcheckout_address->getAddressZone($address['zone_id']);
            $address = array_merge($address, $zone_data);
            unset($zone_data);
        }

        if ($state['session']['payment_address']['shipping_address'] == 1) {
            $payment_address = $this->getShippingAddressFromPaymentAddress();
            $address = array_replace_recursive($address, $payment_address);
        }

        return $address;

    }

    private function getDisplayShippingAddress(){
        $state = $this->model_extension_d_quickcheckout_store->getState();
        if(!$this->hasShipping()){
            $display = 0;
        }elseif($state['session']['account'] == 'logged' 
            && $state['session']['payment_address']['address_id'] != 0 ){
            $display = 1;
        }elseif($state['session']['payment_address']['shipping_address'] == 0){
            $display = 1;
        }else{
            $display = 0;
        }

        return $display;
    }

    private function getShippingAddressFromPaymentAddress(){
        $state = $this->model_extension_d_quickcheckout_store->getState();
        
        $shipping_address = array(
            'firstname' => '',
            'lastname' =>  '',
            'company' =>  '',
            'address_1' => '',
            'address_2' =>  '',
            'postcode' => '',
            'city' =>  '',
            'country_id' =>  '',
            'zone_id' => '',
            'country' => '',
            'iso_code_2' => '',
            'iso_code_3' => '',
            'address_format' =>'',
            'custom_field' =>  array(),
            'zone' => '',
            'zone_code' => '',
            'address_id' => 0
            );

        if(isset($state['session']['payment_address'])){
            foreach ($shipping_address as $field_id => $value) {
                $shipping_address[$field_id] = (isset($state['session']['payment_address'][$field_id])) ? $state['session']['payment_address'][$field_id] : '';
            }
        }

        return $shipping_address;
    }

    private function validateField($field, $value){
        $this->load->model('extension/d_quickcheckout/error');
        return $this->model_extension_d_quickcheckout_error->validateField('shipping_address', $field, $value);
    }

    private function hasShipping(){
        if($this->hasShipping == null){
            $this->hasShipping = $this->cart->hasShipping();
        }
        return $this->hasShipping;
    }
}
