<?php
/*
 *  location: catalog/controller
 */

class ControllerExtensionModuleDQuickcheckout extends Controller {
    private $id = 'd_quickcheckout';
    private $route = 'extension/module/d_quickcheckout';
    private $sub_versions = array('lite', 'light', 'free');
    private $mbooth = '';
    private $config_file = '';
    private $prefix = '';
    private $error = array(); 
    private $debug = false;
    private $setting = array();
    private $current_setting_id = '';

    public function __construct($registry) {
        parent::__construct($registry);

        $this->load->model('extension/module/d_quickcheckout');
        $this->load->model('extension/d_quickcheckout/address');
        $this->load->model('extension/d_quickcheckout/method');
        $this->load->model('extension/d_quickcheckout/order');
        $this->load->model('extension/d_quickcheckout/custom_field');
        $this->load->model('extension/d_opencart_patch/load');
        $this->load->model('account/address');
        // $this->load->language($this->route);
        $_SESSION['d_quickcheckout_minify'] = 1;
        $this->session->data['d_quickcheckout_debug'] = $this->config->get('d_quickcheckout_debug');

        $this->mbooth = $this->model_extension_module_d_quickcheckout->getMboothFile($this->id, $this->sub_versions);

        $this->config_file = $this->model_extension_module_d_quickcheckout->getConfigFile($this->id, $this->sub_versions);

        $this->current_setting_id = $this->model_extension_module_d_quickcheckout->getCurrentSettingId($this->id, $this->config->get('config_store_id'));
    }

    public function index() {
        $this->model_extension_module_d_quickcheckout->logWrite('ControllerModuleDQuickcheckout Start...');

        if(!$this->config->get('d_quickcheckout_status')){
            $this->model_extension_module_d_quickcheckout->logWrite('d_quickcheckout_status off. Exit.');
            return false;
        }

        $this->initialize();

        $this->model_extension_module_d_quickcheckout->logWrite('Load Styles and Scripts.');
        $this->document->addStyle('catalog/view/javascript/d_quickcheckout/library/phoneformat/css/intlTelInput.css');
        if($this->setting['design']['bootstrap']){
            $this->document->addStyle('catalog/view/theme/default/stylesheet/d_quickcheckout/bootstrap.css');
        }
        $this->document->addStyle('catalog/view/theme/default/stylesheet/d_quickcheckout/d_quickcheckout.css');
        $this->document->addStyle('catalog/view/theme/default/stylesheet/d_quickcheckout/theme/'.$this->setting['design']['theme'].'.css');
        $this->document->addScript('catalog/view/javascript/d_quickcheckout/library/jquery-validate/jquery.validate.min.js');
        $this->document->addScript('catalog/view/javascript/d_quickcheckout/library/jquery-maskedinput/jquery.maskedinput.min.js');
        $this->document->addScript('catalog/view/javascript/d_quickcheckout/library/underscore/underscore-min.js');
        $this->document->addScript('catalog/view/javascript/d_quickcheckout/library/backbone/backbone-min.js');
        $this->document->addScript('catalog/view/javascript/d_quickcheckout/library/phoneformat/js/intlTelInput.js');
        if(!$this->setting['general']['compress']){
            $this->document->addScript('catalog/view/javascript/d_quickcheckout/library/backbone-nested/backbone-nested.js');
            //$this->document->addScript('catalog/view/javascript/d_quickcheckout/library/backbone/backbone.validation.min.js');
            $this->document->addScript('catalog/view/javascript/d_quickcheckout/main.js');
            $this->document->addScript('catalog/view/javascript/d_quickcheckout/engine/model.js');
            $this->document->addScript('catalog/view/javascript/d_quickcheckout/engine/view.js');
        }else{
            if(!file_exists('catalog/view/javascript/d_quickcheckout/compress/d_quickcheckout.min.js')){
               require_once(DIR_SYSTEM . 'library/d_compress/compress.php');
            }
            $this->document->addScript('catalog/view/javascript/d_quickcheckout/compress/d_quickcheckout.min.js');
        }
        
        $data['json_config'] = json_encode($this->setting);
        $data['config'] = $this->setting;
        
        
        $data['login'] = $this->load->controller('extension/d_quickcheckout/login', $this->setting);
        $data['field'] = $this->load->controller('extension/d_quickcheckout/field', $this->setting);
        $data['payment_address'] = $this->load->controller('extension/d_quickcheckout/payment_address', $this->setting);
        $data['shipping_address'] = $this->load->controller('extension/d_quickcheckout/shipping_address', $this->setting);
        $data['shipping_method'] = $this->load->controller('extension/d_quickcheckout/shipping_method', $this->setting);
        $data['payment_method'] = $this->load->controller('extension/d_quickcheckout/payment_method', $this->setting);
        $data['cart'] = $this->load->controller('extension/d_quickcheckout/cart', $this->setting);
        $data['payment'] = $this->load->controller('extension/d_quickcheckout/payment', $this->setting);
        $data['confirm'] = $this->load->controller('extension/d_quickcheckout/confirm', $this->setting);
        
        return $this->model_extension_d_opencart_patch_load->view('module/d_quickcheckout', $data);

    }

     
    public function initialize(){

        $data = $this->model_extension_module_d_quickcheckout->getConfigSetting($this->id, $this->id.'_setting', $this->config->get('config_store_id'), $this->config_file, (!empty($this->session->data['payment_address']['customer_group_id'])) ? $this->session->data['payment_address']['customer_group_id'] : $this->config->get('config_customer_group_id'));
     
        

        $this->model_extension_module_d_quickcheckout->logWrite('Initialize:: current_setting_id = '.$this->current_setting_id);

        $this->model_extension_module_d_quickcheckout->logWrite('Initialize:: getConfigData('.$this->id.', '. $this->id.'_setting' .', '.$this->config->get('config_store_id').', '.$this->config_file .') = ' . json_encode($data));

$this->load->model('extension/d_quickcheckout/address');
        //prepare config data
        $data['step']['payment_address']['fields']['country_id']['options'] = $this->model_extension_d_quickcheckout_address->getCountries();
        $data['step']['payment_address']['fields']['zone_id']['options'] = $this->model_extension_d_quickcheckout_address->getZonesByCountryId($this->model_extension_d_quickcheckout_address->getPaymentAddressCountryId());
        $data['step']['payment_address']['fields']['customer_group_id']['options'] = $this->model_extension_d_quickcheckout_address->getCustomerGroups();
        $data['step']['shipping_address']['fields']['country_id']['options'] = $this->model_extension_d_quickcheckout_address->getCountries();
        $data['step']['shipping_address']['fields']['zone_id']['options'] = $this->model_extension_d_quickcheckout_address->getZonesByCountryId($this->model_extension_d_quickcheckout_address->getShippingAddressCountryId());

        foreach($data['account'] as $account => $account_data){
            $data['account'][$account] =  $this->model_extension_module_d_quickcheckout->array_merge_r_d($account_data, $data['step']);
        }

        $this->model_extension_module_d_quickcheckout->logWrite('Initialize:: prepare setting for accounts');

        $field_count = array(
            'guest' => array('payment_address' => 0, 'shipping_address' => 0, 'confirm' => 0),
            'register' => array('payment_address' => 0, 'shipping_address' => 0, 'confirm' => 0),
            'logged' => array('payment_address' => 0, 'shipping_address' => 0, 'confirm' => 0)
        );
        foreach($data['account'] as $account => $account_data){
            foreach($data['account'][$account]['payment_address']['fields'] as $field){
                if(isset($field['display']) && $field['display']){
                    $field_count[$account]['payment_address'] += 1;
                }   
            }
            foreach($data['account'][$account]['shipping_address']['fields'] as $field){
                if(isset($field['display']) && $field['display']){
                    $field_count[$account]['shipping_address'] += 1;
                }   
            }
            foreach($data['account'][$account]['confirm']['fields'] as $field){
                if(isset($field['display']) && $field['display']){
                    $field_count[$account]['confirm'] += 1;
                }   
            }
        }

        $this->model_extension_module_d_quickcheckout->logWrite('Initialize:: count fields for statistics: '.json_encode($field_count));
 
        $this->load->language('extension/module/d_quickcheckout');
        $this->load->language('checkout/checkout');
        $data = $this->model_extension_module_d_quickcheckout->languageFilter($data);

        $this->model_extension_module_d_quickcheckout->logWrite('Initialize:: prepare languages');
        // check for different versions.
        foreach($data['account'] as $account => $account_data){
            $data['account'][$account]['payment_address']['fields']['newsletter']['title'] = sprintf($account_data['payment_address']['fields']['newsletter']['title'], $this->config->get('config_name'));
        }
        $data['trigger'] = $this->model_extension_module_d_quickcheckout->getConfigData($this->id, $this->id.'_trigger', $this->config->get('config_store_id'), $this->config_file);
        $data['general']['debug'] = $this->model_extension_module_d_quickcheckout->getConfigData($this->id, $this->id.'_debug', $this->config->get('config_store_id'), $this->config_file);


        $this->model_extension_module_d_quickcheckout->logWrite('Initialize:: prepare setting and session->data[d_quickcheckout]');

        //prepare session data
        if($this->customer->isLogged()){
            $this->session->data['account'] = 'logged';
        }else{
            $this->session->data['account'] = (!empty($this->session->data['account']) && $this->session->data['account'] !== 'logged') ? $this->session->data['account'] : $data['step']['login']['default_option'];
        }

        $account = $this->session->data['account'];

        unset($data['step']);

        $this->session->data['d_quickcheckout'] = $data;
        $this->setting = $data; 

        $this->model_extension_module_d_quickcheckout->logWrite('Initialize:: set $this->session->data[account] = ' . $this->session->data['account']);
        $customer_group_id = (!empty($this->session->data['payment_address']['customer_group_id'])) ? $this->session->data['payment_address']['customer_group_id'] : $this->config->get('config_customer_group_id');

        if($this->setting['general']['clear_session']){
            $this->session->data['guest'] = array(
                'customer_group_id' => $this->config->get('config_customer_group_id'),
                'firstname' => $this->setSessionValue('firstname','payment_address', $data, $account, false),
                'lastname' => $this->setSessionValue('lastname','payment_address', $data, $account, false),
                'email' => $this->setSessionValue('email','payment_address', $data, $account, false),
                'password' => $this->setSessionValue('password','payment_address', $data, $account, false),
                'telephone' => $this->setSessionValue('telephone','payment_address', $data, $account, false),
                'fax' => $this->setSessionValue('fax','payment_address', $data, $account, false),
                'custom_field' => $this->model_extension_d_quickcheckout_custom_field->setCustomFieldsDefaultSessionData('account', $customer_group_id ),
                'shipping_address' => $this->setSessionValue('shipping_address','payment_address', $data, $account, false),
            );
            $this->session->data['payment_address'] = array(
                'firstname' => $this->setSessionValue('firstname','payment_address', $data, $account, false),
                'lastname' => $this->setSessionValue('lastname','payment_address', $data, $account, false),
                'email' => $this->setSessionValue('email','payment_address', $data, $account, false),
                'email_confirm' => '',
                'telephone' => $this->setSessionValue('telephone','payment_address', $data, $account, false),
                'fax' => $this->setSessionValue('fax','payment_address', $data, $account, false),
                'password' => $this->setSessionValue('password','payment_address', $data, $account, false),
                'confirm' => '',
                'customer_group_id' => $this->config->get('config_customer_group_id'),
                'company' => $this->setSessionValue('company','payment_address', $data, $account, false),
                'address_1' => $this->setSessionValue('address_1','payment_address', $data, $account, false),
                'address_2' => $this->setSessionValue('address_2','payment_address', $data, $account, false),
                'postcode' => $this->setSessionValue('postcode','payment_address', $data, $account, false),
                'city' => $this->setSessionValue('city','payment_address', $data, $account, false),
                'country_id' => $this->setSessionValue('country_id','payment_address', $data, $account, false, $this->config->get('config_country_id')),
                'zone_id' => $this->setSessionValue('zone_id','payment_address', $data, $account, false, $this->config->get('config_zone_id')),
                'country' => '',
                'iso_code_2' => '',
                'iso_code_3' => '',
                'address_format' => '',
                'custom_field' => '',
                'zone' => '',
                'zone_code' => '',
                'agree' => $this->setSessionValue('agree','payment_address', $data, $account, false),
                'shipping_address' => $this->setSessionValue('shipping_address','payment_address', $data, $account, false),
                'newsletter' => $this->setSessionValue('newsletter','payment_address', $data, $account, false),
                //'address_id' => $this->customer->getAddressId(),
               
            );
            $this->session->data['shipping_address'] = array(
                'firstname' => $this->setSessionValue('firstname','shipping_address', $data, $account, false),
                'lastname' => $this->setSessionValue('lastname','shipping_address', $data, $account, false),
                'company' => $this->setSessionValue('company','shipping_address', $data, $account, false),
                'address_1' => $this->setSessionValue('address_1','shipping_address', $data, $account, false),
                'address_2' => $this->setSessionValue('address_2','shipping_address', $data, $account, false),
                'postcode' => $this->setSessionValue('postcode','shipping_address', $data, $account, false),
                'city' => $this->setSessionValue('city','shipping_address', $data, $account, false),
                'country_id' => $this->setSessionValue('country_id','shipping_address', $data, $account, false, $this->config->get('config_country_id')),
                'zone_id' => $this->setSessionValue('zone_id','shipping_address', $data, $account, false, $this->config->get('config_zone_id')),
                'country' => '',
                'iso_code_2' => '',
                'iso_code_3' => '',
                'address_format' => '',
                'custom_field' => '',
                'zone' => '',
                'zone_code' => '',
                //'address_id' => $this->customer->getAddressId(),
            );
            $this->session->data['confirm'] = array(

                'comment' =>  $this->setSessionValue('comment','confirm', $data, $account, false),
                'agree' =>  $this->setSessionValue('agree','confirm', $data, $account, false),

            );

        }else{
        
            $this->session->data['guest'] = array(
                'customer_group_id' => $customer_group_id,
                'firstname' => $this->setSessionValue('firstname','payment_address', $data, $account),
                'lastname' => $this->setSessionValue('lastname','payment_address', $data, $account),
                'email' =>  $this->setSessionValue('email','payment_address', $data, $account),
                'password' =>  $this->setSessionValue('password','payment_address', $data, $account),
                'telephone' =>  $this->setSessionValue('telephone','payment_address', $data, $account),
                'fax' =>  $this->setSessionValue('fax','payment_address', $data, $account),
                'custom_field' => (!empty($this->session->data['payment_address']['custom_field']['account'])) ? array('account' => $this->session->data['payment_address']['custom_field']['account']) : $this->model_extension_d_quickcheckout_custom_field->setCustomFieldsDefaultSessionData('account', $customer_group_id ),
                'shipping_address' =>  $this->setSessionValue('shipping_address','payment_address', $data, $account),
                );
            
            $this->session->data['payment_address'] = array(
                'firstname' => $this->setSessionValue('firstname','payment_address', $data, $account),
                'lastname' => $this->setSessionValue('lastname','payment_address', $data, $account),
                'email' => $this->setSessionValue('email','payment_address', $data, $account),
                'email_confirm' => '',
                'telephone' => $this->setSessionValue('telephone','payment_address', $data, $account),
                'fax' => $this->setSessionValue('fax','payment_address', $data, $account),
                'password' => $this->setSessionValue('password','payment_address', $data, $account),
                'confirm' => '',
                'customer_group_id' => $customer_group_id ,
                'company' => $this->setSessionValue('company','payment_address', $data, $account),
                'address_1' => $this->setSessionValue('address_1','payment_address', $data, $account),
                'address_2' => $this->setSessionValue('address_2','payment_address', $data, $account),
                'postcode' => $this->setSessionValue('postcode','payment_address', $data, $account),
                'city' => $this->setSessionValue('city','payment_address', $data, $account),
                'country_id' =>  $this->setSessionValue('country_id','payment_address', $data, $account, true, $this->config->get('config_country_id')),
                'zone_id' => $this->setSessionValue('zone_id','payment_address', $data, $account, true, $this->config->get('config_zone_id')),
                'country' => $this->setSessionValue('country','payment_address', $data, $account),
                'iso_code_2' => $this->setSessionValue('iso_code_2','payment_address', $data, $account),
                'iso_code_3' => $this->setSessionValue('iso_code_3','payment_address', $data, $account),
                'address_format' => $this->setSessionValue('address_format','payment_address', $data, $account),
                'custom_field' => ((!empty($this->session->data['payment_address']['custom_field']['account'])) ? array('account' => $this->session->data['payment_address']['custom_field']['account']) : $this->model_extension_d_quickcheckout_custom_field->setCustomFieldsDefaultSessionData('account', $customer_group_id)) + ((!empty($this->session->data['payment_address']['custom_field']['address'])) ? array('address' => $this->session->data['payment_address']['custom_field']['address']) :  $this->model_extension_d_quickcheckout_custom_field->setCustomFieldsDefaultSessionData('address', $customer_group_id)),
                'zone' => $this->setSessionValue('zone','payment_address', $data, $account),
                'zone_code' => $this->setSessionValue('zone_code','payment_address', $data, $account),
                'agree' => $this->setSessionValue('agree','payment_address', $data, $account),
                'shipping_address' => $this->setSessionValue('shipping_address','payment_address', $data, $account),
                'newsletter' => $this->setSessionValue('newsletter','payment_address', $data, $account),
                //'address_id' => (!empty($this->session->data['payment_address']['address_id'])) ? $this->session->data['payment_address']['address_id'] : $this->customer->getAddressId(),

            );
             
            $this->session->data['shipping_address'] = array(
                'firstname' =>  $this->setSessionValue('firstname','shipping_address', $data, $account),
                'lastname' =>  $this->setSessionValue('lastname','shipping_address', $data, $account),
                'company' =>  $this->setSessionValue('company','shipping_address', $data, $account),
                'address_1' =>  $this->setSessionValue('address_1','shipping_address', $data, $account),
                'address_2' => $this->setSessionValue('address_2','shipping_address', $data, $account),
                'postcode' => $this->setSessionValue('postcode','shipping_address', $data, $account),
                'city' => $this->setSessionValue('city','shipping_address', $data, $account),
                'country_id' => $this->setSessionValue('country_id','shipping_address', $data, $account, true, $this->config->get('config_country_id')),
                'zone_id' => $this->setSessionValue('zone_id','shipping_address', $data, $account, true, $this->config->get('config_zone_id')),
                'country' => $this->setSessionValue('country','shipping_address', $data, $account),
                'iso_code_2' => $this->setSessionValue('iso_code_2','shipping_address', $data, $account),
                'iso_code_3' => $this->setSessionValue('iso_code_3','shipping_address', $data, $account),
                'address_format' =>  $this->setSessionValue('address_format','shipping_address', $data, $account),
                'custom_field' => ((!empty($this->session->data['shipping_address']['custom_field']['address'])) ? array('address' => $this->session->data['shipping_address']['custom_field']['address']) : $this->model_extension_d_quickcheckout_custom_field->setCustomFieldsDefaultSessionData('address', $customer_group_id )),
                'zone' =>  $this->setSessionValue('zone','shipping_address', $data, $account),
                'zone_code' => $this->setSessionValue('zone_code','shipping_address', $data, $account),
                //'address_id' => (!empty($this->session->data['shipping_address']['address_id'])) ? $this->session->data['shipping_address']['address_id'] : $this->customer->getAddressId(),
            );
        }

        $this->session->data['payment_address'] = $this->model_extension_d_quickcheckout_address->prepareAddress($this->session->data['payment_address']);
        $this->session->data['shipping_address'] = $this->model_extension_d_quickcheckout_address->prepareAddress($this->session->data['shipping_address']);

        if($this->customer->isLogged()){

            if(empty($this->session->data['payment_address']['address_id'])){
                $this->session->data['payment_address']['address_id'] = $this->customer->getAddressId();


            }

            if(!empty($this->session->data['payment_address']['address_id']) && $this->session->data['payment_address']['address_id'] != 'new'){
                $address = $this->model_extension_d_quickcheckout_address->getAddress($this->session->data['payment_address']['address_id']);
                if($address){
                    $this->session->data['payment_address'] = array_replace($this->session->data['payment_address'], $address);
                }else{
                    $this->session->data['payment_address']['address_id'] = 'new';
                }
                
            }

            if(empty($this->session->data['shipping_address']['address_id'])){
                $this->session->data['shipping_address']['address_id'] = $this->customer->getAddressId();
            }

            if(!empty($this->session->data['shipping_address']['address_id']) && $this->session->data['shipping_address']['address_id'] != 'new'){
                $address = $this->model_extension_d_quickcheckout_address->getAddress($this->session->data['shipping_address']['address_id']);
                if($address){
                    $this->session->data['shipping_address'] = array_replace($this->session->data['shipping_address'], $address);
                }else{
                    $this->session->data['shipping_address']['address_id'] = 'new';
                }
                
            }

            $this->session->data['payment_address']['custom_field'] = (!empty($this->session->data['payment_address']['custom_field'])) ? array('address' => $this->session->data['payment_address']['custom_field']) :  $this->model_extension_d_quickcheckout_custom_field->setCustomFieldsDefaultSessionData('address', $customer_group_id);
            $this->session->data['shipping_address']['custom_field'] = (!empty($this->session->data['shipping_address']['custom_field'])) ? array('address' => $this->session->data['shipping_address']['custom_field']) : $this->model_extension_d_quickcheckout_custom_field->setCustomFieldsDefaultSessionData('address', $customer_group_id );

        }

        $this->session->data['payment_address'] = $this->session->data['payment_address'] + $this->model_extension_d_quickcheckout_custom_field->getCustomFieldsSessionData('guest', 'account', $customer_group_id );
        $this->session->data['payment_address'] = $this->session->data['payment_address'] + $this->model_extension_d_quickcheckout_custom_field->getCustomFieldsSessionData('payment_address', 'address', $customer_group_id );
        $this->session->data['shipping_address'] = $this->session->data['shipping_address'] + $this->model_extension_d_quickcheckout_custom_field->getCustomFieldsSessionData('shipping_address', 'address', $customer_group_id );
        
        $this->model_extension_module_d_quickcheckout->logWrite('Initialize:: set session payment address'.json_encode($this->session->data['payment_address']));
        $this->model_extension_module_d_quickcheckout->logWrite('Initialize:: set session shipping address'.json_encode($this->session->data['shipping_address']));
        
        $this->model_extension_d_quickcheckout_address->updateTaxAddress();

        $this->load->controller('extension/d_quickcheckout/shipping_method/prepare');

        $this->session->data['comment'] = (!empty($this->session->data['comment'])) ? $this->session->data['comment'] : $data['account'][$account]['confirm']['fields']['comment']['value'];
        $this->session->data['confirm'] = array(
            'comment' => '',
            'agree' =>  $this->setSessionValue('agree','confirm', $data, $account),
        );

        $totals = array();
        $taxes = $this->cart->getTaxes();
        $total = 0;

        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
        );
     
        $this->session->data['totals'] = $this->model_extension_d_quickcheckout_order->getTotals($total_data);
        
        $this->load->controller('extension/d_quickcheckout/payment_method/prepare');

        $statistic = array('account' => $this->session->data['account'], 'field' => $field_count);

        if($this->model_extension_d_quickcheckout_order->isRecreateOrder()) {
            $this->session->data['order_id'] = $this->createOrder();
            $this->session->data['statistic'] = $statistic;
            $this->session->data['statistic_id'] = $this->model_extension_module_d_quickcheckout->setStatistic($this->current_setting_id, $this->session->data['order_id'], $this->session->data['statistic'], $this->customer->getId());
        }

        $this->load->controller('extension/d_quickcheckout/confirm/updateOrder');
        
        
        $this->model_extension_module_d_quickcheckout->logWrite('Initialize:: create new Order_id and prepare $this->session->data');

        //statistic
        
        
    }

    private function setSessionValue($field, $step, $data, $account, $session = true, $default = ''){
        $value = '';

        if($session && isset($this->session->data[$step][$field])){
            $value = $this->session->data[$step][$field]; 
        }elseif(isset($data['account'][$account][$step]['fields'][$field])){
            if(isset($data['account'][$account][$step]['fields'][$field]['value'])){
                $value = $data['account'][$account][$step]['fields'][$field]['value'];
            }
        }

        if(!$value){
            $value = $default;
        }

        return $value;
        
    }

    public function createOrder(){
        $order_data = array();
        
        $totals = array();
        $taxes = $this->cart->getTaxes();
        $total = 0;

        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
        );

        $this->model_extension_d_quickcheckout_order->getTotals($total_data);
        $this->load->language('checkout/checkout');

        if(isset($this->session->data['payment_address']['zone_id'])){
            $order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
        }else{
            $order_data['payment_zone_id'] = $this->config->get('config_zone_id');
        }
        if(isset($this->session->data['payment_address']['country_id'])){
           $order_data['payment_country_id'] = $this->session->data['payment_address']['country_id']; 
        }else{
           $order_data['payment_country_id'] = $this->config->get('config_country_id');
        }
        
        $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
        $order_data['store_id'] = $this->config->get('config_store_id');
        $order_data['store_name'] = $this->config->get('config_name');

        if ($order_data['store_id']) {
            $order_data['store_url'] = $this->config->get('config_url');
        } else {
            $order_data['store_url'] = HTTP_SERVER;
        }

        $order_data['total'] = $total;

        if (isset($this->request->cookie['tracking'])) {
            $order_data['tracking'] = $this->request->cookie['tracking'];

            $subtotal = $this->cart->getSubTotal();

            // Affiliate
            $this->load->model('affiliate/affiliate');

            $affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);

            if ($affiliate_info) {
                $order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
                $order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
            } else {
                $order_data['affiliate_id'] = 0;
                $order_data['commission'] = 0;
            }

            // Marketing
            $this->load->model('checkout/marketing');

            $marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

            if ($marketing_info) {
                $order_data['marketing_id'] = $marketing_info['marketing_id'];
            } else {
                $order_data['marketing_id'] = 0;
            }
        } else {
            $order_data['affiliate_id'] = 0;
            $order_data['commission'] = 0;
            $order_data['marketing_id'] = 0;
            $order_data['tracking'] = '';
        }

        $order_data['language_id'] = $this->config->get('config_language_id');
        $order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
        $order_data['currency_code'] = $this->session->data['currency'];
        $order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
        $order_data['ip'] = $this->request->server['REMOTE_ADDR'];

        if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
            $order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
            $order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
        } else {
            $order_data['forwarded_ip'] = '';
        }

        if (isset($this->request->server['HTTP_USER_AGENT'])) {
            $order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
        } else {
            $order_data['user_agent'] = '';
        }

        if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
            $order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
        } else {
            $order_data['accept_language'] = '';
        }

        return $this->model_extension_d_quickcheckout_order->addOrder($order_data);
    }
}