<?php
namespace Opencart\Admin\Controller\Extension\AjaxQuickCheckout\Module;

class AjaxQuickCheckout extends \Opencart\System\Engine\Controller {

    private $codename = 'ajax_quick_checkout';
    private $route = 'extension/ajax_quick_checkout/module/ajax_quick_checkout';
    private $config_file = 'ajax_quick_checkout';
    private $extension = array();
    private $store_id = 0;
    private $error = array();
    private $pro = '';

    private $opencart_patch = false;

    public function __construct($registry) {
        parent::__construct($registry);

        $this->opencart_patch = is_file(DIR_EXTENSION . 'dv_opencart_patch/install.json');
        $this->extension = json_decode(file_get_contents(DIR_EXTENSION . 'ajax_quick_checkout/install.json'), true);

        $this->load->model('extension/ajax_quick_checkout/module/ajax_quick_checkout');
        $this->model_extension_ajax_quick_checkout_module_ajax_quick_checkout->loadDependencies();

        $this->store_id = (isset($this->request->get['store_id'])) ? (int)$this->request->get['store_id'] : 0;
        if(is_file(DIR_EXTENSION.'ajax_quick_checkout_pro/install.json')) $this->pro .= '_pro';

        $this->config->addPath(DIR_EXTENSION . 'ajax_quick_checkout' . $this->pro . '/system/config/');
        $this->load->model('localisation/language');

        if(!isset($this->customer)){
            $this->customer = new \Opencart\System\Library\Cart\Customer($registry);
        }
    }

    public function index(){

        $this->load->model('localisation/language');


        $this->load->language($this->route);

        if (isset($this->session->data['user_token'])) {
			$url_token =  'user_token=' . $this->session->data['user_token'];
		}

        $data['languages'] = array();

        $this->load->model('setting/setting');

        $this->document->addScript(HTTP_CATALOG . 'extension/ajax_quick_checkout/admin/view/javascript/d_bootstrap_switch/js/bootstrap-switch.min.js');
        $this->document->addStyle(HTTP_CATALOG . 'extension/ajax_quick_checkout/admin/view/javascript/d_bootstrap_switch/css/bootstrap-switch.css');
        $this->document->addStyle(HTTP_CATALOG . 'extension/ajax_quick_checkout/admin/view/stylesheet/d_bootstrap_extra/bootstrap.css');
        $this->document->addScript(HTTP_CATALOG . 'extension/ajax_quick_checkout/admin/view/javascript/d_alertify/alertify.min.js');
        $this->document->addStyle(HTTP_CATALOG . 'extension/ajax_quick_checkout/admin/view/javascript/d_alertify/css/alertify.min.css');
        $this->document->addStyle(HTTP_CATALOG . 'extension/ajax_quick_checkout/admin/view/javascript/d_alertify/css/themes/semantic.min.css');


        $this->document->setTitle($this->language->get('heading_title_main'));
        $data['heading_title'] = $this->language->get('heading_title_main');
        $data['text_edit'] = $this->language->get('text_edit');

        // Variable
        $data['codename'] = $this->codename;
        $data['route'] = $this->route;
        $data['version'] = $this->extension['version'];
        $data['token'] =  $this->session->data['user_token'];
        $data['pro'] = $this->pro;

        $data['store_id'] = $this->store_id;

        // Tab
        $data['tab_setting'] = $this->language->get('tab_setting');

		$data['non_installed'] = $this->getNonInstalledDependencies();
		if ($data['non_installed']) {
			$data['location'] = html_entity_decode($this->url->link('extension/ajax_quick_checkout/module/'.$this->codename, 'user_token=' . $this->session->data['user_token']));
			$data['user_token'] = $this->session->data['user_token'];
			$data['non_installed'] = json_encode($data['non_installed']);
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view('extension/ajax_quick_checkout/module/dependencies_installer', $data));
            return ;
        }
        $this->load->model('extension/dv_opencart_patch/library/url');
        //action
        $data['module_link'] = $this->model_extension_dv_opencart_patch_library_url->link($this->route, $url_token);
        $data['action'] = $this->model_extension_dv_opencart_patch_library_url->link($this->route . '|save', $url_token);
        $data['editor'] = $this->model_extension_dv_opencart_patch_library_url->link($this->route . '|editor', $url_token);
        $data['add_setting'] = htmlspecialchars_decode($this->model_extension_dv_opencart_patch_library_url->link($this->route.'|addSetting', $url_token));
        $data['delete_setting'] = htmlspecialchars_decode($this->model_extension_dv_opencart_patch_library_url->link($this->route.'|deleteSetting'));
        $data['change_store'] = $this->model_extension_dv_opencart_patch_library_url->link($this->route.'|changeStore');
        $data['cancel'] = $this->model_extension_dv_opencart_patch_library_url->link('marketplace/extension', $url_token . '&type=module', true);

        $this->load->model('setting/store');
        $data['stores'] = $this->model_setting_store->getStores();


        if (isset($this->request->post[$this->codename.'_status'])) {
            $data[$this->codename.'_status'] = $this->request->post[$this->codename.'_status'];
        } else {
            $data[$this->codename.'_status'] = $this->config->get($this->codename.'_status');
        }

        $results = $this->model_localisation_language->getLanguages();

		foreach ($results as $result) {
			if ($result['status']) {
				$data['languages'][] = array(
					'name' => $result['name'],
					'code' => $result['code'],
				);
			}
		}

        $data['version_opencart'] = VERSION;

        foreach ($data['languages'] as $language) {
			if (isset($this->request->post['ajax_quick_checkout_rtl'])) {
                $rtl = json_decode($this->request->post['ajax_quick_checkout_rtl']);
                $data['ajax_quick_checkout_rtl'.'['.$language['code'].']'] = $rtl[$language['code']];
            }elseif ($this->config->get('ajax_quick_checkout_rtl') != '') {
                $rtl = $this->config->get('ajax_quick_checkout_rtl');
                $data['ajax_quick_checkout_rtl'.'['.$language['code'].']'] = $rtl[$language['code']];
            }else {
                $data['ajax_quick_checkout_rtl'.'['.$language['code'].']'] = 0;
            }
		}

        $data['settings'] = $this->getSettings();

        // Breadcrumbs
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->model_extension_dv_opencart_patch_library_url->link('common/home', $url_token, true)
            );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->model_extension_dv_opencart_patch_library_url->link('marketplace/extension', $url_token . '&type=module', true)
            );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->model_extension_dv_opencart_patch_library_url->link($this->route, $url_token)
            );

        // Notification
        foreach($this->error as $key => $error){
            $data['error'][$key] = $error;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/ajax_quick_checkout/module/ajax_quick_checkout', $data));
    }

    public function save() {
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (isset($this->request->post[$this->codename.'_status'])) {
                if ((int)$this->request->post[$this->codename.'_status']) {
                    $this->installEvents();
                } else {
                    $this->uninstallEvents();
                }
            }
            $this->model_setting_setting->editSetting($this->codename, $this->request->post, $this->store_id);
            $json['success'] = $this->language->get('success_modifed');
        }

        $json['error'] = $this->error;

        $this->response->setOutput(json_encode($json));
    }

    public function addSetting(){
        $name = 'Setting';
        $store_id = $this->store_id;


        $this->db->query("INSERT INTO `" . DB_PREFIX . "dqc_setting`
            SET `store_id` = '" . (int)$store_id . "',
                `name` = '" . $this->db->escape($name) . "',
                `date_added` = NOW(),
                `date_modified` = NOW()");


        $data['ajax_quick_checkout_status'] = 1;


        $data['ajax_quick_checkout_rtl'] = '';
        $this->load->model('setting/setting');

        $this->model_setting_setting->editSetting($this->codename, $data, $this->store_id);

        $json = $this->getSettings();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }

    public function deleteSetting(){
        $json = false;
        if(isset($this->request->post['setting_id'])){
            $setting_id = $this->request->post['setting_id'];

            $this->load->model('extension/ajax_quick_checkout/module/ajax_quick_checkout');
            $this->model_extension_ajax_quick_checkout_module_ajax_quick_checkout->deleteSetting($setting_id);

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
        //fixing issue with visual editor

        $option = [
			'expires'  => time() + (int)$this->config->get('config_session_expire'),
			'path'     =>  (!empty($this->request->server['PHP_SELF']) ? rtrim(dirname(dirname($this->request->server['PHP_SELF'])), '/') . '/' : '/'),
			'secure'   => $this->request->server['HTTPS'],
			'httponly' => false,
			'SameSite' => $this->config->get('config_session_samesite')
		];

		setcookie($this->config->get('session_name'), $this->session->getId(), $option);

        $data = array();

        $data['non_installed'] = $this->getNonInstalledDependencies();
		if ($data['non_installed']) {
			$data['location'] = html_entity_decode($this->url->link('extension/ajax_quick_checkout/module/'.$this->codename, 'user_token=' . $this->session->data['user_token']));
			$data['user_token'] = $this->session->data['user_token'];
			$data['non_installed'] = json_encode($data['non_installed']);
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view('extension/ajax_quick_checkout/module/dependencies_installer', $data));
            return ;
        }

        $this->load->model('extension/dv_opencart_patch/library/url');

        $setting_id = 0;
        if(isset($this->request->get['setting_id'])){
            $setting_id = (int)$this->request->get['setting_id'];
        }
        $url_token =  'user_token=' . $this->session->data['user_token'];
        $store_id = 0;

        $url = HTTP_CATALOG;

        $this->load->model('extension/dv_opencart_patch/library/url');

        $data['custom_field_admin_url'] = $this->model_extension_dv_opencart_patch_library_url->link('customer/custom_field', $url_token, true);

        $this->load->model('extension/ajax_quick_checkout/module/ajax_quick_checkout');
        $setting = $this->model_extension_ajax_quick_checkout_module_ajax_quick_checkout->getSetting($setting_id);
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

        $data['editor'] = $url.'index.php?route=checkout/checkout&edit=1&setting_id='.$setting_id . '&language=' . $this->config->get('config_language');
        $this->response->setOutput($this->load->view('extension/ajax_quick_checkout/ajax_quick_checkout/editor', $data));
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
        if ($this->opencart_patch) {
            $this->installEvents();

            $this->load->model('extension/ajax_quick_checkout/module/ajax_quick_checkout');
            $this->model_extension_ajax_quick_checkout_module_ajax_quick_checkout->installDatabase();
        }
    }

    public function uninstall() {
        if ($this->opencart_patch) {
            $this->uninstallEvents();

            $this->load->model('extension/ajax_quick_checkout/module/ajax_quick_checkout');
            $this->model_extension_ajax_quick_checkout_module_ajax_quick_checkout->uninstallDatabase();
        }
    }

    private function installEvents() {
        if ($this->opencart_patch) {
            $this->load->model('extension/dv_opencart_patch/setting/event');

            $this->model_extension_dv_opencart_patch_setting_event->deleteEventByCode($this->codename);

            $events = [
                0 => ['code' => $this->codename,
                'description' => 'Ajax Quick Checkout',
                'trigger' => 'catalog/controller/checkout/checkout/before',
                'action' => 'extension/ajax_quick_checkout/module/ajax_quick_checkout|controller_checkout_checkout_before',
                'status' => 1,
                'sort_order' => 0],
                 1 => ['code' => $this->codename,
                'description' => 'Ajax Quick Checkout',
                'trigger' => 'catalog/view/checkout/checkout/before',
                'action' => 'extension/ajax_quick_checkout/module/ajax_quick_checkout|view_checkout_checkout_before',
                'status' => 1,
                'sort_order' => 0],
                2 => ['code' => $this->codename,
                'description' => 'Ajax Quick Checkout',
                'trigger' => 'catalog/view/checkout/checkout/after',
                'action' => 'extension/ajax_quick_checkout/module/ajax_quick_checkout|view_checkout_checkout_after',
                'status' => 1,
                'sort_order' => 0],
                3 => ['code' => $this->codename,
                'description' => 'Ajax Quick Checkout',
                'trigger' => 'catalog/view/extension/*/checkout/checkout/after',
                'action' => 'extension/ajax_quick_checkout/module/ajax_quick_checkout|view_checkout_checkout_after',
                'status' => 1,
                'sort_order' => 0],
            ];

            foreach ($events as $event) {
                $this->model_extension_dv_opencart_patch_setting_event->addEvent($event);
            }
        }
    }

    private function uninstallEvents() {
        if ($this->opencart_patch) {
            $this->load->model('extension/dv_opencart_patch/setting/event');
            $this->model_extension_dv_opencart_patch_setting_event->deleteEventByCode($this->codename);
        }
    }

    public function installDependencies() {
		$this->load->language('marketplace/installer');

		$json = [];

		$code = $this->request->get['code'] ?? '';

		$page = $this->request->get['page'] ?? 1;

		$this->load->model('setting/extension');

		$extension_install_info = $code ? $this->model_setting_extension->getInstallByCode($code) : [];

		if ($extension_install_info) {
			$file = DIR_STORAGE . 'marketplace/' . $extension_install_info['code'] . '.ocmod.zip';

			if (!is_file($file)) {
				$json['error'] = sprintf($this->language->get('error_file'), $extension_install_info['code'] . '.ocmod.zip');
			}

			if ((int)$page > 1 && !is_dir(DIR_EXTENSION . $extension_install_info['code'] . '/')) {
				$json['error'] = sprintf($this->language->get('error_directory'), $extension_install_info['code'] . '/');
			}
		} else {
			$json['error'] = $this->language->get('error_extension');
		}

		if (!$json) {
			// Unzip the files
			$zip = new \ZipArchive();

			if ($zip->open($file)) {
				$total = $zip->numFiles;
				$limit = 200;

				$start = ((int)$page - 1) * $limit;
				$end = $start > ($total - $limit) ? $total : ($start + $limit);

				// Check if any of the files already exist.
				for ($i = $start; $i < $end; $i++) {
					$source = $zip->getNameIndex($i);

					$destination = str_replace('\\', '/', $source);

					// Only extract the contents of the upload folder
					$path = $extension_install_info['code'] . '/' . $destination;
					$base = DIR_EXTENSION;
					$prefix = '';

					// image > image
					if (substr($destination, 0, 6) == 'image/') {
						$path = $destination;
						$base = substr(DIR_IMAGE, 0, -6);
					}

					// We need to store the path differently for vendor folders.
					if (substr($destination, 0, 15) == 'system/storage/') {
						$path = substr($destination, 15);
						$base = DIR_STORAGE;
						$prefix = 'system/storage/';
					}

					// Must not have a path before files and directories can be moved
					$path_new = '';

					$directories = explode('/', dirname($path));

					foreach ($directories as $directory) {
						if (!$path_new) {
							$path_new = $directory;
						} else {
							$path_new = $path_new . '/' . $directory;
						}

						// To fix storage location
						if (!is_dir($base . $path_new . '/') && mkdir($base . $path_new . '/', 0777)) {
							$this->model_setting_extension->addPath($extension_install_info['extension_install_id'], $prefix . $path_new);
						}
					}

					// If check if the path is not directory and check there is no existing file
					if (substr($source, -1) != '/') {
						if (!is_file($base . $path) && copy('zip://' . $file . '#' . $source, $base . $path)) {
							$this->model_setting_extension->addPath($extension_install_info['extension_install_id'], $prefix . $path);
						}
					}
				}

				$zip->close();
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_progress'), 2, $total);

			$url = '';

			$url .= '&code=' . $code;


			if (((int)$page * 200) <= $total) {
                if (VERSION > '4.0.1.1') {
                    $json['next'] = $this->url->link('extension/ajax_quick_checkout/module/ajax_quick_checkout.installDependencies', 'user_token=' . $this->session->data['user_token'] . $url . '&page=' . ((int)$page + 1), true);
                } else {
                    $json['next'] = $this->url->link('extension/ajax_quick_checkout/module/ajax_quick_checkout|installDependencies', 'user_token=' . $this->session->data['user_token'] . $url . '&page=' . ((int)$page + 1), true);
                }

			} else {
                if (VERSION > '4.0.1.1') {
                    $this->load->controller('marketplace/installer.vendor');
                } else {
                    $this->load->controller('marketplace/installer|vendor');
                }


				$output = json_decode($this->response->getOutput(), 1);

				$json = array_merge($json, $output);

				$extension = json_decode(file_get_contents(DIR_EXTENSION . $code . '/install.json'), 1);
				if (!empty($extension['dependencies'])) {
					$json['dependencies'] = array_keys($extension['dependencies']);
				} else if ($this->getNonInstalledDependencies()) {
                    $this->opencart_patch = is_file(DIR_EXTENSION . 'dv_opencart_patch/install.json');
                    $this->install();
                }
				$this->model_setting_extension->editStatus($extension_install_info['extension_install_id'], 1);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
    private function getNonInstalledDependencies($dependencies = []) {
		$dependencies = $dependencies ? $dependencies : array_keys($this->extension['dependencies']);
		$this->load->model('setting/extension');
		$non_installed = [];
		foreach ($dependencies as $dependency) {
			$install = $this->model_setting_extension->getInstallByCode($dependency);
			if (empty($install['status'])) {
				$non_installed[] = $dependency;
			} else {
				$dependency_extension = json_decode(file_get_contents(DIR_EXTENSION . $dependency . '/install.json'), 1);
				if (!empty($dependency_extension['dependencies'])) {
					$non_installed = array_merge($non_installed, $this->getNonInstalledDependencies(array_keys($dependency_extension['dependencies'])));
				}
			}
		}

		return $non_installed;
	}
}
