<?php

class ControllerExtensionDQuickcheckoutPaymentMethod extends Controller {
    private $route = 'd_quickcheckout/payment_method';

    public $action = array(
        'payment_method/update',
        'payment_address/update/after',
        'cart/update/after',
        'total/update/after'
    );

    public function __construct($registry){
        parent::__construct($registry);

        $this->load->model('extension/d_quickcheckout/store');
        $this->load->model('extension/d_quickcheckout/method');

    }
    /**
     * Initialization
     */
    public function index($config){
        $this->document->addScript('catalog/view/theme/default/javascript/d_quickcheckout/step/payment_method.js');

        $state = $this->model_extension_d_quickcheckout_store->getState();
            
        $state['session']['payment_methods'] = $this->getPaymentMethods();
        $this->model_extension_d_quickcheckout_store->setState($state);

        $state['config'] = $this->getConfig();
        $this->model_extension_d_quickcheckout_store->setState($state);
        $state['session']['payment_method'] = $this->getPaymentMethod();

        $state['language']['payment_method'] = $this->getLanguages();
        $state['action']['payment_method'] = $this->action;
        $this->model_extension_d_quickcheckout_store->setState($state);
        $this->validate();
    }

    /**
     * update via ajax
     */
    public function update(){
        $this->model_extension_d_quickcheckout_store->loadState();
        $this->model_extension_d_quickcheckout_store->dispatch('payment_method/update/before', $this->request->post);
        $this->model_extension_d_quickcheckout_store->dispatch('payment_method/update', $this->request->post);

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
        $update_method = false;

        //updating payment_method value
        if($data['action'] == 'payment_method/update'){

            if($data['data']['payment_method']){
                if(is_string($data['data']['payment_method'])){
                    $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment_method'), $this->getPaymentMethod($data['data']['payment_method']));
                    $update = true;
                }
            }
        }

        //updating payment_methods after payment_address change
        if($data['action'] == 'payment_address/update/after' 
            && (
                $this->model_extension_d_quickcheckout_store->isUpdated('payment_address_country_id')
                || $this->model_extension_d_quickcheckout_store->isUpdated('payment_address_zone_id')
                || $this->model_extension_d_quickcheckout_store->isUpdated('payment_address_address_id')
                || $this->model_extension_d_quickcheckout_store->isUpdated('payment_address_postcode')
            )
        ){
            $update_method = true;
            $update = true;
        }

        //updating payment_methods after cart change
        if($data['action'] == 'cart/update/after'){
            $update_method = true;
            $update = true;
        }

        //updating payment_methods after total has been changed - may trigger a duplicate update.
        if($data['action'] == 'total/update/after' && $this->model_extension_d_quickcheckout_store->isUpdated('totals')){
            $update_method = true;
        }

        if($update_method){
            $this->model_extension_d_quickcheckout_store->updateState(array('session','payment_methods'), $this->getPaymentMethods());
            $this->model_extension_d_quickcheckout_store->updateState(array('session','payment_method'), $this->getPaymentMethod());
            $this->validate();
        }

        //should bot be triggered after payment method has been updated the second time from total changes.
        if($update){
            $this->model_extension_d_quickcheckout_store->dispatch('payment_method/update/after', $data);
        }
    }

    /**
     * Validate 
     * Validate checks if the step is valid to continue the checkout
     */
    public function validate(){
        $this->load->language('checkout/checkout');
        $state = $this->model_extension_d_quickcheckout_store->getState();
        $result = true;
        if(empty($state['errors']['payment_method'])){
            $state['errors']['payment_method'] = array();
        }
        $state['errors']['payment_method']['error_payment'] = false;
        if(!$this->model_extension_d_quickcheckout_method->getFirstPaymentMethod()){
            $state['errors']['payment_method']['error_no_payment'] = $this->language->get('error_no_payment');
            $result = false;
        }else{
            $state['errors']['payment_method']['error_no_payment'] = '';
            if(empty($state['session']['payment_method'] )){
                $state['errors']['payment_method']['error_payment'] = $this->language->get('error_payment');
                $result = false;
            }
        }

        $this->model_extension_d_quickcheckout_store->updateState(array('errors','payment_method'), $state['errors']['payment_method']);

        return $result;
    }

    private function getConfig(){
        $this->load->config('d_quickcheckout/payment_method');
        $config = $this->config->get('d_quickcheckout_payment_method');
        
        $settings = $this->model_extension_d_quickcheckout_store->getSetting();
        $result = array();
        foreach($config['account'] as $account => $value){
            if(!empty($settings['config'][$account]['payment_method'])){
                $result[$account]['payment_method'] = $settings['config'][$account]['payment_method'];
            }else{
                $result[$account]['payment_method'] = array_replace_recursive($config, $value);
            }
        }

        return $result;
    }

    private function getLanguages(){
        $this->load->language('checkout/checkout');
        $this->load->language('extension/d_quickcheckout/payment_method');

        $result = array();
        $languages = $this->config->get('d_quickcheckout_payment_method_language');

        foreach ($languages as $key => $language) {
            $result[$key] = $this->language->get($language);
        }

        $language = $this->model_extension_d_quickcheckout_store->getLanguage();
        if(isset($language['payment_method'])){
            $result = array_replace_recursive($result, $language['payment_method']);
        }

        $result['image'] = HTTPS_SERVER.'image/catalog/d_quickcheckout/step/payment_method.svg';

        return $result;
    }

    private function getDefault(){
        $state = $this->model_extension_d_quickcheckout_store->getState();
        return $this->model_extension_d_quickcheckout_method->getDefaultPaymentMethod($state['config']['guest']['payment_method']['default_option']);
    }

    private function getPaymentMethod($payment_method = false){
        if(!$payment_method){
            $state = $this->model_extension_d_quickcheckout_store->getState();
            if(!empty($state['session']['payment_method'])){
                $payment_method = $state['session']['payment_method']['code'];
            }
        }
        return $this->model_extension_d_quickcheckout_method->getDefaultPaymentMethod((string)$payment_method);
    }

    private function getPaymentMethods(){

        $state = $this->model_extension_d_quickcheckout_store->getState();
        $new_payment_methods = $this->model_extension_d_quickcheckout_method->getPaymentMethods($state['session']['payment_address'], $state['session']['total']);

        if(isset($state['session']['payment_methods'])){
            foreach($state['session']['payment_methods'] as $key => $value){
                if(!isset($new_payment_methods[$key])){
                        $new_payment_methods[$key] = false;
                }
            }
        }
        

        return $new_payment_methods;
    }

}
