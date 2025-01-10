<?php
namespace Opencart\Catalog\Controller\Extension\AjaxQuickCheckout\AjaxQuickCheckout;

class Account extends \Opencart\System\Engine\Controller {
    private $route = 'extension/ajax_quick_checkout/ajax_quick_checkout/login';

    public $action = array(
        'account/update',
        'payment_address/update/after'
    );
    private $pro = '';

    public function __construct($registry){
        parent::__construct($registry);
        
        $this->load->model('extension/ajax_quick_checkout/module/ajax_quick_checkout');
        $this->model_extension_ajax_quick_checkout_module_ajax_quick_checkout->loadDependencies();
        
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/order');
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/store');
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/account');
        if(is_file(DIR_EXTENSION.'ajax_quick_checkout_pro/install.json')) $this->pro .= '_pro';
        
        $this->config->addPath(DIR_EXTENSION . 'ajax_quick_checkout' . $this->pro . '/system/config/');
    }

    public function index(){
        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();

        $state['config'] = $this->getConfig();
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState($state);
        $state['session']['account'] = $this->getDefault();
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_account->updateGuest();


        if($state['session']['account'] == 'logged'){
            $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/address');
            $state['session']['addresses'] = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_address->getAddresses();
        }else{
            $state['session']['addresses'] = false;
        }

        $state['language']['account'] = $this->getLanguages();
        $state['action']['account'] = $this->action;




        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState($state);
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_order->updateOrder();
    }


    public function update(){
        $rawData = file_get_contents('php://input');
        $post = json_decode($rawData, true);
        if(!$post){
            $post = $this->request->post;
        }
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->loadState();
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('account/update/before', $post);
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('account/update', $post);
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('total/update', $post);

        $data = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getStateUpdated();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function receiver($data){
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/store');

        if($data['action'] == 'account/update'){
            if(!empty($data['data']['session']['account'])){
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('session', 'account'), $data['data']['session']['account']);
            }

            if(isset($data['data']['session']['email'])
            && isset($data['data']['session']['password'])){
                $this->login($data['data']['session']['email'], $data['data']['session']['password']);
            }

            //REFACTOR - added other data like config and layout
            if(!empty($data['data']['config']) || !empty($data['data']['layout'])){
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState($data['data']);
            }

            //dispatch new state
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('account/update/after', $data);
        }

        if($data['action'] == 'payment_address/update/after'){
            $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/account');
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_account->updateGuest();
        }
    }



    public function validate(){

        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('errors', 'account', 'login'), '');
        return true;
    }


    private function login($email, $password){
        $this->load->language('account/login');

        $this->load->model('account/customer');

        $data = array();
        if (!$this->customer->login($email, $password)) {
            $data['errors']['account']['login'] = $this->language->get('error_login');
            $this->model_account_customer->addLoginAttempt($email);
        }

        $this->load->model('account/customer');
//REFACTOR
        $customer_info = $this->model_account_customer->getCustomerByEmail($email);


        if ($customer_info && !$customer_info['status']) {
            $data['errors']['account']['login'] = $this->language->get('error_approved');
        }



        if (!$data) {
            $this->model_account_customer->addLogin($this->customer->getId(), $this->request->server['REMOTE_ADDR']);

            $this->session->data['customer'] = [
				'customer_id'       => $customer_info['customer_id'],
				'customer_group_id' => $customer_info['customer_group_id'],
				'firstname'         => $customer_info['firstname'],
				'lastname'          => $customer_info['lastname'],
				'email'             => $customer_info['email'],
				'telephone'         => $customer_info['telephone'],
				'custom_field'      => $customer_info['custom_field']
			];
            // Create customer token
            $this->load->model('extension/dv_opencart_patch/helper/general');
            $this->session->data['customer_token'] = $this->model_extension_dv_opencart_patch_helper_general->token(26);
            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = array(
                'customer_id' => $this->customer->getId(),
                'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
            );

            $this->model_account_activity->addActivity('login', $activity_data);
            $data['session']['account'] = 'logged';
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('text_account_login'), $this->getAccountLoginText());
        }

        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState($data);

    }

    private function getConfig(){
        $this->load->config('ajax_quick_checkout/account');
        $config = $this->config->get('ajax_quick_checkout_account');

        $result = array();
        $settings = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getSetting();
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
        $this->load->language('checkout/payment_address');
        $this->load->language('account/login');
        $this->load->language('account/register');
        $this->load->language('extension/ajax_quick_checkout/ajax_quick_checkout/account');



        $result = array();

        $languages = $this->config->get('ajax_quick_checkout_account_language');

        foreach ($languages as $key => $language) {
            $result[$key] = $this->language->get($language);
        }

        $language = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getLanguage();
        if(isset($language['account'])){
            $result = array_replace_recursive($result, $language['account']);
        }

        if (is_file(DIR_IMAGE . 'catalog/ajax_quick_checkout/step/account.svg')) {
            $result['image'] = HTTP_SERVER.'image/catalog/ajax_quick_checkout/step/account.svg';
        } else {
            $result['image'] = HTTP_SERVER.'extension/ajax_quick_checkout/image/catalog/ajax_quick_checkout/step/account.svg';
        }
        return $result;
    }


    private function getDefault(){
        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
        return $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_account->getDefaultAccount($state['config']['guest']['account']['default_option']);

    }

    private function getDSocialLogin(){

        if(file_exists(DIR_EXTENSION.'social_login/module/d_social_login.php')){
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
        $html_dom = new \Opencart\System\Library\Extension\DvSimpleHtmlDom\DvSimpleHtmlDom();
        $html_dom->load((string)$output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);
        //Check if exist elem '#top-links > ul > li'
        $find = $html_dom->find('#top-links > ul > li', 1);
        $text = !empty($find) ? (string)$find->innertext : '';
        return $text;
    }
}
