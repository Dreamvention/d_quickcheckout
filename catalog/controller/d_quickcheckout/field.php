<?php 

class ControllerDQuickcheckoutField extends Controller {
   
    public function index($config){
        $this->load->model('module/d_quickcheckout');
        $this->model_module_d_quickcheckout->logWrite('Controller:: field/index');

        $this->document->addScript('catalog/view/javascript/d_quickcheckout/library/tinysort/jquery.tinysort.min.js');
        if(!$config['general']['compress']){
            $this->document->addScript('catalog/view/javascript/d_quickcheckout/view/field.js');
        }

        $data['text_select'] = $this->language->get('text_select');
        $data['error_field_required'] = $this->language->get('error_field_required');
        $data['error_email'] = $this->language->get('error_email');
        $data['settings'] =  $this->settings;
        
        if(VERSION >= '2.2.0.0'){
            $template = 'd_quickcheckout/field';
        }elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/d_quickcheckout/field.tpl')) {
            $template = $this->config->get('config_template') . '/template/d_quickcheckout/field.tpl';
        } else {
            $template = 'default/template/d_quickcheckout/field.tpl';
        }
        
        return $this->load->view($template, $data);
    }

    public function getZone(){
        if(isset($this->request->post['country_id'])){
            $this->load->model('d_quickcheckout/address');
            $json = $this->model_d_quickcheckout_address->getZonesByCountryId($this->request->post['country_id']);
        }else{
            $json = false;
        }
        
        if(!$json){
            $json = array( 0 => array( 'name' => $this->language->get('text_none'), 'value' => 0)); 
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function validate_email(){
        $this->load->model('account/customer');
        $this->load->language('checkout/checkout');
        $json = true;

        unset($this->request->get['route']);
        $email = current($this->request->get);

        if ($this->model_account_customer->getTotalCustomersByEmail($email)) {
            $json = $this->language->get('error_exists');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function validate_regex(){
        $this->load->model('account/customer');
        $this->load->language('checkout/checkout');
        $json = true;

        unset($this->request->get['route']);
        $regex = $this->request->get['regex'];
        unset($this->request->get['regex']);
        $value = current($this->request->get);

        if (!preg_match($regex, $value)){
            $json = false;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
