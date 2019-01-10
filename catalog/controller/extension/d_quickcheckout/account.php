<?php

class ControllerExtensionDQuickcheckoutAccount extends Controller {
    private $route = 'extension/d_quickcheckout/login';

    public $action = array(
        'account/update',
        'payment_address/update/after'
    );

    public function __construct($registry){
        parent::__construct($registry);

        $this->load->model('extension/d_quickcheckout/store');
        $this->load->model('extension/d_quickcheckout/account');

    }

    public function index($config){
        $this->document->addScript('catalog/view/theme/default/javascript/d_quickcheckout/step/account.js');

        $state = $this->model_extension_d_quickcheckout_store->getState();

        $state['config'] = $this->getConfig();
        $this->model_extension_d_quickcheckout_store->setState($state);
        $state['session']['account'] = $this->getDefault();
        $this->model_extension_d_quickcheckout_account->updateGuest();
        

        if($state['session']['account'] == 'logged'){
            $this->load->model('extension/d_quickcheckout/address');
            $state['session']['addresses'] = $this->model_extension_d_quickcheckout_address->getAddresses();
        }else{
            $state['session']['addresses'] = false;
        }

        $state['language']['account'] = $this->getLanguages();
        $state['action']['account'] = $this->action;

        $this->model_extension_d_quickcheckout_store->setState($state);
    }


    public function update(){
        $this->model_extension_d_quickcheckout_store->loadState();
        $this->model_extension_d_quickcheckout_store->dispatch('account/update/before', $this->request->post);
        $this->model_extension_d_quickcheckout_store->dispatch('account/update', $this->request->post);

        $data = $this->model_extension_d_quickcheckout_store->getStateUpdated();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function receiver($data){
        $this->load->model('extension/d_quickcheckout/store');

        if($data['action'] == 'account/update'){
            if(!empty($data['data']['session']['account'])){
                $this->model_extension_d_quickcheckout_store->updateState(array('session', 'account'), $data['data']['session']['account']);
            }
            
            if(isset($data['data']['session']['email'])
            && isset($data['data']['session']['password'])){
                $this->login($data['data']['session']['email'], $data['data']['session']['password']);
            }
            
            //REFACTOR - added other data like config and layout
            if(!empty($data['data']['config']) || !empty($data['data']['layout'])){
                $this->model_extension_d_quickcheckout_store->setState($data['data']);
            }

            //dispatch new state
            $this->model_extension_d_quickcheckout_store->dispatch('account/update/after', $data);
        }

        if($data['action'] == 'payment_address/update/after'){
            $this->load->model('extension/d_quickcheckout/account');
            $this->model_extension_d_quickcheckout_account->updateGuest();
        }
    }

    

    public function validate(){

        $this->model_extension_d_quickcheckout_store->updateState(array('errors', 'account', 'login'), '');
        return true;
    }
    

    private function login($email, $password){     
        $this->load->language('checkout/checkout');

        $data = array();
        if (!$this->customer->login($email, $password)) {
            $data['errors']['account']['login'] = $this->language->get('error_login');
        }

        $this->load->model('account/customer');
//REFACTOR
        $customer_info = $this->model_account_customer->getCustomerByEmail($email);

        if(VERSION < '3.0.0.0'){
            if ($customer_info && !$customer_info['approved']) {
                $data['errors']['account']['login'] = $this->language->get('error_approved');
            }
        }else{
            if ($customer_info && !$customer_info['status']) {
                $data['errors']['account']['login'] = $this->language->get('error_approved');
            }
        }


        if (!$data) {
            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = array(
                'customer_id' => $this->customer->getId(),
                'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
            );

            $this->model_account_activity->addActivity('login', $activity_data);
            $data['session']['account'] = 'logged';
            $this->model_extension_d_quickcheckout_store->updateState(array('text_account_login'), $this->getAccountLoginText());
        }

        $this->model_extension_d_quickcheckout_store->setState($data);

    }

    private function getConfig(){
        $this->load->config('d_quickcheckout/account');
        $config = $this->config->get('d_quickcheckout_account');

        $result = array();
        $settings = $this->model_extension_d_quickcheckout_store->getSetting();
        foreach($config['account'] as $account => $value){

            if(!empty($settings['config'][$account]['account'])){
                $result[$account]['account'] = $settings['config'][$account]['account'];
            }else{
                $result[$account]['account'] = array_replace_recursive($config, $value);
            }
        }

        $result['guest']['account']['social_login']['value'] = $this->getDSocialLogin();

        return $result;
    }

    private function getLanguages(){
        $this->load->language('checkout/checkout');
        $this->load->language('extension/d_quickcheckout/account');

        $result = array();
        $languages = $this->config->get('d_quickcheckout_account_language');

        foreach ($languages as $key => $language) {
            $result[$key] = $this->language->get($language);
        }

        $language = $this->model_extension_d_quickcheckout_store->getLanguage();
        if(isset($language['account'])){
            $result = array_replace_recursive($result, $language['account']);
        }

        $result['image'] = HTTPS_SERVER.'image/catalog/d_quickcheckout/step/account.svg';
        return $result;
    }


    private function getDefault(){
        $state = $this->model_extension_d_quickcheckout_store->getState();
        return $this->model_extension_d_quickcheckout_account->getDefaultAccount($state['config']['guest']['account']['default_option']);

    }

    private function getDSocialLogin(){

        if(file_exists(DIR_APPLICATION.'controller/extension/module/d_social_login.php')){
            $sql = "SELECT * FROM " . DB_PREFIX . "setting WHERE `key` = 'd_social_login_status'";
        
            $query = $this->db->query($sql);
            if(!empty($query->row) && $query->row['value']){
                return $this->load->controller('extension/module/d_social_login/index');
            }
        }
        return false;
    }

    private function getAccountLoginText(){
        $output = $this->load->controller('common/header');
        $html_dom = new d_simple_html_dom();
        $html_dom->load((string)$output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);
        $text = (string)$html_dom->find('#top-links > ul > li', 1)->innertext;
        return $text;
    }

}