<?php
namespace Opencart\Catalog\Controller\Extension\AjaxQuickCheckout\AjaxQuickCheckout;

class PaymentMethod extends \Opencart\System\Engine\Controller {
    private $route = 'ajax_quick_checkout/payment_method';

    public $action = array(
        'payment_method/update',
        'payment_address/update/after',
        'cart/update/after',
        'total/update/after'
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

        $state['session']['payment_methods'] = $this->getPaymentMethods();
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState($state);

        $state['config'] = $this->getConfig();
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState($state);
        $state['session']['payment_method'] = $this->getPaymentMethod();

        $state['language']['payment_method'] = $this->getLanguages();
        $state['action']['payment_method'] = $this->action;
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

        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('payment_method/update/before', $post);
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('payment_method/update', $post);
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
        $update = false;
        $update_method = false;

        //updating payment_method value
        if($data['action'] == 'payment_method/update'){
            if($data['data']['payment_method']){
                if(is_string($data['data']['payment_method'])){
                    $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('session', 'payment_method'), $this->getPaymentMethod($data['data']['payment_method']));
                    $update = true;
                }
            }
        }

        //updating payment_methods after payment_address change
        if($data['action'] == 'payment_address/update/after'
            && (
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->isUpdated('payment_address_country_id')
                || $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->isUpdated('payment_address_zone_id')
                || $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->isUpdated('payment_address_address_id')
                || $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->isUpdated('payment_address_postcode')
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
        if($data['action'] == 'total/update/after' && $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->isUpdated('totals')){
            $update_method = true;
        }

        if($update_method){
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('session','payment_methods'), $this->getPaymentMethods());
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('session','payment_method'), $this->getPaymentMethod());
            $this->validate();
        }

        //should bot be triggered after payment method has been updated the second time from total changes.
        if($update){
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('payment_method/update/after', $data);
        }
    }

    /**
     * Validate
     * Validate checks if the step is valid to continue the checkout
     */
    public function validate(){
        $this->load->language('checkout/payment_method');
        $this->load->language('extension/ajax_quick_checkout/ajax_quick_checkout/payment_method');
        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
        $result = true;
        if(empty($state['errors']['payment_method'])){
            $state['errors']['payment_method'] = array();
        }
        $state['errors']['payment_method']['error_payment'] = false;
        if(!$this->model_extension_ajax_quick_checkout_ajax_quick_checkout_method->getFirstPaymentMethod()){
            $state['errors']['payment_method']['error_no_payment'] = $this->language->get('error_no_payment');
            $result = false;
        }else{
            $state['errors']['payment_method']['error_no_payment'] = '';
            if(empty($state['session']['payment_method'] )){
                $state['errors']['payment_method']['error_payment'] = $this->language->get('error_payment');
                $result = false;
            }
        }

        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('errors','payment_method'), $state['errors']['payment_method']);

        return $result;
    }

    private function getConfig(){
        $this->load->config('ajax_quick_checkout/payment_method');
        $config = $this->config->get('ajax_quick_checkout_payment_method');

        $settings = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getSetting();
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
        $this->load->language('extension/ajax_quick_checkout/ajax_quick_checkout/payment_method');

        $result = array();
        $languages = $this->config->get('ajax_quick_checkout_payment_method_language');

        foreach ($languages as $key => $language) {
            $result[$key] = $this->language->get($language);
        }
        $this->load->language('checkout/payment_method');
        $result['heading_title'] = $this->language->get('heading_title');

        $language = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getLanguage();
        if(isset($language['payment_method'])){
            $result = array_replace_recursive($result, $language['payment_method']);
        }

        if (is_file(DIR_IMAGE . 'catalog/ajax_quick_checkout/step/payment_method.svg')) {
            $result['image'] = HTTP_SERVER.'image/catalog/ajax_quick_checkout/step/payment_method.svg';
        } else {
            $result['image'] = HTTP_SERVER.'extension/ajax_quick_checkout/image/catalog/ajax_quick_checkout/step/payment_method.svg';
        }

        return $result;
    }

    private function getDefault(){
        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
        return $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_method->getDefaultPaymentMethod($state['config']['guest']['payment_method']['default_option']);
    }

    private function getPaymentMethod($payment_method = false){
        if(!$payment_method){
            $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
            if(!empty($state['session']['payment_method']) && $state['session']['payment_method']){
                $payment_method = !is_array($state['session']['payment_method']) ? $state['session']['payment_method'] : $state['session']['payment_method']['code'];
            }
        }
        return $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_method->getDefaultPaymentMethod($payment_method);
    }

    private function getPaymentMethods(){

        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
        $new_payment_methods = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_method->getPaymentMethods($state['session']['payment_address'], $state['session']['total']);

        if(!empty($state['session']['payment_methods'])){
            foreach($state['session']['payment_methods'] as $key => $value){
                if(!isset($new_payment_methods[$key])){
                        $new_payment_methods[$key] = null;
                }
            }
        }

        //Need for properly deep-merge in immutable on frontend.
        $new_payment_methods = !empty($new_payment_methods)?$new_payment_methods:'';


        return $new_payment_methods;
    }

}
