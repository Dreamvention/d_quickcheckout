<?php

class ControllerExtensionDQuickcheckoutContinue extends Controller {
    private $route = 'd_quickcheckout/continue';

    public $action = array(
        'continue/update'
    );

    public function __construct($registry){
        parent::__construct($registry);

        $this->load->model('extension/d_quickcheckout/store');
        $this->load->model('extension/d_quickcheckout/page');
        $this->load->model('extension/d_quickcheckout/method');
        $this->load->model('extension/d_quickcheckout/error');

    }
    /**
     * Initialization
     */
    public function index($config){
        $this->document->addScript('catalog/view/theme/default/javascript/d_quickcheckout/step/continue.js');

        
        $pages = $this->model_extension_d_quickcheckout_page->getActivePages();
        $page_id = (isset($pages[0])) ? $pages[0] : false;

        $this->model_extension_d_quickcheckout_store->updateState(array('session','pages'), $pages);
        $this->model_extension_d_quickcheckout_store->updateState(array('session','page_id'), $page_id);

        $state = $this->model_extension_d_quickcheckout_store->getState();
        $state['config'] = $this->getConfig();

        $state['language']['continue'] = $this->getLanguages();
        $state['action']['continue'] = $this->action;

        $this->model_extension_d_quickcheckout_store->setState($state);

    }

    /**
     * update via ajax
     */
    public function update(){
        $this->model_extension_d_quickcheckout_store->loadState();
        $this->model_extension_d_quickcheckout_store->dispatch('continue/update/before', $this->request->post);
        $this->model_extension_d_quickcheckout_store->dispatch('continue/update', $this->request->post);

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
        //updating payment_method value
        if($data['action'] == 'continue/update' && isset($data['data']['page_id'])){

            $page_id = $data['data']['page_id'];
            if($this->validatePage($page_id)){
                $state['session']['page_id'] = $this->getNextPage($page_id);
                $this->model_extension_d_quickcheckout_store->setState($state);
            }

            $this->model_extension_d_quickcheckout_store->dispatch('continue/update/after', $this->request->get);
        }

    }

    public function validate() {
        return true;
    }

    private function getConfig(){

        $this->load->config('d_quickcheckout/continue');
        $config = $this->config->get('d_quickcheckout_continue');
        
        $settings = $this->model_extension_d_quickcheckout_store->getSetting();
        $result = array();
        foreach($config['account'] as $account => $value){
            if(!empty($settings['config'][$account]['continue'])){
                $result[$account]['continue'] = $settings['config'][$account]['continue'];
            }else{
                $result[$account]['continue'] = array_replace_recursive($config, $value);
            }
        }

        return $result;
    }

    private function getLanguages(){
        $this->load->language('checkout/checkout');
        $this->load->language('extension/d_quickcheckout/continue');

        $result = array();
        $languages = $this->config->get('d_quickcheckout_continue_language');

        foreach ($languages as $key => $language) {
            $result[$key] = $this->language->get($language);
        }

        $language = $this->model_extension_d_quickcheckout_store->getLanguage();
        if(isset($language['continue'])){
            $result = array_replace_recursive($result, $language['continue']);
        }

        $result['image'] = HTTPS_SERVER.'image/catalog/d_quickcheckout/step/continue.svg';

        return $result;
    }


    private function validatePage($page_id){
        $state = $this->model_extension_d_quickcheckout_store->getState();

        if(isset($state['layout']['pages'][$page_id])){
            

            $this->load->model('extension/d_quickcheckout/error');
            return $this->model_extension_d_quickcheckout_error->validatePage($page_id);

        }
        return true;
    }

    private function getPageSteps($page_id){
        return $this->array_flatten($state['layout']['pages'][$page_id]['children']);
    }

    private function array_flatten($array) {

       $return = array();

       foreach ($array as $key => $value) {
            if(isset($value['children'])){
                $return = array_merge($return, $this->array_flatten($value['children'])); 
            }
            elseif(isset($value['type']) && $value['type'] == 'item'){
                $return[] = $value['name'];
            }
       }
       return $return;

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
        $pages = $this->model_extension_d_quickcheckout_page->getActivePages();
        $page_id = (isset($pages[0])) ? $pages[0] : false;

        return array(
            'page_id' => $page_id,
            'pages' => $pages,
        );

    }

}
