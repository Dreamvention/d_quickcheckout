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
    }

    /**
     * Initialization
     */
    public function index($config){
        $this->document->addScript('catalog/view/theme/default/javascript/d_quickcheckout/step/confirm.js');

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
            $state['session']['confirm'] = $this->getDefault();
            if($this->model_extension_d_quickcheckout_error->isCheckoutValid()){
                $state['session']['confirm']['checkout'] = true;
            }

            $this->model_extension_d_quickcheckout_store->setState($state);
            $this->model_extension_d_quickcheckout_store->dispatch('confirm/update/after', $this->request->get);
        }
    }

    public function validate(){
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

    private function getDefault(){
        return array(
            'checkout' => false,
            'trigger' => $this->getConfirmTrigger()
        );
    }
}