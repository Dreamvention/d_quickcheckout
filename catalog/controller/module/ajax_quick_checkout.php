<?php
namespace Opencart\Catalog\Controller\Extension\AjaxQuickCheckout\Module;

class AjaxQuickCheckout extends \Opencart\System\Engine\Controller {
    private $codename = 'ajax_quick_checkout';
    private $route = 'extension/ajax_quick_checkout/module/ajax_quick_checkout';
    private $pro = '';

    public function __construct($registry) {
        parent::__construct($registry);
        $error_handler = new \Opencart\Catalog\Model\Extension\AjaxQuickCheckout\Utils\Error($registry);
        set_error_handler([$error_handler, 'customErrorHandler'], E_ALL);
        register_shutdown_function([$error_handler, 'fatal_error_shutdown_handler']);
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/store');
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/order');
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/account');
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/error');
        if(is_file(DIR_EXTENSION.'ajax_quick_checkout_pro/install.json')) $this->pro .= '_pro';

        $this->load->model('extension/ajax_quick_checkout/module/ajax_quick_checkout');
        $this->model_extension_ajax_quick_checkout_module_ajax_quick_checkout->loadDependencies();

        $this->config->addPath(DIR_EXTENSION . 'ajax_quick_checkout' . $this->pro . '/system/config/');
        if(!isset($this->user)){
            $this->user = new \Opencart\System\Library\Cart\User($registry);
        }
    }

    public function index() {
        $data = array();



        $state = $this->initState();
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/view');

        $this->initSteps(true);

        $default_steps = array('account', 'payment_address', 'shipping_address', 'custom', 'shipping_method', 'payment_method', 'cart', 'confirm', 'payment', 'field', 'login');

        $all_steps = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getReceivers();

        $extra_steps = array_diff($all_steps, $default_steps);

        if($extra_steps){
            foreach($extra_steps as $extra_step){
                $this->load->controller('extension/ajax_quick_checkout/ajax_quick_checkout/'.$extra_step);
            }
        }

        $data['riot_tags'] = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_view->getRiotTags();

        $data['state'] = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();

        $data['state']['language']['general'] = $this->getLanguage();

        //set opened page
        if(!isset($state['session']['page_id'])){
            $data['state']['session']['page_id'] = 'page0';
        }

        //set opened page
        if(!isset($state['layout']['skin'])){
            $data['state']['layout']['skin'] = 'default';
        }

        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->saveState();

        $data['state']['edit'] = false;
        $data['state']['pro'] = false;
        $data['state']['loader'] = false;
        $data['state']['close'] = $this->url->link('checkout/checkout');

        //set opened page
        if(is_file(DIR_EXTENSION.'ajax_quick_checkout_pro/install.json')){
             $data['state']['pro'] = true;
        }


        $this->load->model('localisation/language');
        $data['state']['languages'] = $this->model_localisation_language->getLanguages();

        $data['state']['custom_fields'] = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_account->getCustomFields($this->config->get('config_customer_group_id'));

        $data['state']['error_types'] = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_error->getErrorTypes();

        $data['edit'] = false;

        if($this->user->isLogged() && isset($this->request->get['edit'])){
            $data['state']['edit'] = true;
            $data['edit'] = true;
            $data['state']['settings'] = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getAllSettings();
        }

        $data['state']['layout']['js'] = $data['state']['layout']['js'] ?? '';

        return $this->load->view($this->model_extension_ajax_quick_checkout_ajax_quick_checkout_view->template($this->route), $data);
    }

    public function update(){

        $setting_id = 1;

        if($this->validate()){
            $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/store');


            //REFACTOR - need a cleaner way to update pages.
            //$post = $this->request->post;
            $rawData = file_get_contents('php://input');
            $post = json_decode($rawData, true);
            if(!$post){
                $post = $this->request->post;
            }

            $this->load->model('localisation/language');

            $results = $this->model_localisation_language->getLanguages();

            foreach ($results as $result) $language_data[$result['code']] = $result;

            // Language not available then use default
            $code = $this->config->get('config_language');

            if (isset($post['language_code']) && array_key_exists($post['language_code'], $language_data)) {
                $code = $post['language_code'];
            }

            // Set the config language_id
            $this->config->set('config_language_id', $language_data[$code]['language_id']);
            $this->config->set('config_language', $code);

            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->loadState();

            if(isset($post['layout'])){
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('layout'), $post['layout']);
                unset($post['layout']);
            }
            if(isset($post['config']) && isset($post['config']['guest'])){
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('config'), $post['config']);
                unset($post['config']);
            }
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState($post);

            $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();

            $state['status'] = 1;
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->editSetting($state);

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($state));
        }
    }

    protected function validate() {
        $error = [];
        if (!$this->user->hasPermission('modify', $this->route)) {
            $error['warning'] = $this->language->get('error_permission');
        }

        return !$error;
    }

    public function change_language(){
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/store');
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->loadState();

        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($state));
    }

    public function get_language(){
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/store');
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->loadState();

        $rawData = file_get_contents('php://input');
        $post = json_decode($rawData, true);
        if(!$post){
            $post = $this->request->post;
        }

        $this->load->model('localisation/language');

		$results = $this->model_localisation_language->getLanguages();

		foreach ($results as $result) $language_data[$result['code']] = $result;

		// Language not available then use default
		$code = $this->config->get('config_language');

		if (isset($post['language']) && array_key_exists($post['language'], $language_data)) {
			$code = $post['language'];
		}

		// Set the config language_id
		$this->config->set('config_language_id', $language_data[$code]['language_id']);
		$this->config->set('config_language', $code);

		// Language
		$language = new \Opencart\System\Library\Language($code);

		if (!$language_data[$code]['extension']) {
			$language->addPath(DIR_LANGUAGE);
		} else {
			$language->addPath(DIR_EXTENSION . $language_data[$code]['extension'] . '/catalog/language/');
		}

		$language->load($code);

		$this->registry->set('language', $language);

        $this->initSteps();

        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
        $state['language']['general'] = $this->getLanguage();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($state));
    }

    public function get_store_setting(){
        $rawData = file_get_contents('php://input');
        $post = json_decode($rawData, true);
        if(!$post){
            $post = $this->request->post;
        }
        $this->request->post = $post;
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/store');
        // $this->model_extension_ajax_quick_checkout_store->loadState();

        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->initState();

        $this->initSteps(true);

        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($state));
    }

    public function reset(){
        $rawData = file_get_contents('php://input');
        $post = json_decode($rawData, true);
        if(!$post){
            $post = $this->request->post;
        }
        $this->request->post = $post;
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/store');
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->clearState();
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->initState();

        $this->initSteps(true);

        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($state));
    }

    public function change_layout(){

        $rawData = file_get_contents('php://input');
        $post = json_decode($rawData, true);
        if(!$post){
            $post = $this->request->post;
        }
        $this->request->post = $post;

        if(isset($post['layout_codename'])){

            $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/store');
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->changeLayout($post['layout_codename']);
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->initState();

            $this->initSteps(true);
        }

        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($state));
    }

    //REFACTOR !!!!
    public function open_page(){
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/store');
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->loadState();

        //REFACTOR - need a cleaner way to update pages.
        $rawData = file_get_contents('php://input');
        $post = json_decode($rawData, true);
        if(!$post){
            $post = $this->request->post;
        }

        if(isset($post['layout'])){
            unset($post['layout']);
        }


        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState($post);

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

        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/store');
        return $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->initState();

    }

    public function controller_checkout_checkout_before($route, &$data) {
        if($this->user->isLogged() && isset($this->request->get['edit'])){
            if(!isset($this->cart)){
                $this->cart = new \Opencart\System\Library\Cart\Cart($this->registry);
            }

            $cart = $this->cart->getProducts();

            $store_id = $this->config->get('config_store_id');

            if(!$cart){
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.quantity > '0' AND p2s.store_id = '".(int)$store_id."' LIMIT 1");

                $product = $query->row;

                $this->cart->add($product['product_id']);
            }
        }
    }

    public function view_checkout_checkout_before($route, &$data) {
        $data['ajax_quick_checkout'] = $this->load->controller('extension/ajax_quick_checkout/module/ajax_quick_checkout');
        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
        $skin = $state['layout']['skin'] ?? 'default';


        $scripts = [];
        $styles = [];

        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/cash/cash.min.js');
        $styles[] = (HTTP_SERVER . 'extension/dv_dialogify/catalog/view/stylesheet/dv_dialogify.min.css');
        $scripts[] = (HTTP_SERVER . 'extension/dv_dialogify/catalog/view/javascript/dv_dialogify.min.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/serializejson/serializeJSON.min.js');

        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/lodash/lodash.min.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/sortable/dqc_html5_sortable.min.js');
        $styles[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/animate/animate.min.css');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/d_riot/riotcompiler.min.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/d_alertify/alertify.min.js');
        $styles[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/d_alertify/css/alertify.min.css');

        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/core/main.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/core/component/setting.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/core/component/page.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/core/component/row.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/core/component/col.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/core/component/step.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/core/component/field.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/core/component/error.js');

        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/core/step/account.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/core/step/cart.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/core/step/confirm.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/core/step/custom.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/core/step/payment_address.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/core/step/payment_method.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/core/step/shipping_address.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/core/step/shipping_method.js');

        $styles[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/balloon/balloon.css');
        $styles[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/jqueryui/jquery-ui.css');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/interact/dqc_interact.min.js');

        $styles[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/choices/choices.min.css');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/choices/choices.min.js');

        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/jsondiffpatch/jsondiffpatch.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/axios/axios.min.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/imask/imask.min.js');
        $styles[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/intltelinput/css/intlTelInput.min.css');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/intltelinput/js/intlTelInput.min.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/intltelinput/js/utils.js');


        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/datetimepicker/moment/moment.min.js');
        $styles[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/datetimepicker/flatpickr.min.css');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/datetimepicker/dqc_flatpickr.min.js');
        $scripts[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/datetimepicker/moment/locales.min.js');

        if($this->config->get('ajax_quick_checkout_rtl')){
            if (isset($this->request->get['language'])) {
                $language = $this->request->get['language'];
            } else {
                $this->load->model('localisation/language');
                $language_info = $this->model_localisation_language->getLanguage((int)$this->config->get('config_language_id'));
                $language = $language_info['code'];
            }
            $rtl = $this->config->get('ajax_quick_checkout_rtl');
            if(!empty($rtl[$language])){
                $styles[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/ripecss/ripe.rtl.css');
                $styles[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/stylesheet/ajax_quick_checkout/libraries/main.css');
                $styles[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/stylesheet/ajax_quick_checkout/libraries/rtl.css');
            }else{
                $styles[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/ripecss/ripe.css');
                $styles[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/stylesheet/ajax_quick_checkout/main.css');
            }
        }else{
            $styles[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/javascript/ajax_quick_checkout/libraries/ripecss/ripe.css');
            $styles[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/stylesheet/ajax_quick_checkout/main.css');
        }

        if(is_file(DIR_EXTENSION.'ajax_quick_checkout_pro/install.json')) {
            $styles[] = (HTTP_SERVER . 'extension/ajax_quick_checkout_pro/catalog/view/stylesheet/ajax_quick_checkout/skin/'.$skin .'/'.$skin .'.css?'.rand());
        } else {
            $styles[] = (HTTP_SERVER . 'extension/ajax_quick_checkout/catalog/view/stylesheet/ajax_quick_checkout/skin/'.$skin .'/'.$skin .'.css?'.rand());
        }

        $data['header'] = $this->parseHeader($data['header'], $scripts, $styles);
    }

    public function view_checkout_checkout_after($route, $data, &$output) {
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/view');
        $supports = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_view->browserSupported();
        if($supports){
            $output = $this->load->view('extension/ajax_quick_checkout/checkout/ajax_quick_checkout', $data);
        }
    }

    public function getLanguage(){
        $this->load->language('checkout/checkout');
        $this->load->language('checkout/cart');
        $this->load->language('checkout/confirm');
        $this->load->language('checkout/failure');
        $this->load->language('checkout/payment_address');
        $this->load->language('checkout/shipping_address');
        $this->load->language('checkout/payment_method');
        $this->load->language('checkout/shipping_method');

        $data['entry_address'] = $this->language->get('entry_address');
        $data['text_address_existing'] = $this->language->get('text_address_existing');
        $data['text_address_new'] = $this->language->get('text_address_new');

        $this->load->language('extension/ajax_quick_checkout/module/ajax_quick_checkout');
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
        $data['entry_captcha'] = $this->language->get('entry_captcha');


        $data['error_min_length'] = $this->language->get('error_min_length');
        $data['error_max_length'] = $this->language->get('error_max_length');
        $data['error_compare_to'] = $this->language->get('error_compare_to');
        $data['error_not_empty'] = $this->language->get('error_not_empty');
        $data['error_checked'] = $this->language->get('error_checked');
        $data['error_regex'] = $this->language->get('error_regex');
        $data['error_telephone'] = $this->language->get('error_telephone');
        $data['error_email_exists'] = $this->language->get('error_email_exists');
        $data['error_captcha'] = $this->language->get('error_captcha');


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


        $data['text_cart_title'] = $this->language->get('heading_title');
        $data['text_cart_empty'] = $this->language->get('text_no_results');

        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/store');
        $language = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getLanguage();
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
        if (isset($this->request->get['language'])) {
            $language = $this->request->get['language'];
        } else {
            $this->load->model('localisation/language');
            $language_info = $this->model_localisation_language->getLanguage((int)$this->config->get('config_language_id'));
            $language = $language_info['code'];
        }

        return HTTP_SERVER.'catalog/language/'.$language.'/'. $language .'.png';
    }

    public function initSteps($initOrder = false){

            $this->load->controller('extension/ajax_quick_checkout/ajax_quick_checkout/account');
            $this->load->controller('extension/ajax_quick_checkout/ajax_quick_checkout/payment_address'); //2.6
            $this->load->controller('extension/ajax_quick_checkout/ajax_quick_checkout/shipping_address'); //1.5
            $this->load->controller('extension/ajax_quick_checkout/ajax_quick_checkout/custom'); //0.12
            if($initOrder){
                $order_id = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_order->getOrder();
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('session', 'order_id'), $order_id);
            }
            $this->load->controller('extension/ajax_quick_checkout/ajax_quick_checkout/shipping_method'); //3
            $this->load->controller('extension/ajax_quick_checkout/ajax_quick_checkout/payment_method'); //10
            $this->load->controller('extension/ajax_quick_checkout/ajax_quick_checkout/cart'); //1.5
            $this->load->controller('extension/ajax_quick_checkout/ajax_quick_checkout/confirm'); //0.36
            $this->load->controller('extension/ajax_quick_checkout/ajax_quick_checkout/payment'); //4.5
    }

    private function parsseHeader($header, $pro, $skin) {

        $html_dom->load((string)$header, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);



        foreach ($scripts as $script) {
            if (!$html_dom->find('head', 0)->find('script[src="' . $script . '"]')) {
                if ($html_dom->find('head > script', -1)) {
                    $html_dom->find('head > script', -1)->outertext .= '<script src="' . $script . '" type="text/javascript"></script>';
                } else {
                    $html_dom->find('head', -1)->innertext .= '<script src="' . $script . '" type="text/javascript"></script>';
                    $html_dom->load((string)$html_dom, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);
                }
            }
        }
        foreach ($styles as $style) {
            if (!$html_dom->find('head', 0)->find('link[href="' . $style . '"]')) {
                if ($html_dom->find('head > link', -1)) {
                    $html_dom->find('head > link', -1)->outertext .= '<link href="' . $style . '" rel="stylesheet" type="text/css"/>';
                } else {
                    $html_dom->find('head', -1)->innertext .= '<link href="' . $style . '" rel="stylesheet" type="text/css"/>';
                    $html_dom->load((string)$html_dom, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);
                }
            }
        }

        return (string)$html_dom;
    }

    protected function parseHeader($header, $scripts, $styles)
    {
        $html_dom = new \Opencart\System\Library\Extension\DvSimpleHtmlDom\DvSimpleHtmlDom();
        $html_dom->load($header, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

        foreach ($scripts as $script) {
            if (!$html_dom->find('head', 0)->find('script[src="' . $script . '"]')) {
                if ($html_dom->find('head > script', -1)) {
                    $html_dom->find('head > script', -1)->outertext .= '<script src="' . $script . '" type="text/javascript"></script>';
                } else {
                    $html_dom->find('head', -1)->innertext .= '<script src="' . $script . '" type="text/javascript"></script>';
                    $html_dom->load((string)$html_dom, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);
                }
            }
        }
        foreach ($styles as $style) {
            if (!$html_dom->find('head', 0)->find('link[href="' . $style . '"]')) {
                if ($html_dom->find('head > link', -1)) {
                    $html_dom->find('head > link', -1)->outertext .= '<link href="' . $style . '" rel="stylesheet" type="text/css"/>';
                } else {
                    $html_dom->find('head', -1)->innertext .= '<link href="' . $style . '" rel="stylesheet" type="text/css"/>';
                    $html_dom->load((string)$html_dom, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);
                }
            }
        }
        return (string)$html_dom;
    }
}
