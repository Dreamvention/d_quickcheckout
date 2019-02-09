<?php
class ControllerExtensionDQuickcheckoutCustom extends Controller {
    private $route = 'd_quickcheckout/custom';

    public $action = array(
        'custom/update'
    );

    public function __construct($registry){
        parent::__construct($registry);

        $this->load->model('extension/d_quickcheckout/store');
        $this->load->model('extension/d_quickcheckout/address');
        $this->load->model('extension/d_quickcheckout/account');

    }
    /**
     * Initialization
     */
    public function index($config){
        $this->document->addScript('catalog/view/theme/default/javascript/d_quickcheckout/step/custom.js');

        $state = $this->model_extension_d_quickcheckout_store->getState();

        //set default values
        $state['config'] = $this->getConfig();
        $this->model_extension_d_quickcheckout_store->setState($state);
        $state['session']['custom'] = $this->getDefault();
        //opencart fix
        $state['session']['comment'] = $state['session']['custom']['comment'];

        $state['language']['custom'] = $this->getLanguages();
        $state['action']['custom'] = $this->action;
        $this->model_extension_d_quickcheckout_store->setState($state);
    }

    /**
     * update via ajax
     */
    public function update(){
        $this->model_extension_d_quickcheckout_store->loadState();
        $this->model_extension_d_quickcheckout_store->dispatch('custom/update/before', $this->request->post);
        $this->model_extension_d_quickcheckout_store->dispatch('custom/update', $this->request->post);

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


        //updating payment_address field values
        if($data['action'] == 'custom/update'){

            if(!empty($data['data']['session']['custom'])){
                foreach($data['data']['session']['custom'] as $field => $value){
                    $this->updateField($field, $value);
                    $update = true;
                }
            }
            //REFACTOR - added other data like config and layout
            if(!empty($data['data']['config']) || !empty($data['data']['layout'])){
                $this->model_extension_d_quickcheckout_store->setState($data['data']);
            }
        }

        if($update){
            $this->model_extension_d_quickcheckout_store->dispatch('custom/update/after', $data);
        }
    }

    public function validate(){
        $this->load->model('extension/d_quickcheckout/error');
        $state = $this->model_extension_d_quickcheckout_store->getState();
        $step = 'custom';
        $result = true;

        foreach($state['session']['custom'] as $field_id => $value){
            if(!empty($state['config'][$state['session']['account']][$step]['fields'][$field_id]['display'])
            && !empty($state['config'][$state['session']['account']][$step]['fields'][$field_id]['require'])
            && !empty($state['config'][$state['session']['account']][$step]['fields'][$field_id]['errors'])
            ){
                $errors = $state['config'][$state['session']['account']][$step]['fields'][$field_id]['errors'];
                $no_errors = true;
                foreach($errors as $error){
                    if(is_array($error)){
                        foreach($error as $validate => $rule){
                            if(!$this->model_extension_d_quickcheckout_error->$validate($rule, $value)){
                                $state['errors'][$step][$field_id] = $this->model_extension_d_quickcheckout_error->text($error['text'], $value);
                                $result = false;
                                $no_errors = false;
                                break;
                            }
                        }
                    }
                    if($no_errors){
                        $state['errors'][$step][$field_id] = '';
                    }
                }
            }else{
                $state['errors'][$step][$field_id] = '';
            }
            
        }

        $this->model_extension_d_quickcheckout_store->setState($state);

        return $result;
    }


    /**
     * logic for updating fields
     */
    private function updateField($field, $value){
        $state['session']['custom'][$field] = $value;
        if($this->validateField($field, $value)){
            switch ($field){
                case 'comment' :
                    $state['session']['comment'] = $value;
                break;

                default: 
                    if(isset($state['config']['guest']['custom']['fields'][$field])){
                        if($state['config']['guest']['custom']['fields'][$field]['custom']){
                            $location = $state['config']['guest']['custom']['fields'][$field]['location'];
                            $custom_field_id = $state['config']['guest']['custom']['fields'][$field]['custom_field_id'];
                        }
                    }else{
                        $part = explode('-', $field);
                        if(isset($part[2]) && is_numeric($part[2])){
                            if($part[0] == 'custom'){
                                $location = $part[1];
                                $custom_field_id = $part[2];
                            }
                        }
                        
                    }
                    if(isset($location)){
                        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'custom', 'custom_field', $location, $custom_field_id),  $value);
                    }
                    //nothing at the moment;
                break;
            }
            $this->model_extension_d_quickcheckout_store->setState($state);
        }else{
            $this->model_extension_d_quickcheckout_store->setState($state);
        }
    }

    private function getConfig(){

        
        $this->load->config('d_quickcheckout/custom');
        $this->load->model('extension/d_quickcheckout/store');
        $config = $this->config->get('d_quickcheckout_custom');

        $result = array();
        $settings = $this->model_extension_d_quickcheckout_store->getSetting();

        foreach($config['account'] as $account => $value){
            if(!empty($settings['config'][$account]['custom'])){
                $result[$account]['custom'] = $settings['config'][$account]['custom'];
            }else{
                $result[$account]['custom'] = array_replace_recursive($config, $value);
            }
        }

        return $result;
    }

    private function getLanguages(){
        $this->load->language('checkout/checkout');
        $this->load->language('extension/d_quickcheckout/custom');

        $result = array();
        $languages = $this->config->get('d_quickcheckout_custom_language');

        foreach ($languages as $key => $language) {
            $result[$key] = $this->language->get($language);
        }

        $language = $this->model_extension_d_quickcheckout_store->getLanguage();
        if(isset($language['custom'])){
            $result = array_replace_recursive($result, $language['custom']);
        }

        //links in default text
        if ($this->config->get('config_checkout_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

            if ($information_info) {
                $result['entry_agree'] = sprintf($result['entry_agree'], $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_checkout_id'), true), htmlspecialchars_decode($information_info['title']), $information_info['title']);

                 
                $result['error_agree_checked'] = sprintf($result['error_agree_checked'], htmlspecialchars_decode($information_info['title']));
            }
        }

        $result['image'] = HTTPS_SERVER.'image/catalog/d_quickcheckout/step/custom.svg';

        return $result;
    }

    /**
     * Default state
     */
    private function getDefault($populate = true){

        $custom = array();
        $state = $this->model_extension_d_quickcheckout_store->getState();
        if($populate){
            if(isset($state['session']['custom'])){
                $custom = $state['session']['custom'];
            }
        }
        $default = $state['config'][$state['session']['account']]['custom']['fields'];
        $address = array(
            'comment' => (isset($custom['comment'])) ? $custom['comment'] : $default['comment']['value'],
            'agree' => (isset($custom['agree'])) ? $custom['agree'] : $default['agree']['value'],
            'custom_field' => array()
        );

        //init custom fields
        foreach($default as $key => $field){
            if(!empty($field['custom'])){
                $address[$key] = $field['value'];

                $part = explode('-', $key);
                if(isset($part[2]) && is_numeric($part[2])){
                    if($part[0] == 'custom'){
                        $location = $part[1];
                        $custom_field_id = $part[2];
                    }
                }

                $custom_field = array( 
                    $location => array( 
                        $custom_field_id => $field['value']
                    )
                );
                $address['custom_field'] = array_merge($address['custom_field'], $custom_field);
            }
        }
        return $address;
    }

    private function validateField($field, $value){
        $this->load->model('extension/d_quickcheckout/error');
        return $this->model_extension_d_quickcheckout_error->validateField('custom', $field, $value);
    }
}
