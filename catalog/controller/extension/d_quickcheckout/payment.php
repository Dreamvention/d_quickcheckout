<?php
class ControllerExtensionDQuickcheckoutPayment extends Controller {
    private $route = 'd_quickcheckout/payment';

    public $action = array(
        'payment_method/update/after',
        'total/update/after'
    );

    public function __construct($registry){
        parent::__construct($registry);

        $this->load->model('extension/d_quickcheckout/store');
        $this->load->model('extension/d_quickcheckout/method');

    }

    /**
     *  Initialization
     *
     *  Loaded in the extension/module/d_quickcheckout controller once.
     *  Sets default values to state
     *
     */
    public function index($config){
        $this->document->addScript('catalog/view/theme/default/javascript/d_quickcheckout/step/payment.js');

        $state = $this->model_extension_d_quickcheckout_store->getState();

        $state['session']['payment'] = $this->getDefault();
        $state['config'] = $this->getConfig();

        $state['language']['payment'] = $this->getLanguages();
        $state['action']['payment'] = $this->action;
        $this->model_extension_d_quickcheckout_store->setState($state);
    }

    /**
     *  Update
     *
     *  Called via AJAX to update state by current module.
     *  Returns updated state.
     *
     */
    public function update(){
        $this->model_extension_d_quickcheckout_store->loadState();
        $this->model_extension_d_quickcheckout_store->dispatch('payment/update/before', $this->request->post);
        $this->model_extension_d_quickcheckout_store->dispatch('payment/update', $this->request->post);

        $data = $this->model_extension_d_quickcheckout_store->getStateUpdated();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }


    /**
     *  Receiver
     *
     *  Receiver listens to dispatch of events and accepts data with action and state.
     *  Receivers are parsed with the first initialization of Store in extension/module/d_quickcheckout controller
     *
     */
    public function receiver($data){
        $update = false;


        //updating payment_method value
        if($data['action'] == 'payment_method/update/after'
        || $data['action'] == 'total/update/after'){

            $payment = $this->model_extension_d_quickcheckout_method->getPayment();
            $this->model_extension_d_quickcheckout_store->updateState(array('session', 'payment'), $payment);
            $update = true;
        }

        if($update){
            $this->model_extension_d_quickcheckout_store->dispatch('payment/update/after', $data);
        }
    }

    public function validate(){
        return true;
    }

    private function getConfig(){

        $this->load->config('d_quickcheckout/payment');
        $config = $this->config->get('d_quickcheckout_payment');
        
        $settings = $this->model_extension_d_quickcheckout_store->getSetting();
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
        $this->load->language('checkout/checkout');
        $this->load->language('extension/d_quickcheckout/payment');

        $result = array();
        $languages = $this->config->get('d_quickcheckout_payment_language');

        foreach ($languages as $key => $language) {
            $result[$key] = $this->language->get($language);
        }

        $language = $this->model_extension_d_quickcheckout_store->getLanguage();
        if(isset($language['payment'])){
            $result = array_replace_recursive($result, $language['payment']);
        }

        $result['image'] = HTTPS_SERVER.'image/catalog/d_quickcheckout/step/payment.svg';

        return $result;
    }

    private function getDefault(){

        return $this->model_extension_d_quickcheckout_method->getPayment();

    }
}
