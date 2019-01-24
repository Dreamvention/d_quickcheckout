<?php
/*
 *	location: catalog/controller
 */

class ControllerExtensionModuleDQuickcheckout extends Controller {
    private $codename = 'd_quickcheckout';
    private $route = 'extension/module/d_quickcheckout';

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->model('extension/d_quickcheckout/store');
        $this->load->model('extension/d_quickcheckout/order');
        $this->load->model('extension/d_quickcheckout/account');
        $this->load->model('extension/d_quickcheckout/error');

        if(!isset($this->user)){
            if(VERSION < '2.2.0.0'){
                $this->user = new User($registry);
            }else{
                $this->user = new Cart\User($registry);
            }
        }
    }

    public function index() {

        $data = array();

        if(!$this->config->get('d_quickcheckout_status')){
            return false;
        }
        
        $this->document->addScript('catalog/view/javascript/d_quickcheckout/serializejson/jquery.serializejson.min.js');
        $this->document->addScript('catalog/view/javascript/d_quickcheckout/immutable/immutable.js');
        $this->document->addScript('catalog/view/javascript/d_quickcheckout/sortable/jquery.sortable.min.js');
        $this->document->addStyle('catalog/view/javascript/d_quickcheckout/animate/animate.min.css');
        $this->document->addScript('catalog/view/javascript/d_riot/riotcompiler.min.js');
        $this->document->addScript('catalog/view/javascript/d_alertify/alertify.min.js');
        $this->document->addStyle('catalog/view/javascript/d_alertify/css/alertify.min.css');
        $this->document->addScript('catalog/view/javascript/d_bootstrap_switch/js/bootstrap-switch.js');
        $this->document->addStyle('catalog/view/javascript/d_bootstrap_switch/css/bootstrap-switch.min.css');

        $this->document->addScript('catalog/view/javascript/d_bootstrap_select/js/bootstrap-select.min.js');
        $this->document->addStyle('catalog/view/javascript/d_bootstrap_select/css/bootstrap-select.min.css');
        $this->document->addScript('catalog/view/javascript/d_bootstrap_select/js/i18n/defaults-en_US.min.js');
        
        $this->document->addScript('catalog/view/theme/default/javascript/d_quickcheckout/main.js');
        $this->document->addScript('catalog/view/theme/default/javascript/d_quickcheckout/component/setting.js');
        $this->document->addScript('catalog/view/theme/default/javascript/d_quickcheckout/component/page.js');
        $this->document->addScript('catalog/view/theme/default/javascript/d_quickcheckout/component/row.js');
        $this->document->addScript('catalog/view/theme/default/javascript/d_quickcheckout/component/col.js');
        $this->document->addScript('catalog/view/theme/default/javascript/d_quickcheckout/component/step.js');
        $this->document->addScript('catalog/view/theme/default/javascript/d_quickcheckout/component/field.js');
        $this->document->addScript('catalog/view/theme/default/javascript/d_quickcheckout/component/error.js');

        $this->document->addStyle('catalog/view/javascript/d_quickcheckout/jqueryui/jquery-ui.css');
        $this->document->addScript('catalog/view/javascript/d_quickcheckout/jqueryui/jquery-ui.js');
        //fix for jquery-ui conflict
        $this->document->addScript('catalog/view/javascript/d_quickcheckout/bootstrap/conflict.js');
        $this->document->addScript('catalog/view/javascript/d_quickcheckout/jsondiffpatch/jsondiffpatch.js');
        $this->document->addScript('catalog/view/javascript/d_quickcheckout/jquerymask/jquery.mask.min.js');
        $this->document->addStyle('catalog/view/javascript/d_quickcheckout/intltelinput/css/intlTelInput.min.css');
        $this->document->addScript('catalog/view/javascript/d_quickcheckout/intltelinput/js/intlTelInput.min.js');
        $this->document->addScript('catalog/view/javascript/d_quickcheckout/intltelinput/js/utils.js');

        
        $this->document->addScript('catalog/view/javascript/d_quickcheckout/datetimepicker/moment/moment.min.js');
        $this->document->addScript('catalog/view/javascript/d_quickcheckout/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addScript('catalog/view/javascript/d_quickcheckout/datetimepicker/moment/locales.min.js');
        if($this->config->get('d_quickcheckout_rtl')){
            $this->document->addStyle('catalog/view/javascript/d_quickcheckout/ripecss/ripe.rtl.css');
            $this->document->addStyle('catalog/view/theme/default/stylesheet/d_quickcheckout/main.css');
            $this->document->addStyle('catalog/view/theme/default/stylesheet/d_quickcheckout/rtl.css');
        }else{
            $this->document->addStyle('catalog/view/javascript/d_quickcheckout/ripecss/ripe.css');
            $this->document->addStyle('catalog/view/theme/default/stylesheet/d_quickcheckout/main.css');
        }
        
        $state = $this->initState();
        $this->load->model('extension/d_quickcheckout/view');

        $this->load->controller('extension/d_quickcheckout/account');
        $this->load->controller('extension/d_quickcheckout/payment_address');
        $this->load->controller('extension/d_quickcheckout/shipping_address');
        $this->load->controller('extension/d_quickcheckout/custom'); 

        $order_id = $this->model_extension_d_quickcheckout_order->getOrder();
        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'order_id'), $order_id);

        $this->load->controller('extension/d_quickcheckout/shipping_method'); 
        $this->load->controller('extension/d_quickcheckout/payment_method'); 
        $this->load->controller('extension/d_quickcheckout/cart'); 
        //$this->load->controller('extension/d_quickcheckout/continue'); 
        $this->load->controller('extension/d_quickcheckout/confirm'); 
        $this->load->controller('extension/d_quickcheckout/payment'); 

        $default_steps = array('account', 'payment_address', 'shipping_address', 'custom', 'shipping_method', 'payment_method', 'cart', 'confirm', 'payment', 'field', 'login');

        $all_steps = $this->model_extension_d_quickcheckout_store->getReceivers();

        $extra_steps = array_diff($all_steps, $default_steps);

        if($extra_steps){
            foreach($extra_steps as $extra_step){
                $this->load->controller('extension/d_quickcheckout/'.$extra_step);
            }
        }

        $data['riot_tags'] = $this->model_extension_d_quickcheckout_view->getRiotTags();

        $data['state'] = $this->model_extension_d_quickcheckout_store->getState();

        $data['state']['language']['general'] = $this->getLanguage();

        //set opened page
        if(!isset($state['session']['page_id'])){
            $data['state']['session']['page_id'] = 'page0';
        }

        //set opened page
        if(!isset($state['layout']['skin'])){
            $data['state']['layout']['skin'] = 'default';
        }

        $this->model_extension_d_quickcheckout_store->saveState();

        $data['state']['edit'] = false;
        $data['state']['pro'] = false;
        $data['state']['loader'] = false;
        $data['state']['close'] = $this->url->link('checkout/checkout');

        //set opened page
        if(file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_quickcheckout_pack.json')){
            $data['state']['pro'] = true;
        }

        $this->load->model('localisation/language');
        $data['state']['languages'] = $this->model_localisation_language->getLanguages();

        $data['state']['custom_fields'] = $this->model_extension_d_quickcheckout_account->getCustomFields($this->config->get('config_customer_group_id'));

        $data['state']['error_types'] = $this->model_extension_d_quickcheckout_error->getErrorTypes();

        $data['edit'] = false;
        if($this->user->isLogged() && isset($this->request->get['edit'])){
            $data['state']['edit'] = true;
            $data['edit'] = true;
        }else{
            $this->document->addStyle('catalog/view/theme/default/stylesheet/d_quickcheckout/skin/'.$data['state']['layout']['skin'] .'/'.$data['state']['layout']['skin'] .'.css?'.rand());
        }
        return $this->load->view($this->model_extension_d_quickcheckout_view->template($this->route), $data);
    }

    public function update(){

        $setting_id = 1;

        if($this->validate()){
            $this->load->model('extension/d_quickcheckout/store');
            $this->model_extension_d_quickcheckout_store->loadState();

            //REFACTOR - need a cleaner way to update pages.
            //$post = $this->request->post;
            $rawData = file_get_contents('php://input');
            $post = json_decode($rawData, true);
            if(!$post){
                $post = $this->request->post;
            }
            

            if(isset($post['layout'])){
                $this->model_extension_d_quickcheckout_store->updateState(array('layout'), $post['layout']);
                unset($post['layout']);
            }
            if(isset($post['config']) && isset($post['config']['guest'])){
                $this->model_extension_d_quickcheckout_store->updateState(array('config'), $post['config']);
                unset($post['config']);
            }
            $this->model_extension_d_quickcheckout_store->setState($post);

            $state = $this->model_extension_d_quickcheckout_store->getState();

            $state['status'] = 1;
            $this->model_extension_d_quickcheckout_store->editSetting($state);

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($state));
        }
    }

    protected function validate() {
        $error = false;
        if (!$this->user->hasPermission('modify', $this->route)) {
            $error['warning'] = $this->language->get('error_permission');
        }

        return !$error;
    }

    public function change_language(){
        $this->load->model('extension/d_quickcheckout/store');
        $this->model_extension_d_quickcheckout_store->loadState();
        
        $this->session->data['language'] = $this->request->post['language_id'];

        $state = $this->model_extension_d_quickcheckout_store->getState();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($state));
    }

    public function get_language(){
        $this->load->model('extension/d_quickcheckout/store');
        $this->model_extension_d_quickcheckout_store->loadState();


        
        $this->load->controller('extension/d_quickcheckout/account');
        $this->load->controller('extension/d_quickcheckout/payment_address'); //2.6
        $this->load->controller('extension/d_quickcheckout/shipping_address'); //1.5
        $this->load->controller('extension/d_quickcheckout/custom'); //0.12
        $this->load->controller('extension/d_quickcheckout/shipping_method'); //3
        $this->load->controller('extension/d_quickcheckout/payment_method'); //10
        $this->load->controller('extension/d_quickcheckout/cart'); //1.5
        //$this->load->controller('extension/d_quickcheckout/continue'); //0.12
        $this->load->controller('extension/d_quickcheckout/confirm'); //0.36
        $this->load->controller('extension/d_quickcheckout/payment'); //4.5

        $state = $this->model_extension_d_quickcheckout_store->getState();
        $state['language']['general'] = $this->getLanguage();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($state));
    }

    public function reset(){
        $this->load->model('extension/d_quickcheckout/store');
        $this->model_extension_d_quickcheckout_store->clearState();
        $this->model_extension_d_quickcheckout_store->initState();

        $this->load->controller('extension/d_quickcheckout/account');
        $this->load->controller('extension/d_quickcheckout/payment_address'); //2.6
        $this->load->controller('extension/d_quickcheckout/shipping_address'); //1.5
        $this->load->controller('extension/d_quickcheckout/custom'); //0.12
        $order_id = $this->model_extension_d_quickcheckout_order->getOrder();
        $this->model_extension_d_quickcheckout_store->updateState(array('session', 'order_id'), $order_id);
        $this->load->controller('extension/d_quickcheckout/shipping_method'); //3
        $this->load->controller('extension/d_quickcheckout/payment_method'); //10
        $this->load->controller('extension/d_quickcheckout/cart'); //1.5
        //$this->load->controller('extension/d_quickcheckout/continue'); //0.12
        $this->load->controller('extension/d_quickcheckout/confirm'); //0.36
        $this->load->controller('extension/d_quickcheckout/payment'); //4.5

        $state = $this->model_extension_d_quickcheckout_store->getState();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($state));
    }

    public function change_layout(){

        $post = $this->request->post;

        if(isset($post['layout_codename'])){

            $this->load->model('extension/d_quickcheckout/store');
            $this->model_extension_d_quickcheckout_store->changeLayout($post['layout_codename']);
            $this->model_extension_d_quickcheckout_store->initState();

            $this->load->controller('extension/d_quickcheckout/account');
            $this->load->controller('extension/d_quickcheckout/payment_address'); //2.6
            $this->load->controller('extension/d_quickcheckout/shipping_address'); //1.5
            $this->load->controller('extension/d_quickcheckout/custom'); //0.12
            $order_id = $this->model_extension_d_quickcheckout_order->getOrder();
            $this->model_extension_d_quickcheckout_store->updateState(array('session', 'order_id'), $order_id);
            $this->load->controller('extension/d_quickcheckout/shipping_method'); //3
            $this->load->controller('extension/d_quickcheckout/payment_method'); //10
            $this->load->controller('extension/d_quickcheckout/cart'); //1.5
            //$this->load->controller('extension/d_quickcheckout/continue'); //0.12
            $this->load->controller('extension/d_quickcheckout/confirm'); //0.36
            $this->load->controller('extension/d_quickcheckout/payment'); //4.5
        }

        $state = $this->model_extension_d_quickcheckout_store->getState();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($state));
    }

    //REFACTOR !!!!
    public function open_page(){
        $this->load->model('extension/d_quickcheckout/store');
        $this->model_extension_d_quickcheckout_store->loadState();

        //REFACTOR - need a cleaner way to update pages.
        $post = $this->request->post;

        if(isset($post['layout'])){
            unset($post['layout']);
        }
        

        $this->model_extension_d_quickcheckout_store->setState($post);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($post));
    }

    public function get_custom_fields(){

        // Custom Fields
        $this->load->model('account/custom_field');

        $json = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function initState(){

        $this->load->model('extension/d_quickcheckout/store');
        return $this->model_extension_d_quickcheckout_store->initState();

    }

    public function view_checkout_checkout_after($route, $data, &$output) {

        if($this->config->get('d_quickcheckout_status')){
            $this->load->model('extension/d_quickcheckout/view');
            $supports = $this->model_extension_d_quickcheckout_view->browserSupported();
            if($supports){
                if(true){
                    $template = 'd_quickcheckout';
                    $output = $this->load->view($this->model_extension_d_quickcheckout_view->template('checkout/'.$template), $data);
                }else{
                    $html_dom = new d_simple_html_dom();
                    $html_dom->load((string)$output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);
                    $html_dom->find('body > #content', 0)->innertext = $data['d_quickcheckout'];
                    
                    $output = (string)$html_dom;
                }
            }
            
        }
    }

    public function getLanguage(){
        $this->load->language('checkout/checkout');
        
        $data['entry_address'] = $this->language->get('entry_address');
        $data['text_address_existing'] = $this->language->get('text_address_existing');
        $data['text_address_new'] = $this->language->get('text_address_new');

        $this->load->language('extension/module/d_quickcheckout');
        $data['text_editor_title'] = $this->language->get('text_editor_title');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_guest'] = $this->language->get('text_guest');
        $data['text_logged'] = $this->language->get('text_logged');
        $data['text_display'] = $this->language->get('text_display');
        $data['text_require'] = $this->language->get('text_require');
        $data['text_title'] = $this->language->get('text_title');
        $data['text_description'] = $this->language->get('text_description');
        $data['text_placeholder'] = $this->language->get('text_placeholder');
        $data['text_tooltip'] = $this->language->get('text_tooltip');
        $data['text_error_rules'] = $this->language->get('text_error_rules');
        $data['text_depends'] = $this->language->get('text_depends');
        $data['text_add_page'] = $this->language->get('text_add_page');
        $data['text_add'] = $this->language->get('text_add');
        $data['text_page_1'] = $this->language->get('text_page_1');
        $data['text_settings'] = $this->language->get('text_settings');
        $data['text_hidden'] = $this->language->get('text_hidden');
        $data['text_remove'] = $this->language->get('button_remove');
        $data['text_general'] = $this->language->get('text_general');
        $data['text_css'] = $this->language->get('text_css');
        $data['text_script'] = $this->language->get('text_script');
        $data['text_error'] = $this->language->get('text_error');
        $data['text_dependency'] = $this->language->get('text_dependency');
        $data['text_design'] = $this->language->get('text_design');
        $data['text_value'] = $this->language->get('text_value');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_not_checked'] = $this->language->get('text_not_checked');
        $data['text_checked'] = $this->language->get('text_checked');
        $data['text_mask'] = $this->language->get('text_mask');
        $data['text_header_footer'] = $this->language->get('text_header_footer');
        $data['text_has_dependencies'] = $this->language->get('text_has_dependencies');
        $data['text_no_payment_step'] = $this->language->get('text_no_payment_step');

        $data['text_telephone_validation'] = $this->language->get('text_telephone_validation');
        $data['text_telephone_countries'] = $this->language->get('text_telephone_countries');
        $data['text_layout'] = $this->language->get('text_layout');
        $data['text_skin'] = $this->language->get('text_skin');

        $data['text_text'] = $this->language->get('text_text');
        $data['text_min_length'] = $this->language->get('text_min_length');
        $data['text_max_length'] = $this->language->get('text_max_length');
        $data['text_compare_to'] = $this->language->get('text_compare_to');
        $data['text_not_empty'] = $this->language->get('text_not_empty');
        $data['text_checked'] = $this->language->get('text_checked');
        $data['text_regex'] = $this->language->get('text_regex');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_email_exists'] = $this->language->get('text_email_exists');
        $data['text_select'] = $this->language->get('text_select');

        $data['text_update'] = $this->language->get('button_update');
        $data['text_reset'] = $this->language->get('text_reset');
        $data['text_style'] = $this->language->get('text_style');

        $data['entry_not_empty'] = $this->language->get('entry_not_empty');
        $data['entry_checked'] = $this->language->get('entry_checked');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_email_exists'] = $this->language->get('entry_email_exists');
        $data['entry_min_length'] = $this->language->get('entry_min_length');
        $data['entry_max_length'] = $this->language->get('entry_max_length');
        $data['entry_compare_to'] = $this->language->get('entry_compare_to');
        $data['entry_regex'] = $this->language->get('entry_regex');
        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_radio'] = $this->language->get('entry_radio');
        $data['entry_select'] = $this->language->get('entry_select');

        $data['error_min_length'] = $this->language->get('error_min_length');
        $data['error_max_length'] = $this->language->get('error_max_length');
        $data['error_compare_to'] = $this->language->get('error_compare_to');
        $data['error_not_empty'] = $this->language->get('error_not_empty');
        $data['error_checked'] = $this->language->get('error_checked');
        $data['error_regex'] = $this->language->get('error_regex');
        $data['error_telephone'] = $this->language->get('error_telephone');
        $data['error_email_exists'] = $this->language->get('error_email_exists');

        $data['name'] = $this->config->get('config_name');

        if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] =  $server . 'image/' . $this->config->get('config_logo');
		}

        $this->load->language('checkout/cart');
        $data['text_cart_title'] = $this->language->get('heading_title');
        $data['text_cart_empty'] = $this->language->get('text_empty');

        $this->load->model('extension/d_quickcheckout/store');
        $language = $this->model_extension_d_quickcheckout_store->getLanguage();
        if(isset($language['general'])){
            $data = array_replace_recursive($data, $language['general']);
        }
        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] =  $server . 'image/' . $this->config->get('config_logo');
		}


        $data['img'] = $this->getLanguageImage();

        return $data;
    }

    private function getLanguageImage(){
        if(VERSION < '2.2.0.0'){
            $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "language WHERE code = '" . $this->session->data['language'] . "'");

            if($query->row){
                return HTTPS_SERVER.'image/flags/' . $query->row['image'];
            }
            
        }else{
            return HTTPS_SERVER.'catalog/language/'.$this->session->data['language'].'/'. $this->session->data['language'] .'.png';
        }

         
    }
}
