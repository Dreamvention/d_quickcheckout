<?php
namespace Opencart\Catalog\Controller\Extension\AjaxQuickCheckout\AjaxQuickCheckout;

class ShippingMethod extends \Opencart\System\Engine\Controller {
    private $route = 'ajax_quick_checkout/shipping_method';
    private $hasShipping = null;

    public $action = array(
        'shipping_method/update',
        'shipping_address/update/after',
        'cart/update/after'
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

    }
    /**
    * Initialization
    */
    public function index(){
        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();

    //set default values
        $state['session']['shipping_methods'] = $this->getShippingMethods();
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState($state);

        $state['config'] = $this->getConfig();
        $state['session']['shipping_method'] = $this->getShippingMethod($state['config']['guest']['shipping_method']['default_option']);

        $state['language']['shipping_method'] = $this->getLanguages();
        $state['action']['shipping_method'] = $this->action;

        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState($state);
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_order->updateOrder();
        $this->validate();

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
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('shipping_method/update/before', $post);
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('shipping_method/update', $post);
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('total/update', $post);

        $data = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getStateUpdated();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }


    /**
    * Receiver
    * Receiver listens to dispatch of events and accepts data array with action and state
    */
    public function receiver($data){
        $update_method = false;
        $update = [];

        //updating shipping_method value
        if($data['action'] == 'shipping_method/update'){
            if(isset($data['data']['shipping_method'])){
                if(is_string($data['data']['shipping_method'])){
                    $update['session']['shipping_method'] = $this->getShippingMethod($data['data']['shipping_method']);
                    $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState($update);
                }
            }
        }

        //updating shipping_methods after shipping_address change
        if($data['action'] == 'shipping_address/update/after'){
            $update_method = true;
        }

        //updating shipping_methods after cart change
        if($data['action'] == 'cart/update/after'){
            $update_method = true;
        }



        if($update_method){
            $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
            if($this->hasShipping()){
                $update['session']['shipping_methods'] = $this->getShippingMethods();
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('session','shipping_methods'), $update['session']['shipping_methods']);

                $update['session']['shipping_method'] = $this->getShippingMethod(is_array($state['session']['shipping_method']) ? $state['session']['shipping_method']['code'] : $state['session']['shipping_method']);
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('session','shipping_method'), $update['session']['shipping_method']);

            }

            $this->validate();
        }

        if($update){

            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('shipping_method/update/after', $data);
        }
    }

    public function validate(){
        $this->load->language('checkout/shipping_method');
        $this->load->language('extension/ajax_quick_checkout/ajax_quick_checkout/shipping_method');
        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
        if(empty($state['errors']['shipping_method'])){
            $state['errors']['shipping_method'] = array();
        }
        $state['errors']['shipping_method']['error_shipping'] = '';

        if(!$this->hasShipping()){
            $state['errors']['shipping_method']['error_no_shipping'] = '';
            foreach($state['config'] as $account => $value){
                $state['config'][$account]['shipping_method']['display'] = 0;
            }
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState($state);
            return true;
        }else{
            if(!$state['config'][$state['session']['account']]['shipping_method']['display']){
                $this->load->config('ajax_quick_checkout/shipping_method');
                $config = $this->config->get('ajax_quick_checkout_shipping_method');
                $settings = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getSetting();
                foreach($config['account'] as $account => $value){
                    if(!empty($settings['config'][$account]['shipping_method']['display'])){
                        $state['config'][$account]['shipping_method']['display'] = $settings['config'][$account]['shipping_method']['display'];
                    }else{
                        $state['config'][$account]['shipping_method']['display'] = $value['display'];
                    }
                }
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState($state);
            }
        }

        $result = true;
        if(empty($state['session']['shipping_methods'] )){
            $state['errors']['shipping_method']['error_no_shipping'] = $this->language->get('error_no_shipping');
            $result = false;
        }else{
            $state['errors']['shipping_method']['error_no_shipping'] = '';
            if(empty($state['session']['shipping_method']) || !$state['session']['shipping_method']){
                $state['errors']['shipping_method']['error_shipping'] = $this->language->get('error_shipping');
                $result = false;
            }else{
                $state['errors']['shipping_method']['error_shipping'] = '';
            }
        }

        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('errors','shipping_method'), $state['errors']['shipping_method']);

        return $result;
    }

    private function getConfig(){
        $this->load->config('ajax_quick_checkout/shipping_method');
        $config = $this->config->get('ajax_quick_checkout_shipping_method');

        $settings = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getSetting();
        $result = array();
        foreach($config['account'] as $account => $value){
            if(!empty($settings['config'][$account]['shipping_method'])){
                $result[$account]['shipping_method'] = $settings['config'][$account]['shipping_method'];
            }else{
                $result[$account]['shipping_method'] = array_replace_recursive($config, $value);
            }

            if(!$this->hasShipping()){
                $result[$account]['shipping_method']['display'] = 0;
            }
        }

        return $result;
    }

    private function getLanguages(){
        $this->load->language('extension/ajax_quick_checkout/ajax_quick_checkout/shipping_method');

        $result = array();
        $languages = $this->config->get('ajax_quick_checkout_shipping_method_language');

        foreach ($languages as $key => $language) {
            $result[$key] = $this->language->get($language);
        }

        $this->load->language('checkout/shipping_method');
        $result['heading_title'] = $this->language->get('heading_title');

        $language = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getLanguage();
        if(isset($language['shipping_method'])){
            $result = array_replace_recursive($result, $language['shipping_method']);
        }

        if (is_file(DIR_IMAGE . 'catalog/ajax_quick_checkout/step/shipping_method.svg')) {
            $result['image'] = HTTP_SERVER.'image/catalog/ajax_quick_checkout/step/shipping_method.svg';
        } else {
            $result['image'] = HTTP_SERVER.'extension/ajax_quick_checkout/image/catalog/ajax_quick_checkout/step/shipping_method.svg';
        }

        return $result;
    }

    private function getShippingMethod($shipping_method = false){
        if(!$shipping_method){
            $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
            if(!empty($state['session']['shipping_method'])){
                $shipping_method = !is_array($state['session']['shipping_method']) ? $state['session']['shipping_method'] : $state['session']['shipping_method']['code'];
            }
        }
        return $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_method->getDefaultShippingMethod($shipping_method);
    }

    private function getShippingMethods(){
        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
        $new_shipping_methods = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_method->getShippingMethods($state['session']['shipping_address']);

        if(!empty($state['session']['shipping_methods'])){
            foreach($state['session']['shipping_methods'] as $key => $value){
                if(!isset($new_shipping_methods[$key])){
                    $new_shipping_methods[$key] = null;
                }
            }
        }

        //Need for properly deep-merge in immutable on frontend.
        $new_shipping_methods = !empty($new_shipping_methods) ? $new_shipping_methods : '';

        return $new_shipping_methods;
    }

    private function hasShipping(){
        if($this->hasShipping == null){
            $this->hasShipping = $this->cart->hasShipping();
        }
        return $this->hasShipping;
    }


}
