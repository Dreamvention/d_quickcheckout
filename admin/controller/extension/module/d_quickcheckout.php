<?php
/*
 *  location: admin/controller
 */
class ControllerExtensionModuleDQuickcheckout extends Controller {

    private $codename = 'd_quickcheckout';
    private $route = 'extension/module/d_quickcheckout';
    private $config_file = 'd_quickcheckout';
    private $extension = array();
    private $store_id = 0;
    private $error = array();


    public function __construct($registry) {
        parent::__construct($registry);

        $this->d_shopunity = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_shopunity.json'));
        $this->d_opencart_patch = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_opencart_patch.json'));
        $this->d_quickcheckout_pack = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_quickcheckout_pack.json'));
        $this->d_admin_style = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_admin_style.json'));
        $this->d_twig_manager = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_twig_manager.json'));
        $this->d_event_manager = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_event_manager.json'));
        $this->extension = json_decode(file_get_contents(DIR_SYSTEM.'library/d_shopunity/extension/'.$this->codename.'.json'), true);
        $this->store_id = (isset($this->request->get['store_id'])) ? $this->request->get['store_id'] : 0;

        if($this->d_admin_style){
            $this->load->model('extension/d_admin_style/style');
            $this->model_extension_d_admin_style_style->getStyles('light');
        }
        
        if(!isset($this->customer)){
            if(VERSION < '2.2.0.0'){
                $this->customer = new Customer($registry);
            }else{
                $this->customer = new Cart\Customer($registry);
            }
        }
    }

    public function index(){

        if($this->d_shopunity){
            $this->load->model('extension/d_shopunity/mbooth');
            $this->model_extension_d_shopunity_mbooth->validateDependencies($this->codename);
        }

        if($this->d_twig_manager){
            $this->load->model('extension/module/d_twig_manager');
            $this->model_extension_module_d_twig_manager->installCompatibility();
        }

        if($this->d_event_manager){
            $this->load->model('extension/module/d_event_manager');
            $this->model_extension_module_d_event_manager->installCompatibility();
        }

        $this->load->model('extension/module/d_quickcheckout');
        $this->model_extension_module_d_quickcheckout->update();

        $this->load->language($this->route);

        $this->load->model('setting/setting');
        $this->load->model('extension/d_opencart_patch/load');
        $this->load->model('extension/d_opencart_patch/user');
        $this->load->model('extension/d_opencart_patch/url');
        $this->load->model('extension/d_opencart_patch/cache');

        $this->model_extension_d_opencart_patch_cache->clearTwig();


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting($this->codename, $this->request->post, $this->store_id);
            $this->session->data['success'] = $this->language->get('success_modifed');
            $this->response->redirect($this->model_extension_d_opencart_patch_url->getExtensionLink('module'));
        }

        $this->document->addScript('view/javascript/d_bootstrap_switch/js/bootstrap-switch.min.js');
        $this->document->addStyle('view/javascript/d_bootstrap_switch/css/bootstrap-switch.css');
        $this->document->addStyle('view/stylesheet/d_bootstrap_extra/bootstrap.css');


        $this->document->setTitle($this->language->get('heading_title_main'));
        $data['heading_title'] = $this->language->get('heading_title_main');
        $data['text_edit'] = $this->language->get('text_edit');

        // Variable
        $data['codename'] = $this->codename;
        $data['route'] = $this->route;
        $data['version'] = $this->extension['version'];
        $data['token'] =  $this->model_extension_d_opencart_patch_user->getToken();
        $data['pro'] = $this->d_quickcheckout_pack;
        $data['d_shopunity'] = $this->d_shopunity;

        $data['store_id'] = $this->store_id;

        // text
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_editor'] = $this->language->get('text_editor');
        $data['text_open_editor'] = $this->language->get('text_open_editor');
        $data['text_get_pro'] = $this->language->get('text_get_pro');
        $data['help_editor'] = $this->language->get('help_editor');

        //entry
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_rtl'] = $this->language->get('entry_rtl');

        $data['entry_support'] = $this->language->get('entry_support');
        $data['text_support'] = $this->language->get('text_support');

        $data['text_powered_by'] = $this->language->get('text_powered_by');
        
        // Tab
        $data['tab_setting'] = $this->language->get('tab_setting');

        // Button
        $data['button_save'] = $this->language->get('button_save');
        $data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
        $data['button_cancel'] = $this->language->get('button_cancel');
        
        
        //action
        $data['module_link'] = $this->model_extension_d_opencart_patch_url->link($this->route);
        $data['action'] = $this->model_extension_d_opencart_patch_url->link($this->route);
        $data['editor'] = $this->model_extension_d_opencart_patch_url->ajax($this->route.'/editor');
        $data['add_setting'] = $this->model_extension_d_opencart_patch_url->ajax($this->route.'/addSetting');
        $data['delete_setting'] = $this->model_extension_d_opencart_patch_url->ajax($this->route.'/deleteSetting');
        $data['change_store'] = $this->model_extension_d_opencart_patch_url->ajax($this->route.'/changeStore');
        $data['cancel'] = $this->model_extension_d_opencart_patch_url->getExtensionLink('module');

        $this->load->model('extension/d_opencart_patch/store');
        $data['stores'] = $this->model_extension_d_opencart_patch_store->getAllStores();
        

        if (isset($this->request->post[$this->codename.'_status'])) {
            $data[$this->codename.'_status'] = $this->request->post[$this->codename.'_status'];
        } else {
            $data[$this->codename.'_status'] = $this->config->get($this->codename.'_status');
        }

        if (isset($this->request->post[$this->codename.'_rtl'])) {
            $data[$this->codename.'_rtl'] = $this->request->post[$this->codename.'_rtl'];
        } else {
            $data[$this->codename.'_rtl'] = $this->config->get($this->codename.'_rtl');
        }

        $data['settings'] = $this->getSettings();

        // Breadcrumbs
        $data['breadcrumbs'] = array(); 
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->model_extension_d_opencart_patch_url->link('common/home')
            );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->model_extension_d_opencart_patch_url->getExtensionLink('module')
            );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->model_extension_d_opencart_patch_url->link($this->route)
            );
        
        // Notification
        foreach($this->error as $key => $error){
            $data['error'][$key] = $error;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->model_extension_d_opencart_patch_load->view('extension/module/d_quickcheckout', $data));
    }

    public function addSetting(){
        $name = 'Setting';
        $store_id = $this->store_id;

        $this->db->query("INSERT INTO `" . DB_PREFIX . "dqc_setting`
            SET `store_id` = '" . (int)$store_id . "',
                `name` = '" . $this->db->escape($name) . "',
                `date_added` = NOW(),
                `date_modified` = NOW()");

        $data['d_quickcheckout_status'] = 1;
        $data['d_quickcheckout_rtl'] = $this->isRtlNeeded();
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting($this->codename, $data, $this->store_id);

        $json = $this->getSettings();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }

    public function isRtlNeeded(){
        // $themes = array('journal');
        // $config_theme = $this->config->get('config_theme');
        // $config_default_directory = $this->config->get('theme_default_directory');

        // foreach($themes as $theme){
        //     if(strpos($config_theme, $theme) !== false){
        //         return true;
        //     }

        //     if(strpos($config_default_directory, $theme) !== false){
        //         return true;
        //     }
        // }

        return false;
    }

    public function deleteSetting(){
        $json = false;
        if(isset($this->request->post['setting_id'])){
            $setting_id = $this->request->post['setting_id'];

            $this->load->model('extension/module/d_quickcheckout');
            $this->model_extension_module_d_quickcheckout->deleteSetting($setting_id);
            
            $json = true;
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }

    public function changeStore(){
        $json = array();
        if(isset($this->request->post['store_id'])){
            $store_id = $this->request->post['store_id'];

            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "dqc_setting`
                WHERE store_id = '" . (int)$store_id . "'" );

            if($query->row){
                $json['setting_id'] = $query->row['setting_id'];
            }else{
                $name = 'Store '.$store_id;
                $this->db->query("INSERT INTO `" . DB_PREFIX . "dqc_setting`
                    SET `store_id` = '" . (int)$store_id . "',
                        `name` = '" . $this->db->escape($name) . "',
                        `date_added` = NOW(),
                        `date_modified` = NOW()");
                $json['setting_id'] = $this->db->getLastId();
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    

    public function getSettings(){
        $store_id = $this->store_id;
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "dqc_setting WHERE store_id = '" . (int)$store_id . "'");
        return $query->rows;
        
    }

    public function editor(){
        $data = array();
        $this->load->model('extension/d_opencart_patch/load');

        $setting_id = 0;
        if(isset($this->request->get['setting_id'])){
            $setting_id = $this->request->get['setting_id'];
        }

        $store_id = 0;
        $this->load->model('extension/d_opencart_patch/user');
        if($this->config->get('config_secure')){
            $url = HTTPS_CATALOG;
            $admin = HTTPS_SERVER.'?'.$this->model_extension_d_opencart_patch_user->getUrlToken();
        }else{
            $url = HTTP_CATALOG;
            $admin = HTTP_SERVER.'?'.$this->model_extension_d_opencart_patch_user->getUrlToken();
        }

        if(VERSION >= '2.1.0.0'){
            $custom_field = $admin.'&route=customer/custom_field';
        }else{
            $custom_field = $admin.'&route=sale/custom_field';
        }
        
        $this->load->model('extension/module/d_quickcheckout');
        $setting = $this->model_extension_module_d_quickcheckout->getSetting($setting_id);
        if(!empty($setting['store_id'])){
            $this->load->model('setting/setting');
            $store_setting = $this->model_setting_setting->getSetting('config', $setting['store_id']);
            
            if(isset($store_setting['config_url'])){
                $store_id = $setting['store_id'];
                if(!empty($store_setting['config_secure'])){
                    $url = $store_setting['config_ssl'];
                }else{
                    $url = $store_setting['config_url'];
                }
            }
        }

        if(!isset($this->cart)){
            $this->config->set('config_store_id', $store_id);
            if(VERSION < '2.2.0.0'){
                $this->cart = new Cart($this->registry);
            }else{
                $this->cart = new Cart\Cart($this->registry);
            }
        }

        $cart = $this->cart->getProducts();

        if(!$cart){
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p2s.store_id = '".(int)$store_id."' LIMIT 1");

            $product = $query->row;

            $this->cart->add($product['product_id']);
        }
        
        $data['editor'] = $url.'index.php?route=checkout/checkout&edit&setting_id='.$setting_id.'&admin='.urlencode($admin).'&custom_field='. urlencode($custom_field);
        $this->response->setOutput($this->model_extension_d_opencart_patch_load->view('extension/d_quickcheckout/editor', $data));
    }

    private function validate($permission = 'modify') {

        $this->language->load($this->route);
        
        if (!$this->user->hasPermission($permission, $this->route)) {
            $this->error['warning'] = $this->language->get('error_permission');
            return false;
        }

        return true;
    }

    public function install() {

        if($this->d_shopunity){
            $this->load->model('extension/d_shopunity/mbooth');
            $this->model_extension_d_shopunity_mbooth->installDependencies($this->codename);
        }

        if($this->d_opencart_patch){
            $this->load->model('extension/d_opencart_patch/modification');
            $this->model_extension_d_opencart_patch_modification->setModification('d_quickcheckout.xml', 1); 
            $this->model_extension_d_opencart_patch_modification->refreshCache();
        }

        if($this->d_event_manager){
            $this->load->model('extension/module/d_event_manager');
            $this->model_extension_module_d_event_manager->deleteEvent($this->codename);
            $this->model_extension_module_d_event_manager->addEvent($this->codename, 
                'catalog/view/checkout/checkout/after', 
                'extension/module/d_quickcheckout/view_checkout_checkout_after');
        }

        $this->load->model('extension/module/d_quickcheckout');
        $this->model_extension_module_d_quickcheckout->installDatabase();
        
    }

    public function uninstall() {
        if($this->d_opencart_patch){
            $this->load->model('extension/d_opencart_patch/modification');
            $this->model_extension_d_opencart_patch_modification->setModification('d_quickcheckout.xml', 0); 
        }
        
        if($this->d_event_manager){
            $this->load->model('extension/module/d_event_manager');

            $this->model_extension_module_d_event_manager->deleteEvent($this->codename);
        }

        $this->load->model('extension/module/d_quickcheckout');
        $this->model_extension_module_d_quickcheckout->uninstallDatabase(); 

    }

}
?>