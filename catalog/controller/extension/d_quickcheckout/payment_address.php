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
        $this->document->addScript('catalog/view/theme/default/javascript/d_quickcheckout/step/payment_address.js?'.rand());

        $this->model_extension_d_quickcheckout_store->updateState(array('config', 'payment_address', 'fields', 'zone_id', 'options'), array());

        $state = $this->model_extension_d_quickcheckout_store->getState();

        if($state['session']['account'] == 'logged'){
            $this->load->model('extension/d_quickcheckout/address');
            $addresses = $this->model_extension_d_quickcheckout_address->getAddresses();
            if($addresses){
                $this->model_extension_d_quickcheckout_store->updateState(array('session','addresses'), $addresses);
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

        
    }

    /**
     *  Update
     *
     *  Called via AJAX to update state by current module.
     *  Returns updated state.
     *
     */
    public function update(){
        $this->model_extension_d_quickcheckout_store->loadState();
        $this->model_extension_d_quickcheckout_store->dispatch('payment_address/update/before', $this->request->post);

        $this->model_extension_d_quickcheckout_store->dispatch('payment_address/update', $this->request->post);
        $data = $this->model_extension_d_quickcheckout_store->getStateUpdated();

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
            if(!empty($data['data']['payment_address'])){
                foreach($data['data']['payment_address'] as $field => $value){
                    $this->updateField($field, $value);
                    $update = true;
                }
            }
        }

        //updating customer group when changing account
        if($data['action'] == 'account/update/after' 
        && $this->model_extension_d_quickcheckout_store->isUpdated('account')
        ){  

            $state = $this->model_extension_d_quickcheckout_store->getState();

            //If just logged in
            if($state['session']['account'] == 'logged'){
                $this->load->model('extension/d_quickcheckout/address');

                $addresses = $this->model_extension_d_quickcheckout_address->getAddresses();
                $update['session']['addresses'] = $addresses;
                reset($addresses);
                $address_id = key($addresses);

                $update['session']['payment_address'] = $this->getAddress($address_id);
                $update['session']['payment_address']['address_id'] = $address_id;
                $update['session']['payment_address']['shipping_address'] = 0;

                $this->model_extension_d_quickcheckout_store->setState($update);

            }else{
                $this->load->model('extension/d_quickcheckout/account');
                $customer_groups = $this->model_extension_d_quickcheckout_account->getCustomerGroups();

                if($state['config'][$state['session']['account']]['payment_address']['fields']['customer_group_id']['options'] != $customer_groups) {

                    $this->model_extension_d_quickcheckout_store->updateState(array('config', 'payment_address', 'fields', 'customer_group_id', 'options'), $customer_groups);

                    $update = true;
                }
            }
            
        }

        if($data['action'] == 'confirm/update'){
            if($this->model_extension_d_quickcheckout_error->isCheckoutValid()){
                $state = $this->model_extension_d_quickcheckout_store->getState();
                if($state['session']['account'] == 'register'){
                    $this->load->model('account/customer');
                    $this->model_account_customer->addCustomer($this->session->data['payment_address']);

                    if($this->customer->login($this->session->data['payment_address']['email'], $this->session->data['payment_address']['password'])){

                        $update['session']['account'] = 'logged';

                        $this->load->model('extension/d_quickcheckout/address');
                        $addresses = $this->model_extension_d_quickcheckout_address->getAddresses();
                        $update['session']['addresses'] = $addresses;
                        reset($addresses);
                        $address_id = key($addresses);

                        $update['session']['payment_address']['address_id'] = $address_id; 
                        $update['session']['payment_address'] = $this->model_extension_d_quickcheckout_address->getAddress($address_id);
                        
                        $this->model_extension_d_quickcheckout_store->setState($update);
                    }
                }
                if($state['session']['account'] == 'logged' && $state['session']['payment_address']['address_id']==0){
                    $this->load->model('extension/d_quickcheckout/address');
                    $address_id = $this->model_extension_d_quickcheckout_address->addAddress($this->session->data['payment_address']);
                    $update['session']['payment_address']['address_id'] = $address_id; 
                    $this->model_extension_d_quickcheckout_store->setState($update);
                }
            }
        }

        if($update){
            $this->model_extension_d_quickcheckout_store->dispatch('payment_address/update/after', $data);
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

        foreach($state['session']['payment_address'] as $field_id => $value){
            if(!empty($state['config'][$state['session']['account']][$step]['fields'][$field_id]['require'])
            && !empty($state['config'][$state['session']['account']][$step]['fields'][$field_id]['errors'])
            ){
                $errors = $state['config'][$state['session']['account']][$step]['fields'][$field_id]['errors'];
                $no_errors = true;
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
                        break;
                    }
                }
            }else{
                $state['errors'][$step][$field_id] = false;
            }
            
        }

        $this->model_extension_d_quickcheckout_store->setState($state);

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
        
        $state['session']['payment_address'][$field] = $value;

        if($this->validateField($field, $value)){
            //$state = $this->model_extension_d_quickcheckout_store->getState('session');
            
            $this->model_extension_d_quickcheckout_store->setState($state);

            switch ($field){

                //if country_id is modified
                case 'country_id' :
                    if($this->model_extension_d_quickcheckout_store->isUpdated('payment_address_'.$field)){
                        $this->load->model('extension/d_quickcheckout/address');
                        $this->model_extension_d_quickcheckout_address->setPaymentAddressCountry($value);

                        $state['session']['payment_address']['zone_id'] = '';
                        $this->model_extension_d_quickcheckout_store->setState($state);

                        $zones = $this->model_extension_d_quickcheckout_address->getZonesByCountryId($value);
                        $this->model_extension_d_quickcheckout_store->updateState(array('config', 'payment_address', 'fields', 'zone_id', 'options'), $zones);
                    }
                    break;

                //if zone_id is modified
                case 'zone_id' :
                    if($this->model_extension_d_quickcheckout_store->isUpdated('payment_address_'.$field)){
                        $this->load->model('extension/d_quickcheckout/address');
                        $this->model_extension_d_quickcheckout_address->setPaymentAddressZone($value);
                    }
                    break;

                //if address_id is modified
                case 'address_id':
                    if($this->model_extension_d_quickcheckout_store->isUpdated('payment_address_'.$field)){
                        $state['session']['payment_address'] = $this->getAddress($value);
                    }

                    if($state['session']['payment_address']['address_id'] != 0){
                        $state['session']['payment_address']['shipping_address'] = 0;
                    }

                    $this->model_extension_d_quickcheckout_store->setState($state);
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

                default: 

                    if(isset($state['config']['guest']['payment_address']['fields'][$field])){
                        if($state['config']['guest']['payment_address']['fields'][$field]['custom']){
                            $location = $state['config']['guest']['payment_address']['fields'][$field]['location'];
                            $custom_field_id = $state['config']['guest']['payment_address']['fields'][$field]['custom_field_id'];
                        }
                    }else{
                        $part = explode('_', $field);
                        if(isset($part[1]) && is_numeric($part[1])){
                            $location = $part[0];
                            $custom_field_id = $part[1];
                        }
                        
                    }

                    if(isset($location)){
                        $state['session']['payment_address']['custom_field'][$location][$custom_field_id] = $value;
                        $this->model_extension_d_quickcheckout_store->setState($state);
                    }
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
        $this->load->model('localisation/language');

        $this->load->config('d_quickcheckout/payment_address');
        $config = $this->config->get('d_quickcheckout_payment_address');

        $settings = $this->model_extension_d_quickcheckout_store->getSetting();
        $result = array();
        foreach($config['account'] as $account => $value){

            if(!empty($settings['config'][$account]['payment_address'])){
                $result[$account]['payment_address'] = $settings['config'][$account]['payment_address'];
            }else{
                $result[$account]['payment_address'] = array_replace_recursive($config, $value);
            }

            $this->load->model('extension/d_quickcheckout/address');
            $result[$account]['payment_address']['fields']['country_id']['options'] = $this->model_extension_d_quickcheckout_address->getCountries();
            $result[$account]['payment_address']['fields']['customer_group_id']['options'] = $this->model_extension_d_quickcheckout_account->getCustomerGroups();
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

        $result['image'] = HTTPS_SERVER.'image/catalog/d_quickcheckout/step/payment_address.svg';

        return $result;

    }


    private function getAddress($address_id){
        $this->load->model('extension/d_quickcheckout/address');
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

        $payment_address = array();
        $state = $this->model_extension_d_quickcheckout_store->getState();
        if($populate){

            

            if(isset($state['session']) && isset($state['session']['payment_address'])){
                if($state['session']['account'] == 'logged'
                && !empty($state['session']['payment_address']['address_id'])
                && !empty($state['session']['addresses']) 
                && !empty($state['session']['addresses'][$state['session']['payment_address']['address_id']])){
                    foreach($state['session']['addresses'][$state['session']['payment_address']['address_id']] as $field_id => $value){
                        $state['session']['payment_address'][$field_id] = $value;
                    }
                }

                $payment_address = $state['session']['payment_address'];
            }
        }
        $default = $state['config'][$state['session']['account']]['payment_address']['fields'];

        return array(
            'firstname' => (isset($payment_address['firstname'])) ? $payment_address['firstname'] : $default['firstname']['value'],
            'lastname' => (isset($payment_address['lastname'])) ? $payment_address['lastname'] : $default['lastname']['value'],
            'email' => (isset($payment_address['email'])) ? $payment_address['email'] : $default['email']['value'],
            'email_confirm' => (isset($payment_address['email_confirm'])) ? $payment_address['email_confirm'] : $default['email_confirm']['value'],
            'telephone' => (isset($payment_address['telephone'])) ? $payment_address['telephone'] : $default['telephone']['value'],
            'fax' => (isset($payment_address['fax'])) ? $payment_address['fax'] : $default['fax']['value'],
            'password' => (isset($payment_address['password'])) ? $payment_address['password'] : '',
            'confirm' => '',
            'customer_group_id' =>  $this->model_extension_d_quickcheckout_account->getDefaultCustomerGroup(),
            'company' => (isset($payment_address['company'])) ? $payment_address['company'] : $default['company']['value'],
            'address_1' => (isset($payment_address['address_1'])) ? $payment_address['address_1'] : $default['address_1']['value'],
            'address_2' => (isset($payment_address['address_2'])) ? $payment_address['address_2'] : $default['address_2']['value'],
            'postcode' => (isset($payment_address['postcode'])) ? $payment_address['postcode'] : $default['postcode']['value'],
            'city' => (isset($payment_address['city'])) ? $payment_address['city'] : $default['city']['value'],
            'country_id' => (isset($payment_address['country_id'])) ? $payment_address['country_id'] : $default['country_id']['value'],
            'zone_id' => (isset($payment_address['zone_id'])) ? $payment_address['zone_id'] : $default['zone_id']['value'],
            'country' => (isset($payment_address['country'])) ? $payment_address['country'] : '',
            'iso_code_2' => (isset($payment_address['iso_code_2'])) ? $payment_address['iso_code_2'] : '',
            'iso_code_3' => (isset($payment_address['iso_code_3'])) ? $payment_address['iso_code_3'] : '',
            'address_format' => (isset($payment_address['address_format'])) ? $payment_address['address_format'] : '',
            'custom_field' =>  array(),
            'zone' => (isset($payment_address['zone'])) ? $payment_address['zone'] : '',
            'zone_code' => (isset($payment_address['zone_code'])) ? $payment_address['zone_code'] : '',
            'agree' => (isset($payment_address['agree'])) ? $payment_address['agree'] : $default['agree']['value'],
            'shipping_address' => (isset($payment_address['shipping_address'])) ? $payment_address['shipping_address'] : $default['shipping_address']['value'],
            'newsletter' => (isset($payment_address['newsletter'])) ? $payment_address['newsletter'] : $default['newsletter']['value'],
            'address_id' => (isset($payment_address['address_id'])) ? $payment_address['address_id'] : 0
        );

    }

    private function validateField($field, $value){
        $this->load->language('checkout/checkout');
        $this->load->model('extension/d_quickcheckout/error');
        return $this->model_extension_d_quickcheckout_error->validateField('payment_address', $field, $value);
    }
}
