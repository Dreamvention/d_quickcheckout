<?php

namespace Opencart\Catalog\Controller\Extension\AjaxQuickCheckout\AjaxQuickCheckout;

class Confirm extends \Opencart\System\Engine\Controller {
    private $route = 'ajax_quick_checkout/confirm';

    public $action = array(
        'confirm/update/before',
        'confirm/update'
    );

    private $pro = '';

    public function __construct($registry){
        parent::__construct($registry);
        
        if(is_file(DIR_EXTENSION.'ajax_quick_checkout_pro/install.json')) $this->pro .= '_pro';

        $this->config->addPath(DIR_EXTENSION . 'ajax_quick_checkout' . $this->pro . '/system/config/');

        $this->load->model('extension/ajax_quick_checkout/module/ajax_quick_checkout');
        $this->model_extension_ajax_quick_checkout_module_ajax_quick_checkout->loadDependencies();
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/order');
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/store');
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/method');
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/error');
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/page');
    }

    /**
     * Initialization
     */
    public function index(){
        $pages = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_page->getActivePages();
        $page_id = (isset($pages[0])) ? $pages[0] : false;

        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('session','pages'), $pages);
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('session','page_id'), $page_id);

        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
        $state['session']['confirm'] = $this->getDefault();
        $state['config'] = $this->getConfig();

        $state['language']['confirm'] = $this->getLanguages();
        $state['action']['confirm'] = $this->action;

        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState($state);
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_order->updateOrder();
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
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->loadState();
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_order->updateOrder();

        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('confirm/update/before', $post);
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('confirm/update', $post);

        $data = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getStateUpdated();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    /**
     * Receiver
     * Receiver listens to dispatch of events and accepts data array with action and state
     */
    public function receiver($data){
        $update = false;

        if($data['action'] == 'confirm/update/before'){
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_error->validateCheckout();
        }

        //updating payment_method value
        if($data['action'] == 'confirm/update'){
            if(isset($data['data']['page_id'])){
                $page_id = $data['data']['page_id'];
                if($this->validatePage($page_id)){
                    $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('session','page_id'), $this->getNextPage($page_id));
                }
            }else{
                $state['session']['confirm'] = $this->getDefault();
                if($this->model_extension_ajax_quick_checkout_ajax_quick_checkout_error->isCheckoutValid()){
                    $state['session']['confirm']['checkout'] = true;
                }
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('session','confirm','checkout'), $state['session']['confirm']['checkout']);
            }
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('confirm/update/after', $this->request->get);

        }

    }

    public function validate(){
        return true;
    }

    private function validatePage($page_id){
        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();

        if(isset($state['layout']['pages'][$page_id])){


            $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/error');
            return $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_error->validatePage($page_id);

        }
        return true;
    }

    private function getConfirmTrigger(){
        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();

        if(!empty($state['session']['confirm']['trigger'])){
            return $state['session']['confirm']['trigger'];
        }else{
            $this->load->config('ajax_quick_checkout/confirm');
            $config = $this->config->get('ajax_quick_checkout_confirm');
            return $config['trigger'];
        }
    }

    private function getConfig(){
        $this->load->config('ajax_quick_checkout/confirm');
        $config = $this->config->get('ajax_quick_checkout_confirm');

        $settings = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getSetting();
        $result = array();
        foreach($config['account'] as $account => $value){
            if(!empty($settings['config'][$account]['confirm'])){
                $result[$account]['confirm'] = $settings['config'][$account]['confirm'];
            }else{
                $result[$account]['confirm'] = array_replace_recursive($config, $value);
            }
        }

        return $result;
    }

    private function getLanguages(){
        $this->load->language('checkout/confirm');
        $this->load->language('extension/ajax_quick_checkout/ajax_quick_checkout/confirm');

        $result = array();
        $languages = $this->config->get('ajax_quick_checkout_confirm_language');

        foreach ($languages as $key => $language) {
            $result[$key] = $this->language->get($language);
        }

        $language = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getLanguage();
        if(isset($language['confirm'])){
            $result = array_replace_recursive($result, $language['confirm']);
        }
        if (is_file(DIR_IMAGE . 'catalog/ajax_quick_checkout/step/confirm.svg')) {
            $result['image'] = HTTP_SERVER.'image/catalog/ajax_quick_checkout/step/confirm.svg';
        } else {
            $result['image'] = HTTP_SERVER.'extension/ajax_quick_checkout/image/catalog/ajax_quick_checkout/step/confirm.svg';
        }
        

        return $result;
    }

    private function getNextPage($page_id){
        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
        $pages = array_keys($state['layout']['pages']);
        $page_index = array_search($page_id, $pages);
        $pages_total = count($pages);

        if($pages_total){
            $page_index++;
            if($page_index < $pages_total){
                return $pages[$page_index];
            }
        }
        return $page_id;
    }

    private function getDefault(){
        return array(
            'checkout' => false,
            'trigger' => $this->getConfirmTrigger(),
            'loading' => false,
        );
    }
}
