<?php

class ControllerExtensionDQuickcheckoutConfirm extends Controller {
    private $route = 'd_quickcheckout/confirm';

    public $action = array(
        'confirm/update/before',
        'confirm/update'
    );

    public function __construct($registry){
        parent::__construct($registry);

        $this->load->model('extension/d_quickcheckout/store');
        $this->load->model('extension/d_quickcheckout/method');
        $this->load->model('extension/d_quickcheckout/error');
        $this->load->model('extension/d_quickcheckout/page');
    }

    /**
     * Initialization
     */
    public function index($config){
        $this->document->addScript('catalog/view/theme/default/javascript/d_quickcheckout/step/confirm.js');

        $pages = $this->model_extension_d_quickcheckout_page->getActivePages();
        $page_id = (isset($pages[0])) ? $pages[0] : false;

        $this->model_extension_d_quickcheckout_store->updateState(array('session','pages'), $pages);
        $this->model_extension_d_quickcheckout_store->updateState(array('session','page_id'), $page_id);

        $state = $this->model_extension_d_quickcheckout_store->getState();
        $state['session']['confirm'] = $this->getDefault();
        $state['config'] = $this->getConfig();

        $state['language']['confirm'] = $this->getLanguages();
        $state['action']['confirm'] = $this->action;

        $this->model_extension_d_quickcheckout_store->setState($state);
    }

    /**
     * update via ajax
     */
    public function update(){
        $this->model_extension_d_quickcheckout_store->loadState();
        $this->model_extension_d_quickcheckout_store->dispatch('confirm/update/before', $this->request->post);
        $this->model_extension_d_quickcheckout_store->dispatch('confirm/update', $this->request->post);

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

        if($data['action'] == 'confirm/update/before'){
            $this->model_extension_d_quickcheckout_error->validateCheckout();
        }

        //updating payment_method value
        if($data['action'] == 'confirm/update'){
            if(isset($data['data']['page_id'])){
                $page_id = $data['data']['page_id'];
                if($this->validatePage($page_id)){
                    $this->model_extension_d_quickcheckout_store->updateState(array('session','page_id'), $this->getNextPage($page_id));
                }
            }else{
                $state['session']['confirm'] = $this->getDefault();
                if($this->model_extension_d_quickcheckout_error->isCheckoutValid()){
                    $state['session']['confirm']['checkout'] = true;
                }
                $this->model_extension_d_quickcheckout_store->updateState(array('session','confirm','checkout'), $state['session']['confirm']['checkout']);
            }
            $this->model_extension_d_quickcheckout_store->dispatch('confirm/update/after', $this->request->get);
        }
    }

    public function validate(){
        return true;
    }

    private function validatePage($page_id){
        $state = $this->model_extension_d_quickcheckout_store->getState();

        if(isset($state['layout']['pages'][$page_id])){
            

            $this->load->model('extension/d_quickcheckout/error');
            return $this->model_extension_d_quickcheckout_error->validatePage($page_id);

        }
        return true;
    }

    private function getConfirmTrigger(){
        $state = $this->model_extension_d_quickcheckout_store->getState();
        if(!empty($state['session']['confirm']['trigger'])){
            return $state['session']['confirm']['trigger'];
        }else{
            $this->load->config('d_quickcheckout/confirm');
            $config = $this->config->get('d_quickcheckout_confirm');
            return $config['trigger'];
        }
    }

    private function getConfig(){        
        $this->load->config('d_quickcheckout/confirm');
        $config = $this->config->get('d_quickcheckout_confirm');

        $settings = $this->model_extension_d_quickcheckout_store->getSetting();
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
        $this->load->language('checkout/checkout');
        $this->load->language('extension/d_quickcheckout/confirm');

        $result = array();
        $languages = $this->config->get('d_quickcheckout_confirm_language');

        foreach ($languages as $key => $language) {
            $result[$key] = $this->language->get($language);
        }

        $language = $this->model_extension_d_quickcheckout_store->getLanguage();
        if(isset($language['confirm'])){
            $result = array_replace_recursive($result, $language['confirm']);
        }

        $result['image'] = HTTPS_SERVER.'image/catalog/d_quickcheckout/step/confirm.svg';

        return $result;
    }

    private function getNextPage($page_id){
        $state = $this->model_extension_d_quickcheckout_store->getState();
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
            'trigger' => $this->getConfirmTrigger()
        );
    }
}