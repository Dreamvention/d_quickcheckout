<?php
/*
 *	location: admin/model
 */

class ModelModuleDQuickcheckout extends Model {

	public function getCountries(){
		$this->load->model('localisation/country');	
		$countries = $this->model_localisation_country->getCountries();
		$options = array();
		foreach ($countries as $country){
			$country['value'] = $country['country_id']; 
			unset($country['country_id']);
			$options[] = $country;
		}
		return $options;

	}

	public function getZonesByCountryId($country_id){
		$this->load->model('localisation/zone');
		$zones =  $this->model_localisation_zone->getZonesByCountryId($country_id);
		$options = array();
		foreach ($zones as $zone){
			$zone['value'] = $zone['zone_id']; 
			unset($zone['zone_id']);
			$options[] = $zone;
		}
		return $options;

	}

	public function getShippingMethods() {
		$shipping_methods = glob(DIR_APPLICATION . 'controller/shipping/*.php');
		$result = array();
		foreach ($shipping_methods as $shipping){
			$shipping = basename($shipping, '.php');
			$this->load->language('shipping/' . $shipping);
			$result[] = array(
				'code' => $shipping,
				'title' => $this->language->get('heading_title')
			);
		}
		return $result;
	}

	public function getPaymentMethods(){
		$payment_methods = glob(DIR_APPLICATION . 'controller/payment/*.php');
		$result = array();
		foreach ($payment_methods as $payment){
			$payment = basename($payment, '.php');
			$this->load->language('payment/' . $payment);
			$result[] = array(
				'status' => $this->config->get($payment . '_status'),
				'code' => $payment,
				'title' => $this->language->get('heading_title')
			);
		}
		return $result;
	}

	public function getThemes(){
		$dir = DIR_CATALOG.'view/theme/default/stylesheet/d_quickcheckout/theme';
		$files = scandir($dir);
		$result = array();
		foreach($files as $file){
			if(strlen($file) > 6 && !strpos( $file, '.less')){
				$result[] = substr($file, 0, -4);
			}
		}
		return $result;
	}

	public function languageFilter($data){
        $this->load->model('catalog/information');
        $result = $data;
        $translate = array('title', 'description');

        if(is_array($data)){

            foreach($data as $key => $value){
                
                if(in_array($key, $translate)){
                   
                    if(!is_array($value)){

                        $result[$key] = $this->escape($this->language->get($value));
                    // }elseif(isset($value[(int)$this->config->get('config_language_id')])){
                    //     $result[$key] = $this->escape($value[(int)$this->config->get('config_language_id')]);
                    }else{
                        $result[$key] = $this->languageFilter($value);
                    }

                    if(is_string($result[$key]) && isset($result['information_id'])){
                        $information_info = $this->model_catalog_information->getInformation($result['information_id']);
                            
                        if(isset($information_info['title']) && substr_count($result[$key], '%s') == 1){
                            $result[$key] = sprintf($result[$key], $information_info['title']);  
                        }

                        if(isset($information_info['title']) && substr_count($result[$key], '%s') == 2){
                            $result[$key] = sprintf($result[$key], $this->url->link('information/information/agree', 'information_id=' . $result['information_id'], 'SSL'), $information_info['title']);  
                        }

                        if(isset($information_info['title']) && substr_count($result[$key], '%s') == 3){
                        	$result[$key] = sprintf($result[$key], $this->url->link('information/information/agree', 'information_id=' . $result['information_id'], 'SSL'), $information_info['title'], $information_info['title']);  
                        }
                    }

                }else{
                    $result[$key] = $this->languageFilter($value);
                }

            }
        }
        return $result;
    }

    public function escape($data){
    	return $data;
    }

    
	/*
	*	Vqmod: turn on or off
	*/

	public function setVqmod($xml, $action = 1){
		$dir_vqmod =  str_replace("system", "vqmod/xml", DIR_SYSTEM);
		$on  = $dir_vqmod.$xml;
		$off = $dir_vqmod.$xml.'_';
		if($action){
			if (file_exists($off)) { 
				return rename($off, $on);
			}
		}else{
			if (file_exists($on)) { 
				return rename($on, $off);
			}
		}
		return false;
	}

	/*
	*	Format the link to work with ajax requests
	*/
	public function ajax($link){
		return str_replace('&amp;', '&', $link);
	}

	/*
	*	Get file contents, usualy for debug log files.
	*/

	public function getFileContents($file){

		if (file_exists($file)) {
			$size = filesize($file);

			if ($size >= 5242880) {
				$suffix = array(
					'B',
					'KB',
					'MB',
					'GB',
					'TB',
					'PB',
					'EB',
					'ZB',
					'YB'
					);

				$i = 0;

				while (($size / 1024) > 1) {
					$size = $size / 1024;
					$i++;
				}

				return sprintf($this->language->get('error_get_file_contents'), basename($file), round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i]);
			} else {
				return file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
			}
		}
	}

	/*
	*	Return name of config file.
	*/
	public function getConfigFile($id, $sub_versions){
		
		if(isset($this->request->post['config'])){
			return $this->request->post['config'];
		}

		if(isset($this->request->get['setting_id'])){
			$setting_id = $this->request->get['setting_id'];
		}else{
			
			$setting_id = $this->config->get($id.'_setting');
		}

		$setting = $this->getSetting($setting_id);

		if(isset($setting['value']['general']['config'])){
			return $setting['value']['general']['config'];
		}

		$full = DIR_SYSTEM . 'config/'. $id . '.php';
		if (file_exists($full)) {
			return $id;
		} 

		foreach ($sub_versions as $lite){
			if (file_exists(DIR_SYSTEM . 'config/'. $id . '_' . $lite . '.php')) {
				return $id . '_' . $lite;
			}
		}
		
		return false;
	}
	/*
	*	Return list of config files that contain the id of the module.
	*/
	public function getConfigFiles($id){
		$files = array();
		$results = glob(DIR_SYSTEM . 'config/'. $id .'*');
		foreach($results as $result){
			$files[] = str_replace('.php', '', str_replace(DIR_SYSTEM . 'config/', '', $result));
		}
		return $files;
	}

	/*
	*	Get config file values and merge with config database values
	*/

	public function installDatabase(){

    	//install oc_dqc_setting
		$query = $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."dqc_setting` (
		  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
		  `store_id` int(11) NOT NULL,
		  `name` varchar(32) NOT NULL,
		  `value` text NOT NULL,
		  PRIMARY KEY (`setting_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

		//install oc_dqc_statistic	
		$query = $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."dqc_statistic` (
		  `statistic_id` int(11) NOT NULL AUTO_INCREMENT,
		  `setting_id` int(11) NOT NULL,
		  `order_id` int(11) NOT NULL,
		  `customer_id` int(11) NOT NULL,
		  `data` text NOT NULL,
		  `rating` int(11) NOT NULL, 
		  `date_added` datetime NOT NULL,
  		  `date_modified` datetime NOT NULL,
		  
		  PRIMARY KEY (`statistic_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
    }
    public function uninstallDatabase(){

    	$query = $this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."dqc_setting`");
    	$query = $this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."dqc_statistic`");
    }

    public function getCurrentSettingId($id, $store_id = 0){
    	$this->load->model('setting/setting');
    	$setting = $this->model_setting_setting->getSetting($id, $store_id);

    	if(isset($this->request->get['setting_id'])){
    		return $this->request->get['setting_id'];

				
    	}else{
    		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "dqc_setting` 
    		WHERE store_id = '" . (int)$store_id . "'" );
    		if($query->row){
    			return $query->row['setting_id'];
    		}
    	}
    	
    	return false;
    }

    public function getSettingName($setting_id){
    	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "dqc_setting` 
    		WHERE setting_id = '" . (int)$setting_id . "'" );
    	if(isset($query->row['name'])){
    		return $query->row['name'];
    	}else{
    		return false;
    	}
    	
    }

    public function getSetting($setting_id){
    	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "dqc_setting` 
    		WHERE setting_id = '" . (int)$setting_id . "'" );

    	$result = $query->row;
    	if(isset($result['value'])){
    		$result['value'] = json_decode($result['value'], true);
    	}
    	
		return $result;
		
    }

    public function getSettings($store_id, $rating = false){
    	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "dqc_setting` 
    		WHERE store_id = '" . (int)$store_id . "'"  );
    	
    	$results = $query->rows;

    	foreach ($results as $key => $result) {
			$results[$key]['value'] = json_decode($result['value'], true);
			if($rating){
				$query = $this->db->query("SELECT AVG((date_modified - date_added)) as average_checkout_time, AVG(rating) as average_rating FROM " . DB_PREFIX . "dqc_statistic WHERE setting_id = '" . (int)$result['setting_id'] . "' LIMIT 50");
				$results[$key]['average_checkout_time'] = $query->row['average_checkout_time'];
				$results[$key]['average_rating'] = $query->row['average_rating'];
			}

		}

		return $results;
    }

    public function setSetting($setting_name, $setting_value, $store_id = 0){

		$this->db->query("INSERT INTO `" . DB_PREFIX . "dqc_setting` 
			SET store_id = '" . (int)$store_id . "', 
				`name` = '" . $this->db->escape($setting_name) . "', 
				`value` = '" . $this->db->escape(json_encode($setting_value)) . "'");


		return $this->db->getLastId();
    }

    public function editSetting($setting_id, $data){
    	
		$this->db->query("UPDATE `" . DB_PREFIX . "dqc_setting` 
				SET `name` = '" . $this->db->escape($data['name']) . "', 
					`value` = '" . $this->db->escape(json_encode($data)) . "'  
				WHERE setting_id = '" . (int)$setting_id . "'");
    }

    public function deleteSetting($setting_id){
    	$this->db->query("DELETE FROM `" . DB_PREFIX . "dqc_setting` WHERE setting_id = '" . (int)$setting_id . "'");
    	$this->db->query("DELETE FROM `" . DB_PREFIX . "dqc_statistic` WHERE setting_id = '" . (int)$setting_id . "'");
    }

    public function deleteStatisticsBySettingId($setting_id){
    	$this->db->query("DELETE FROM `" . DB_PREFIX . "dqc_statistic` WHERE setting_id = '" . (int)$setting_id . "'");
    }

    public function rateStatistic($data){
 
    	$total = array('update' => 0, 'click' => 0 , 'error' => 0);
    	$field = 1;

    	if(isset($data['field'])){
    		$field = array_sum($data['field'][$data['account']]);
    	}

    	if(isset($data['update'])){
    		$total['update'] = array_sum($data['update']);
    	}

    	if(isset($data['click'])){
    		$total['click'] = array_sum($data['click']);
    	}

    	if(isset($data['error'])){
    		$total['error'] = array_sum($data['error']);
    	}
    	if(array_sum($total)){
    		$rating = ($field / array_sum($total)) - ($field/35);
    	}else{
    		$rating = 0;
    	}
    	
    	if($rating > 1){
    		$rating = 1;
    	}
    	return array('rating' => round($rating, 2), 'data' => $data, 'account' => $data['account'] , 'total' => $total, 'field' => $field); 

    }

    public function deleteStatistic($statistic_id){
    	$this->db->query("DELETE FROM `" . DB_PREFIX . "dqc_statistic` WHERE statistic_id = '" . (int)$statistic_id . "'");
    }

    public function getStatistic($statistic_id){
    	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "dqc_statistic` s LEFT JOIN " . DB_PREFIX . "order o ON (o.order_id = s.order_id) WHERE statistic_id = '" . (int)$statistic_id . "'");
    	return $query->row;
    }

    public function getStatistics($setting_id){
    	$query = $this->db->query("SELECT *, (s.date_modified - s.date_added) as checkout_time FROM `" . DB_PREFIX . "dqc_statistic` s 
    		LEFT JOIN `" . DB_PREFIX . "order` o ON (o.order_id = s.order_id) 
    		WHERE setting_id = '" . (int)$setting_id . "' ORDER BY o.order_id DESC LIMIT 50");
    	
    	foreach($query->rows as $key => $data){
    		$query->rows[$key]['data'] = $this->rateStatistic(json_decode($data['data'], true));
    		if($data['rating'] == '0.00' && $data['order_status_id']){
       			$this->db->query("UPDATE `" . DB_PREFIX . "dqc_statistic` SET `rating` = '" . (int)$query->rows[$key]['data']['rating'] . "' WHERE `statistic_id` = '" . (float)$data['statistic_id'] . "'");
    		}
    		if($data['order_status_id']){
    			$query->rows[$key]['rating'] = $query->rows[$key]['data']['rating'];
    		}	
    	}
    	return $query->rows;
    }

	public function getTotalStatistics($setting_id){
		$query = $this->db->query("SELECT COUNT(*) as total FROM `" . DB_PREFIX . "dqc_statistic` WHERE setting_id = '" . (int)$setting_id . "'");

		return $query->row['total'];
	}

	public function getAnalytics($setting_id, $data = array()){
		$sql = "SELECT *, (s.date_modified - s.date_added) as checkout_time FROM `" . DB_PREFIX . "dqc_statistic` s
    		LEFT JOIN `" . DB_PREFIX . "order` o ON (o.order_id = s.order_id)
    		WHERE setting_id = '" . (int)$setting_id . "' ORDER BY o.order_id DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		foreach($query->rows as $key => $data){
			$query->rows[$key]['data'] = $this->rateStatistic(json_decode($data['data'], true));
			if($data['rating'] == '0.00' && $data['order_status_id']){
				$this->db->query("UPDATE `" . DB_PREFIX . "dqc_statistic` SET `rating` = '" . (int)$query->rows[$key]['data']['rating'] . "' WHERE `statistic_id` = '" . (float)$data['statistic_id'] . "'");
			}
			if($data['order_status_id']){
				$query->rows[$key]['rating'] = $query->rows[$key]['data']['rating'];
			}
		}

		return $query->rows;
	}

	public function getConfigSetting($id, $config_key, $store_id, $config_file = false){

		if($this->getCurrentSettingId($id, $store_id) !== false){
			$setting = $this->getSetting($this->getCurrentSettingId($id, $store_id));
			$setting[$config_key] = $setting['value'];
		}

		if(isset($setting[$config_key]['general']['config'])){
			$this->config->load($setting[$config_key]['general']['config']);
		}elseif($config_file){
			$this->config->load($config_file);
		}

		$result = ($this->config->get($config_key)) ? $this->config->get($config_key) : array();
		$result['general']['default_email'] = $this->config->get('config_email');
		$result['step']['payment_address']['fields']['agree']['information_id'] = $this->config->get('config_account_id');
		$result['step']['payment_address']['fields']['agree']['error'][0]['information_id'] = $this->config->get('config_account_id');
		$result['step']['confirm']['fields']['agree']['information_id'] = $this->config->get('config_checkout_id');
		$result['step']['confirm']['fields']['agree']['error'][0]['information_id'] = $this->config->get('config_checkout_id');
		
		$result['step']['payment_address']['fields'] = $result['step']['payment_address']['fields'] + $this->getCustomFieldsConfigDataStep('account');
		$result['step']['payment_address']['fields'] = $result['step']['payment_address']['fields'] + $this->getCustomFieldsConfigDataStep('address');
		$result['step']['shipping_address']['fields'] = $result['step']['shipping_address']['fields'] + $this->getCustomFieldsConfigDataStep('address');
		
		$result['account']['guest']['payment_address']['fields'] = $result['account']['guest']['payment_address']['fields'] + $this->getCustomFieldsConfigDataAccount('account');
		$result['account']['guest']['payment_address']['fields'] = $result['account']['guest']['payment_address']['fields'] + $this->getCustomFieldsConfigDataAccount('address');
		$result['account']['guest']['shipping_address']['fields'] = $result['account']['guest']['shipping_address']['fields'] + $this->getCustomFieldsConfigDataAccount('address');
		$result['account']['register']['payment_address']['fields'] = $result['account']['register']['payment_address']['fields'] + $this->getCustomFieldsConfigDataAccount('account');
		$result['account']['register']['payment_address']['fields'] = $result['account']['register']['payment_address']['fields'] + $this->getCustomFieldsConfigDataAccount('address');
		$result['account']['register']['shipping_address']['fields'] = $result['account']['register']['shipping_address']['fields'] + $this->getCustomFieldsConfigDataAccount('address');
		$result['account']['logged']['payment_address']['fields'] = $result['account']['logged']['payment_address']['fields'] + $this->getCustomFieldsConfigDataAccount('address');
		$result['account']['logged']['shipping_address']['fields'] = $result['account']['logged']['shipping_address']['fields'] + $this->getCustomFieldsConfigDataAccount('address');

		if(!isset($this->request->post['config'])){
			$this->load->model('setting/setting');
			if (isset($this->request->post[$config_key])) {
				$setting = $this->request->post;
			} elseif ($this->model_setting_setting->getSetting($id, $store_id)) { 
				$setting = $this->model_setting_setting->getSetting($id, $store_id);

			}

			if($this->getCurrentSettingId($id, $store_id) !== false){
				$setting = $this->getSetting($this->getCurrentSettingId($id, $store_id));
				$setting[$config_key] = $setting['value'];

			}
			if(isset($setting[$config_key])){

				$result = $this->array_merge_recursive_distinct($result, $setting[$config_key]);

			}
			
		}

		$result['step']['payment_address']['fields']['country_id']['options'] = $this->getCountries();
        $result['step']['payment_address']['fields']['zone_id']['options'] = $this->getZonesByCountryId((isset($result['step']['payment_address']['fields']['country_id']['value']))?$result['step']['payment_address']['fields']['country_id']['value']:0); 
		$result['step']['shipping_address']['fields']['country_id']['options'] = $this->getCountries();
        $result['step']['shipping_address']['fields']['zone_id']['options'] = $this->getZonesByCountryId((isset($result['step']['payment_address']['fields']['country_id']['value']))?$result['step']['payment_address']['fields']['country_id']['value']:0); 
		      		
		return $result;
	}

	public function getConfigData($id, $config_key, $store_id, $config_file = false){
		if(!$config_file){
			$config_file = $this->config_file;
		}

		if($config_file){
			$this->config->load($config_file);

		}

		$result = ($this->config->get($config_key)) ? $this->config->get($config_key) : array();

		if(!isset($this->request->post['config'])){
			$this->load->model('setting/setting');
			if (isset($this->request->post[$config_key])) {
				$setting = $this->request->post;
			} elseif ($this->model_setting_setting->getSetting($id, $store_id)) { 
				$setting = $this->model_setting_setting->getSetting($id, $store_id);

			}

			if(isset($setting[$config_key])){

				if(is_array($setting[$config_key])){
					$result = $this->array_merge_recursive_distinct($result, $setting[$config_key]);
				}else{
					$result = $setting[$config_key];
				}
			}
		}     		
		return $result;
	}

	public function getCustomFieldsByLocation($location){
		if(VERSION >= '2.1.0.1'){
			$this->load->model('customer/custom_field');
			$results = $this->model_customer_custom_field->getCustomFields();	
		}else{
			$this->load->model('sale/custom_field');
			$results = $this->model_sale_custom_field->getCustomFields();	
		}
		
		foreach($results as $key => $result){
			if($result['location'] != $location){
				unset($results[$key]);
			}
		}
		return $results;
	}

	public function getCustomFieldsConfigDataStep($location){

		$results = $this->getCustomFieldsByLocation($location);

		$custom_fields = array();
		foreach($results as $key => $custom_field){

			$custom_fields['custom_field.'.$location.'.'.$custom_field['custom_field_id']] = array(
				'id' => 'custom_field.'.$location.'.'.$custom_field['custom_field_id'],
				'title' => $custom_field['name'],
				'tooltip' => '',
				'error' => '',
				'type' => $custom_field['type'],
				'options' => '',
				'refresh' => '0',
				'custom' => 1,
				'location' => $custom_field['location'],
				'sort_order' => $custom_field['sort_order'],
				'class' => '',
				//'display' => 1,
				//'require' => $custom_field['required'],

			);

			if(!empty($custom_field['custom_field_value'])){

				foreach($custom_field['custom_field_value'] as $option){

					$custom_fields['custom_field.'.$location.'.'.$custom_field['custom_field_id']]['options'][] = array(
						'name' =>  $option['name'],
						'value' =>  $option['custom_field_value_id'],
					);
				}
			}
		}

		return $custom_fields;
	}


	public function getCustomFieldsConfigDataAccount($location){

		$results = $this->getCustomFieldsByLocation($location);

		$custom_fields = array();
		foreach($results as $key => $custom_field){

			$custom_fields['custom_field.'.$location.'.'.$custom_field['custom_field_id']] = array(
			
				'display' => 1,
				'require' => 1,

			);

		}

		return $custom_fields;
	}

	/* ******************************************************************************************
	*
	*	Return mbooth file.
	*
	*  ******************************************************************************************/ 

	public function getMboothFile($id, $sub_versions){
		$full = DIR_SYSTEM . 'mbooth/xml/mbooth_'. $id .'.xml';
		if (file_exists($full)) {
			return 'mbooth_'. $id . '.xml';
		} else{
			foreach ($sub_versions as $lite){
				if (file_exists(DIR_SYSTEM . 'mbooth/xml/mbooth_'. $id . '_' . $lite . '.xml')) {
					$this->prefix = '_' . $lite;
					return 'mbooth_'. $id . '_' . $lite . '.xml';
				}
			}
		}
		return false;
	}

	/*
	*	Return mbooth file.
	*/
	public function getMboothInfo($mbooth_xml){
		if(file_exists(DIR_SYSTEM . 'mbooth/xml/'. $mbooth_xml)){
			$xml = new SimpleXMLElement(file_get_contents(DIR_SYSTEM . 'mbooth/xml/'. $mbooth_xml));
			return $xml;
		}else{
			return false;
		}
	}

	/*
	*	Return list of stores.
	*/
	public function getStores(){
		$this->load->model('setting/store');
		$stores = $this->model_setting_store->getStores();
		$result = array();
		if($stores){
			$result[] = array(
				'store_id' => 0, 
				'name' => $this->config->get('config_name')
				);
			foreach ($stores as $store) {
				$result[] = array(
					'store_id' => $store['store_id'],
					'name' => $store['name']	
					);
			}	
		}
		return $result;
	}

	/*
	*	Check if another extension/module is installed.
	*/
	public function isInstalled($code) {
		$extension_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `code` = '" . $this->db->escape($code) . "'");
		if($query->row) {
			return true;
		}else{
			return false;
		}	
	}

	/*
	*	Get the version of this module
	*/
	public function getVersion($mbooth_xml){
		if(file_exists(DIR_SYSTEM . 'mbooth/xml/'. $mbooth_xml)){
			$xml = new SimpleXMLElement(file_get_contents(DIR_SYSTEM . 'mbooth/xml/'. $mbooth_xml));
			return (string)$xml->version;
		}else{
			return false;
		}
	}

	

	/*
	*	Get extension info by mbooth from server (Check for update)
	*/
	public function getUpdateInfo($mbooth_xml, $status = 1){
		$result = array();

		$current_version = $this->getVersion($mbooth_xml);
		$customer_url = HTTP_SERVER;
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE language_id = " . (int)$this->config->get('config_language_id') ); 
		$language_code = $query->row['code'];
		$ip = $this->request->server['REMOTE_ADDR'];

		$request = 'http://opencart.dreamvention.com/api/1/index.php?route=extension/check&mbooth=' . $mbooth_xml . '&store_url=' . $customer_url . '&module_version=' . $current_version . '&language_code=' . $language_code . '&opencart_version=' . VERSION . '&ip='.$ip . '&status=' .$status;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $request);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result['data'] = curl_exec($curl);
		$result['code'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		return $result;
	}

	/*
	*	Get the version of this module
	*/

	public function installDependencies($mbooth){
		define('DIR_ROOT', substr_replace(DIR_SYSTEM, '/', -8));
		foreach($this->getDependencies($mbooth) as $extension){
			if(isset($extension['codename'])){
				if(!$this->getVersion('mbooth_'.$extension['codename'].'.xml') || ($extension['version'] > $this->getVersion('mbooth_'.$extension['codename'].'.xml'))){
					$this->download_extension($extension['codename'], $extension['version']);
					$this->extract_extension();
					if(file_exists(DIR_SYSTEM . 'mbooth/xml/'.$mbooth)){
						$result = $this->backup_files_by_mbooth($mbooth, 'update');
					}
					$this->move_dir(DIR_DOWNLOAD . 'upload/', DIR_ROOT, $result);
				}
			}
		}
	}


	public function getDependencies($mbooth_xml){
		if(file_exists(DIR_SYSTEM . 'mbooth/xml/'. $mbooth_xml)){
			$xml = new SimpleXMLElement(file_get_contents(DIR_SYSTEM . 'mbooth/xml/'. $mbooth_xml));
			$result = array();
			$version = false;
			
			foreach($xml->required->require as $require){

				foreach($require->attributes() as $key => $value){
					$version = false;
					if($key == 'version'){
						$version = $value;
					}
				}
				$result[] = array(
					'codename' => (string)$require,
					'version' => (string)$version
				);
			}
			return $result;
		}else{
			return false;
		}
	}

	public function download_extension($codename, $version, $filename  = false ) {

		if(!$filename){
			$filename = DIR_DOWNLOAD . 'archive.zip';
		}

		$userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';  
		$ch = curl_init();  
		$fp = fopen($filename, "w");  
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);  
		curl_setopt($ch, CURLOPT_URL, 'http://opencart.dreamvention.com/api/1/extension/download/?codename=' . $codename.'&opencart_version='.VERSION.'&extension_version='. $version);  
		curl_setopt($ch, CURLOPT_FAILONERROR, true);  
		curl_setopt($ch, CURLOPT_HEADER,0);  
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);  
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);  
		curl_setopt($ch, CURLOPT_TIMEOUT, 100);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);   
		curl_setopt($ch, CURLOPT_FILE, $fp);  
		$page = curl_exec($ch);  
		if (!$page) {  
			exit;  
		}
		curl_close($ch);

	}
    
    public function get_extension_link($codename, $version ) {
		return 'http://opencart.dreamvention.com/api/1/extension/download/?codename=' . $codename.'&opencart_version='.VERSION.'&extension_version='. $version;  	
	}

	

	public function extract_extension($filename = false, $location = false ) {
		if(!$filename){
			$filename = DIR_DOWNLOAD . 'archive.zip';
		}
		if(!$location){
			$location = DIR_DOWNLOAD ;
		}

		$result = array();
		$zip = new ZipArchive;
		if (!$zip) {  
			$result['error'][] = 'ZipArchive not working.'; 
		}

		if($zip->open($filename) != "true") {  
			$result['error'][] = $filename;
		}
		$zip->extractTo($location);  
		$zip->close();

		unlink($filename);

		return $result;

	}

	

	public function get_files_by_mbooth($mbooth_xml) {

		$xml = new SimpleXMLElement(file_get_contents($mbooth_xml));

	  	if(isset($xml->id)){
			$result['file_name'] =   basename($mbooth_xml, '');
			$result['id'] = isset($xml->id) ? (string)$xml->id : '';
			$result['name'] = isset($xml->name) ? (string)$xml->name : '';
			$result['description'] = isset($xml->description) ? (string)$xml->description : '';
			$result['type'] = isset($xml->type) ? (string)$xml->type : '';
			$result['version'] = isset($xml->version) ? (string)$xml->version : '';
			$result['mbooth_version'] = isset($xml->mbooth_version) ? (string)$xml->mbooth_version : '';
			$result['opencart_version'] = isset($xml->opencart_version) ? (string)$xml->opencart_version : '';
			$result['author'] = isset($xml->author) ? (string)$xml->author : '';
			$files = $xml->files;
			$dirs = $xml->dirs;
			$required = $xml->required;
			$updates = $xml->update;

			foreach ($files->file as $file){
			   $result['files'][] = (string)$file; 
			} 
			
			if (!empty($dirs)) {

				$dir_files = array();
			
				foreach ($dirs->dir as $dir) {
					$this->scan_dir(DIR_ROOT . $dir, $dir_files);
				}
				
				foreach ($dir_files as $file) {
					$file = str_replace(DIR_ROOT, "", $file);
					$result['files'][] = (string)$file;
				}
			}
			
			return $result;  
		}else{
			return false;
		}
		
	}

	public function backup_files_by_mbooth($mbooth_xml, $action = 'install'){

		$zip = new ZipArchive();

		if (!file_exists(DIR_SYSTEM . 'mbooth/backup/')) {
		    mkdir(DIR_SYSTEM . 'mbooth/backup/', 0777, true);
		}

		$mbooth = $this->get_files_by_mbooth(DIR_SYSTEM . 'mbooth/xml/' . $mbooth_xml);
		$files = $mbooth['files'];

		$zip->open(DIR_SYSTEM . 'mbooth/backup/' . date('Y-m-d.h-i-s'). '.'. $action .'.'.$mbooth_xml.'.v'.$mbooth['version'].'.zip', ZipArchive::CREATE);

		
		foreach ($files as $file) {
			
			if(file_exists(DIR_ROOT.$file)){

				if (is_file(DIR_ROOT.$file)) {
					$zip->addFile(DIR_ROOT.$file, 'upload/'.$file);
					$result['success'][] = $file;
				}else{
					$result['error'][] = $file;
				}
			}else{
					$result['error'][] = $file;
			}
		}
		$zip->close();
		return $result;	

	}

	public function scan_dir($dir, &$arr_files){
		
        if (is_dir($dir)){
        	$handle = opendir($dir);
	        while ($file = readdir($handle)){
	                if ($file == '.' or $file == '..') continue;
	                if (is_file($file)) $arr_files[]="$dir/$file";
	                else $this->scan_dir("$dir/$file", $arr_files);
	        }
        	closedir($handle);
        }else {
        	$arr_files[]=$dir;
        }
	}

	public function move_dir($souce, $dest, &$result) {
		
		$files = scandir($souce);

		foreach($files as $file){
			
			if($file == '.' || $file == '..' || $file == '.DS_Store') continue;
			
			if(is_dir($souce.$file)){
				if (!file_exists($dest.$file.'/')) {
				    mkdir($dest.$file.'/', 0777, true);
				}
				$this->move_dir($souce.$file.'/', $dest.$file.'/', $result);
			}elseif (rename($souce.$file, $dest.$file)) {
			    $result['success'][] = str_replace(DIR_ROOT, '', $dest.$file);
			}else{
				$result['error'][] = str_replace(DIR_ROOT, '', $dest.$file);
			}
		}

		$this->delete_dir($souce);
	}

	public function delete_dir($dir) {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($dir."/".$object) == "dir") $this->delete_dir($dir."/".$object); 
					else unlink($dir."/".$object);
				}
			}
			reset($objects);
			rmdir($dir);
		}
	}

	public function array_merge_recursive_distinct( array &$array1, array &$array2 )
	{
	  $merged = $array1;	
	  foreach ( $array2 as $key => &$value )
		  {
			if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
			{
			  $merged [$key] = $this->array_merge_recursive_distinct ( $merged [$key], $value );
			}
			else
			{
			  $merged [$key] = $value;
			}
		  }
		
	  return $merged;
	}
}
?>