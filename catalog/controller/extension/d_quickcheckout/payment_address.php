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
        $this->document->addScript('catalog/view/theme/default/javascript/d_quickcheckout/step/payment_address.js');

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
            if(!empty($data['data']['session']['payment_address'])){
                foreach($data['data']['session']['payment_address'] as $field => $value){
                    $this->updateField($field, $value);
                    $update = true;
                }
            }
            //REFACTOR - added other data like config and layout
            if(!empty($data['data']['config']) || !empty($data['data']['layout'])){
                $this->model_extension_d_quickcheckout_store->setState($data['data']);
            }
        }
        //updating customer group when changing account
        if($data['action'] == 'account/update/after' 
        && $this->model_extension_d_quickcheckout_store->isUpdated('account')
        ){  

            $state = $this->model_extension_d_quickcheckout_store->getState();
            $this->load->model('extension/d_quickcheckout/address');
            $zones = $this->model_extension_d_quickcheckout_address->getZonesByCountryId($state['session']['payment_address']['country_id']);
            $this->model_extension_d_quickcheckout_store->updateState(array('config', 'payment_address', 'fields', 'zone_id', 'options'), $zones);

            //If just logged in
            if($state['session']['account'] == 'logged'){
                $this->load->model('extension/d_quickcheckout/address');

                $addresses = $this->model_extension_d_quickcheckout_address->getAddresses();
                $update['session']['addresses'] = $addresses;
                reset($addresses);
                $address_id = key($addresses);

                $update['session']['payment_address'] = $this->getAddress($address_id);
                $update['session']['payment_address']['address_id'] = $address_id;
                if($state['session']['payment_address']['shipping_address'] == 1){
                    $update['session']['payment_address']['shipping_address'] = 0;
                }

                $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_address'), $update['session']['payment_address']);
                $this->model_extension_d_quickcheckout_store->updateState(array('session', 'addresses'), $update['session']['addresses']);
            }else{
                $this->load->model('extension/d_quickcheckout/account');
                $customer_groups = $this->model_extension_d_quickcheckout_account->getCustomerGroups();

                if($state['config'][$state['session']['account']]['payment_address']['fields']['customer_group_id']['options'] != $customer_groups) {

                    $this->model_extension_d_quickcheckout_store->updateState(array('config', 'payment_address', 'fields', 'customer_group_id', 'options'), $customer_groups);

                    $update = true;
                }  

                $default_customer_group_id = $this->model_extension_d_quickcheckout_account->getDefaultCustomerGroup();
                

                if(in_array($default_customer_group_id, $customer_groups) && empty($state['session']['payment_address']['customer_group_id'])){
                    $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_address', 'customer_group_id' ), $default_customer_group_id);
                }
                

                $this->load->model('extension/d_quickcheckout/address');
                $zones = $this->model_extension_d_quickcheckout_address->getZonesByCountryId($state['session']['payment_address']['country_id']);

                $this->model_extension_d_quickcheckout_store->updateState(array('config', 'payment_address', 'fields', 'zone_id', 'options'), $zones);
            }
            
        }

        if($data['action'] == 'confirm/update'){
            if($this->model_extension_d_quickcheckout_error->isCheckoutValid()){
                $state = $this->model_extension_d_quickcheckout_store->getState();
                if($state['session']['account'] == 'register'){
                    $this->load->model('account/customer');
                    $this->model_account_customer->addCustomer($state['session']['payment_address']);

                    if($this->customer->login($state['session']['payment_address']['email'], $state['session']['payment_address']['password'])){

                        $this->load->model('extension/d_quickcheckout/order');
                        $this->model_extension_d_quickcheckout_order->initCart();

                        $this->model_extension_d_quickcheckout_store->updateState(array('text_account_login'), $this->getAccountLoginText());

                        $update['session']['account'] = 'logged';

                        $this->load->model('extension/d_quickcheckout/address');


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
                        
                        if($state['session']['payment_address']['shipping_address'] == 1){
                            $update['session']['payment_address']['shipping_address'] = 0;
                        }
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_address'), $update['session']['payment_address']);
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'addresses'), $update['session']['addresses']);
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'account'), $update['session']['account']);
            
                    }
                }
                
                if($state['session']['account'] == 'logged' && $state['session']['payment_address']['address_id']==0){
                    $this->load->model('extension/d_quickcheckout/address');
                    $address_id = $this->model_extension_d_quickcheckout_address->addAddress($state['session']['payment_address']);
                    $state['session']['payment_address']['address_id'] = $address_id; 
                    $update['session']['addresses'] = $this->model_extension_d_quickcheckout_address->getAddresses();

                    $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_address'), $state['session']['payment_address']);
                    $this->model_extension_d_quickcheckout_store->updateState(array('session', 'addresses'), $update['session']['addresses']);
            
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
            if(!empty($state['config'][$state['session']['account']][$step]['fields'][$field_id]['display'])
            && !empty($state['config'][$state['session']['account']][$step]['fields'][$field_id]['require'])
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
                        $state['errors'][$step][$field_id] = '';
                    }
                }
            }else{
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
        

        if($this->validateField($field, $value)){
            $state = $this->model_extension_d_quickcheckout_store->getState();
            $state['session']['payment_address'][$field] = $value;
            
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
                    if($this->model_extension_d_quickcheckout_store->isUpdated('payment_address_'.$field) && $value != 0){
                        $state['session']['payment_address'] = $this->getAddress($value);
                    }

                    if($state['session']['payment_address']['address_id'] != 0){
                        $state['session']['payment_address']['shipping_address'] = 0;
                    }

                    if($state['session']['payment_address']['address_id'] == 0){
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_address'), $this->getDefault($populate = false));
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
            'address_id' => 0
        );

        //init custom fields
        foreach($default as $key => $field){
            if(!empty($field['custom'])){
                $address[$key] = $field['value'];

                $part = explode('-', $key);
                if(isset($part[2]) && is_numeric($part[2])){
                    if($part[0] == 'custom'){
                        $location = $part[1];
                        $custom_field_id = $part[2];
                    }
                }

                $custom_field = array( 
                    $location => array( 
                        $custom_field_id => $field['value']
                    )
                );
                $address['custom_field'] = array_merge($address['custom_field'], $custom_field);
            }
        }


        foreach($address as $key => $value){
            if(isset($payment_address[$key])){
                $address[$key] = $payment_address[$key];
            }elseif(isset($default[$key]) && isset($default[$key]['value'])){
                $address[$key] = $default[$key]['value'];
            }
        }
        $address['customer_group_id'] = $this->model_extension_d_quickcheckout_account->getDefaultCustomerGroup();
        
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
