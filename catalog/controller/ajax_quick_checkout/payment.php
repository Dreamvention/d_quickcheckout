<?php
namespace Opencart\Catalog\Controller\Extension\AjaxQuickCheckout\AjaxQuickCheckout;

class Payment extends \Opencart\System\Engine\Controller {
    private $route = 'ajax_quick_checkout/payment';

    public $action = array(
        'payment_method/update/after',
        'total/update/after'
    );

    private $pro = '';

    public function __construct($registry){
        parent::__construct($registry);
        
        if(is_file(DIR_EXTENSION.'ajax_quick_checkout_pro/install.json')) $this->pro .= '_pro';

        $this->config->addPath(DIR_EXTENSION . 'ajax_quick_checkout' . $this->pro . '/system/config/');

        $this->load->model('extension/ajax_quick_checkout/module/ajax_quick_checkout');
        $this->model_extension_ajax_quick_checkout_module_ajax_quick_checkout->loadDependencies();
        
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/store');
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/method');

    }

    /**
     *  Initialization
     *
     *  Loaded in the extension/module/ajax_quick_checkout controller once.
     *  Sets default values to state
     *
     */
    public function index(){

        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();

        $state['session']['payment'] = $this->getDefault();
        $state['config'] = $this->getConfig();

        $state['language']['payment'] = $this->getLanguages();
        $state['action']['payment'] = $this->action;
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState($state);
    }

    /**
     *  Update
     *
     *  Called via AJAX to update state by current module.
     *  Returns updated state.
     *
     */
    public function update(){
        $rawData = file_get_contents('php://input');
        $post = json_decode($rawData, true);
        if(!$post){
            $post = $this->request->post;
        }
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->loadState();
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('payment/update/before', $post);
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('payment/update', $post);

        $data = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getStateUpdated();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }


    /**
     *  Receiver
     *
     *  Receiver listens to dispatch of events and accepts data with action and state.
     *  Receivers are parsed with the first initialization of Store in extension/module/ajax_quick_checkout controller
     *
     */
    public function receiver($data){
        $update = false;


        //updating payment_method value
        if($data['action'] == 'payment_method/update/after'
        || $data['action'] == 'total/update/after'){

            $payment = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_method->getPayment();
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('session', 'payment'), $payment);
            $update = true;
        }

        if($update){
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('payment/update/after', $data);
            //REFACTOR
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getStateUpdated();
        }
    }

    public function validate(){
        return true;
    }

    private function getConfig(){
        $this->load->config('ajax_quick_checkout/payment');
        $config = $this->config->get('ajax_quick_checkout_payment');

        $settings = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getSetting();
        $result = array();
        foreach($config['account'] as $account => $value){
            if(!empty($settings['config'][$account]['payment'])){
                $result[$account]['payment'] = $settings['config'][$account]['payment'];
            }else{
                $result[$account]['payment'] = array_replace_recursive($config, $value);
            }
        }

        return $result;
    }

    private function getLanguages(){
        $this->load->language('extension/ajax_quick_checkout/ajax_quick_checkout/payment');

        $result = array();
        $languages = $this->config->get('ajax_quick_checkout_payment_language');

        foreach ($languages as $key => $language) {
            $result[$key] = $this->language->get($language);
        }

        $language = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getLanguage();
        if(isset($language['payment'])){
            $result = array_replace_recursive($result, $language['payment']);
        }

        if (is_file(DIR_IMAGE . 'catalog/ajax_quick_checkout/step/payment.svg')) {
            $result['image'] = HTTP_SERVER.'image/catalog/ajax_quick_checkout/step/payment.svg';
        } else {
            $result['image'] = HTTP_SERVER.'extension/ajax_quick_checkout/image/catalog/ajax_quick_checkout/step/payment.svg';
        }

        return $result;
    }

    private function getDefault(){

        return $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_method->getPayment();

    }
}
