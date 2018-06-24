<?php

class ControllerExtensionDQuickcheckoutShippingAddress extends Controller {
    private $route = 'd_quickcheckout/shipping_address';

    public $action = array(
        'shipping_address/update',
        'payment_address/update/after',
        'account/update/after'
    );

    public function __construct($registry){
        parent::__construct($registry);

        $this->load->model('extension/d_quickcheckout/store');
        $this->load->model('extension/d_quickcheckout/address');
        $this->load->model('extension/d_quickcheckout/account');

    }

    /**
    * Initialization
    */
    public function index($config){
        $this->document->addScript('catalog/view/theme/default/javascript/d_quickcheckout/step/shipping_address.js');
        $this->model_extension_d_quickcheckout_store->updateState(array('config', 'shipping_address', 'fields', 'zone_id', 'options'), array());
        $state = $this->model_extension_d_quickcheckout_store->getState();

        $state['session']['shipping_address'] = $this->getDefault();
        $this->model_extension_d_quickcheckout_store->setState($state);

        $state['config'] = $this->getConfig();
        $state['language']['shipping_address'] = $this->getLanguages();
        $state['action']['shipping_address'] = $this->action;
        $this->model_extension_d_quickcheckout_store->setState($state);
    }

    /**
    * update via ajax
    */
    public function update(){
        $this->model_extension_d_quickcheckout_store->loadState();
        $this->model_extension_d_quickcheckout_store->dispatch('shipping_address/update/before', $this->request->post);
        $this->model_extension_d_quickcheckout_store->dispatch('shipping_address/update', $this->request->post);

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
            if(!empty($data['data']['shipping_address'])){
                foreach($data['data']['shipping_address'] as $field => $value){
                    $this->updateField($field, $value);
                    $update = true;
                }
            }
        }

        if($data['action'] == 'payment_address/update/after'){
            
            //edit payment_address/shipping_address
            if($this->model_extension_d_quickcheckout_store->isUpdated('payment_address_shipping_address')){
                $display_shipping_address = $this->getDisplayShippingAddress();
                $this->model_extension_d_quickcheckout_store->updateState(array('config', 'shipping_address', 'display'), $display_shipping_address);
            }

            //is shipping show
            
            if($this->getDisplayShippingAddress()){
                $state = $this->model_extension_d_quickcheckout_store->getState('session');
                if($this->model_extension_d_quickcheckout_store->isUpdated('account') && $state['session']['account'] == 'logged'){
                
                    $this->load->model('extension/d_quickcheckout/address');
                    $address_id = $this->model_extension_d_quickcheckout_address->addAddress($this->session->data['shipping_address']);
                    $update['session']['shipping_address']['address_id'] = $address_id; 
                    $update['session']['addresses'] = $this->model_extension_d_quickcheckout_address->getAddresses();
                    $this->model_extension_d_quickcheckout_store->setState($update);

                }
            }else{
                $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address'), $this->getShippingAddressFromPaymentAddress());
                $update = true;
            }

        }

        if($data['action'] == 'confirm/update'){
            if($this->model_extension_d_quickcheckout_error->isCheckoutValid()){
                if($state['session']['shipping_address']['address_id'] == 0 && $this->getDisplayShippingAddress()){
                    $this->load->model('extension/d_quickcheckout/address');
                    $address_id = $this->model_extension_d_quickcheckout_address->addAddress($this->session->data['shipping_address']);
                    $update['session']['shipping_address']['address_id'] = $address_id;
                    $update['session']['addresses'] = $this->model_extension_d_quickcheckout_address->getAddresses();
                    $this->model_extension_d_quickcheckout_store->setState($update);
                }else{
                    $this->model_extension_d_quickcheckout_store->updateState(array('session', 'shipping_address'), $this->getShippingAddressFromPaymentAddress());
                }
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

        foreach($state['session']['shipping_address'] as $field_id => $value){
            if(!empty($state['config'][$state['session']['account']][$step]['fields'][$field_id]['require'])
            && !empty($state['config'][$state['session']['account']][$step]['fields'][$field_id]['errors'])
            ){
                $errors = $state['config'][$state['session']['account']][$step]['fields'][$field_id]['errors'];
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
                        $state['errors'][$step][$field_id] = false;
                    }
                }
            }else{
                $state['errors'][$step][$field_id] = false;
            }
        }

        $this->model_extension_d_quickcheckout_store->setState($state);

        return $result;
    }

    /**
    * logic for updating fields
    */
    private function updateField($field, $value){

        $state['session']['shipping_address'][$field] = $value;
        if($this->validateField($field, $value)){
            $this->model_extension_d_quickcheckout_store->setState($state);

            switch ($field){

                case 'country_id' :
                    if($this->model_extension_d_quickcheckout_store->isUpdated('shipping_address_'.$field)){
                        $this->model_extension_d_quickcheckout_address->setShippingAddressCountry($value);
                        
                        $update['session']['shipping_address']['zone_id'] = '';
                        $this->model_extension_d_quickcheckout_store->setState($update);

                        $zones = $this->model_extension_d_quickcheckout_address->getZonesByCountryId($value);
                        $this->model_extension_d_quickcheckout_store->updateState(array('config', 'shipping_address', 'fields', 'zone_id', 'options'), $zones);
                    }
                break;

                case 'zone_id' :
                    if($this->model_extension_d_quickcheckout_store->isUpdated('shipping_address_'.$field)){
                        $this->model_extension_d_quickcheckout_address->setShippingAddressZone($value);
                    }
                break;

                case 'address_id':
                    if($this->model_extension_d_quickcheckout_store->isUpdated('shipping_address_'.$field)){
                        $update['session']['shipping_address'] = $this->getAddress($value);
                        $this->model_extension_d_quickcheckout_store->setState($update);
                    }
                default: 
                    //nothing at the moment;
                break;
            }
        }else{
            $this->model_extension_d_quickcheckout_store->setState($state);
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

            $result[$account]['shipping_address']['fields']['country_id']['options'] = $this->model_extension_d_quickcheckout_address->getCountries();
            $result[$account]['shipping_address']['fields']['zone_id']['options'] = $this->model_extension_d_quickcheckout_address->getZonesByCountryId($state['session']['shipping_address']['country_id']);
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

        $this->load->model('extension/d_quickcheckout/account');

        $shipping_address = array();
        if($populate){
            $state = $this->model_extension_d_quickcheckout_store->getState();
            if(isset($state['session']) && isset($state['session']['shipping_address'])){
                if(!empty($state['session']['shipping_address']['address_id'])
                    && !empty($state['session']['addresses']) 
                    && !empty($state['session']['addresses'][$state['session']['shipping_address']['address_id']])){
                    foreach($state['session']['addresses'][$state['session']['shipping_address']['address_id']] as $field_id => $value){
                        $state['session']['shipping_address'][$field_id] = $value;
                    }
                }
                $shipping_address = $state['session']['shipping_address'];
            }
        }

        return array(
            'firstname' => (isset($shipping_address['firstname'])) ? $shipping_address['firstname'] : '',
            'lastname' => (isset($shipping_address['lastname'])) ? $shipping_address['lastname'] : '',
            'company' => (isset($shipping_address['company'])) ? $shipping_address['company'] : '',
            'address_1' => (isset($shipping_address['address_1'])) ? $shipping_address['address_1'] : '',
            'address_2' => (isset($shipping_address['address_2'])) ? $shipping_address['address_2'] : '',
            'postcode' => (isset($shipping_address['postcode'])) ? $shipping_address['postcode'] : '',
            'city' => (isset($shipping_address['city'])) ? $shipping_address['city'] : '',
            'country_id' => (isset($shipping_address['country_id'])) ? $shipping_address['country_id'] : '',
            'zone_id' => (isset($shipping_address['zone_id'])) ? $shipping_address['zone_id'] : '',
            'country' => (isset($shipping_address['country'])) ? $shipping_address['country'] : '',
            'iso_code_2' => (isset($shipping_address['iso_code_2'])) ? $shipping_address['iso_code_2'] : '',
            'iso_code_3' => (isset($shipping_address['iso_code_3'])) ? $shipping_address['iso_code_3'] : '',
            'address_format' => (isset($shipping_address['address_format'])) ? $shipping_address['address_format'] : '',
            'custom_field' =>  array(),
            'zone' => (isset($shipping_address['zone'])) ? $shipping_address['zone'] : '',
            'zone_code' => (isset($shipping_address['zone_code'])) ? $shipping_address['zone_code'] : '',
            'address_id' => (isset($shipping_address['address_id'])) ? $shipping_address['address_id'] : 0
            );

    }

    private function getDisplayShippingAddress(){
        $state = $this->model_extension_d_quickcheckout_store->getState();
        if(!$this->model_extension_d_quickcheckout_store->isUpdated('account')  && $state['session']['account'] == 'logged' && $state['session']['payment_address']['address_id'] != 0){
            $display = 1;
        }elseif(empty($state['session']['payment_address']['shipping_address'])){
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
}
