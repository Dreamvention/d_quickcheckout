<?php
/*
 *  location: admin/model
 */

class ModelExtensionModuleDQuickcheckout extends Model {
    private $handle;
    private $debug;

    /*
    *   debug
    */

    public function logWrite($message){
        if(!empty($this->session->data['d_quickcheckout_debug'])){
            $this->handle = fopen(DIR_LOGS . $this->config->get('d_quickcheckout_debug_file'), 'a');
            fwrite($this->handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . "\n");
            fclose($this->handle);
        }
    }

    /*
    *   Language
    */

    public function languageFilter($data){
        $this->load->model('catalog/information');
        $result = $data;
        $translate = array('title', 'tooltip', 'description', 'text', 'placeholder');

        if(is_array($data)){

            foreach($data as $key => $value){

                if(in_array($key, $translate)){

                    if(!is_array($value)){

                        $result[$key] = $this->escape($this->language->get($value));
                    }elseif(isset($value[(int)$this->config->get('config_language_id')])){
                        $result[$key] = $this->escape($value[(int)$this->config->get('config_language_id')]);
                    }else{
                        $result[$key] = $this->languageFilterRec($value);
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
                    $result[$key] = $this->languageFilterRec($value);
                }

            }
        }
        return $result;
    }

    public function languageFilterRec($data){

        $result = $data;
        $translate = array('title', 'tooltip', 'description', 'text', 'placeholder');

        if(is_array($data)){

            foreach($data as $key => $value){

                if(in_array($key, $translate)){

                    if(!is_array($value)){

                        $result[$key] = $this->escape($this->language->get($value));
                    }elseif(isset($value[(int)$this->config->get('config_language_id')])){
                        $result[$key] = $this->escape($value[(int)$this->config->get('config_language_id')]);
                    }else{
                        $result[$key] = $this->languageFilterRec($value);
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
                    $result[$key] = $this->languageFilterRec($value);
                }

            }
        }
        return $result;
    }

    public function escape($data){
        return $data;
    }

    public function in_array_multi($needle, $haystack, $strict = true) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_multi($needle, $item, $strict))) {
                return true;
            }
        }

        return false;
    }

    // public function array_merge_r_d( array &$array1, array &$array2 ){
 //      $merged = $array1;
 //      foreach ( $array2 as $key => &$value )
 //          {
 //            if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
 //            {
 //              $merged [$key] = $this->array_merge_r_d ( $merged [$key], $value );
 //            }
 //            else
 //            {
 //              $merged [$key] = $value;
 //            }
 //          }

 //      return $merged;
 //    }

public function array_merge_r_d() {
    $arrays = func_get_args();
    $base = array_shift($arrays);
    if(!is_array($base)) $base = empty($base) ? array() : array($base);
    foreach($arrays as $append) {
        if(!is_array($append)) $append = array($append);
        foreach($append as $key => $value) {
            if(!array_key_exists($key, $base) and !is_numeric($key)) {
                $base[$key] = $append[$key];
                continue;
            }
            if(is_array($value) or is_array($base[$key])) {
                $base[$key] = $this->array_merge_r_d($base[$key], $append[$key]);
            } else if(is_numeric($key)) {
                if(!in_array($value, $base)) $base[] = $value;
            } else {
                $base[$key] = $value;
            }
        }
    }
    return $base;
 }

    /*
    *   Vqmod: turn on or off
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
    *   Format the link to work with ajax requests
    */
    public function ajax($link){
        return str_replace('&amp;', '&', $link);
    }

    /*
    *   Get file contents, usualy for debug log files.
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
    *   Return name of config file.
    */
    public function getConfigFile($id, $sub_versions){

        if(isset($this->request->post['config'])){
            return $this->request->post['config'];
        }

        $setting = $this->config->get($id.'_setting');

        if(isset($setting['config'])){
            return $setting['config'];
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
    *   Return list of config files that contain the id of the module.
    */
    public function getConfigFiles($id){
        $files = array();
        $results = glob(DIR_SYSTEM . 'config/'. $id .'*');
        foreach($results as $result){
            $files[] = str_replace('.php', '', str_replace(DIR_SYSTEM . 'config/', '', $result));
        }
        return $files;
    }

    public function getConfigSetting($id, $config_key, $store_id, $config_file = false, $customer_group_id = 0){

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

        $result['step']['payment_address']['fields'] = $result['step']['payment_address']['fields'] + $this->model_extension_d_quickcheckout_custom_field->getCustomFieldsConfigData('account');
        $result['step']['payment_address']['fields'] = $result['step']['payment_address']['fields'] + $this->model_extension_d_quickcheckout_custom_field->getCustomFieldsConfigData('address');
        $result['step']['shipping_address']['fields'] = $result['step']['shipping_address']['fields'] + $this->model_extension_d_quickcheckout_custom_field->getCustomFieldsConfigData('address');

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

                $result = $this->array_merge_recursive_distinct($result,$setting[$config_key]);

            }

        }


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

    /*
    *   Get config file values and merge with config database values
    */

    public function getCurrentSettingId($id, $store_id = 0){

        if(isset($this->request->get['setting_id'])){
            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "dqc_setting`
            WHERE setting_id = '". (int)$this->request->get['setting_id'] ."' AND store_id = '" . (int)$store_id . "'" );
            if($query->row){
                return $query->row['setting_id'];
            }
        }

        $this->load->model('setting/setting');
        $setting = $this->model_setting_setting->getSetting($id, $store_id);

        $setting_id = '';

        if(isset($setting[$id.'_setting_cycle'])){
            $probabilities = $setting[$id.'_setting_cycle'];

            if(isset($this->session->data['current_setting_id']) &&
                isset($probabilities[$this->session->data['current_setting_id']])
                && $probabilities[$this->session->data['current_setting_id']]){
                $setting_id = $this->session->data['current_setting_id'];
            }else{

                $random = array();
                foreach($probabilities as $key => $value) {
                    for($i = 0; $i < $value; $i++) {
                        $random[] = $key;
                    }

                    if($i > 100){
                        break;
                    }
                }

                shuffle($random);

                $setting_id = current($random);

                if($setting_id){
                    $this->session->data['current_setting_id'] = $setting_id;

                }
            }

        }
        if(!$setting_id){
            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "dqc_setting`
            WHERE store_id = '" . (int)$store_id . "'" );
            if($query->row){
                $setting_id = $query->row['setting_id'];
            }
        }
        return $setting_id;
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
        }else{
            $result = false;
        }

        return $result;

    }
    /*
    statistic = array(
        'account' => 'guest',

        'click' => array(
            'account' => 1,
            'login' => 0,
            'confirm' => 1
        ),

        'update' => array(
            'payment_address' => array(
                ...

            ),
        ),

        'error' => array(
            'payment_address' => array(
                ...
            ),
        ),

        'total' => array(
            'click' => 2,
            'update' => 45,
            'error' => 12,
        ),

        'status' => 0,

    );

    */

    // public function setStatisticActivity($action, $activity, $data){

    //  if(isset($data[$action])){
    //      if(is_array($activity)){
    //          foreach($activity as $key => $value){
    //              if(isset($data[$action][$key])){
    //                  if(is_array($data[$action][$key])){
    //                      foreach($data[$action][$key] as $kkey => $vvalue){
    //                          if(isset($data[$action][$key][$kkey])) {

    //                              $data[$action][$key][$kkey] = $data[$action][$key][$kkey] + $vvalue;
    //                          }else{
    //                              $data[$action][$key][$kkey] = $vvalue;
    //                          }
    //                      }


    //                  }else{
    //                      if(isset($data[$action][$key])){
    //                          $data[$action][$key] = $data[$action][$key] + $value;
    //                      }else{
    //                          $data[$action][$key] = $value;
    //                      }
    //                  }
    //              }
    //          }
    //      }else{
    //          $data[$action] = $activity;
    //      }

    //  }else{
    //      $data[$action] = $activity;
    //  }

    //  return  $data;
    // }

    public function setStatisticActivity($data, $activity){
        foreach($activity as $key => $value){
            if(is_array($value)){
                if(!isset($data[$key])){
                    $data[$key] = array();
                }
                $data[$key] = $this->setStatisticActivity($value, $data[$key]);
            }else{
                if(!isset($data[$key])){
                    $data[$key] = '';
                }
                if(is_int($value)){
                    $data[$key] = (int)$data[$key] + (int)$value;
                }else{
                    $data[$key] = $value;
                }

            }

        }
        return $data;
    }

    public function setStatistic($setting_id, $order_id, $data){
        $this->session->data['statistic'] = array();
        $this->session->data['statistic'] = $this->setStatisticActivity($this->session->data['statistic'], $data);

        $this->db->query("INSERT INTO " . DB_PREFIX . "dqc_statistic
            SET setting_id = '" . (int)$setting_id . "',
                order_id = '" . (int)$order_id . "',
                customer_id = '" . (int)$this->customer->getId() . "',
                data = '" . $this->db->escape(json_encode($this->session->data['statistic'])) . "',
                rating = 0,
                date_added = NOW(),
                date_modified = NOW()");

        $this->session->data['statistic_id'] = $this->db->getLastId();
        return $this->session->data['statistic_id'];
    }

    public function updateStatistic($data){
        $this->session->data['statistic'] = $this->setStatisticActivity($this->session->data['statistic'], $data);
        $statistic_id = $this->session->data['statistic_id'];
        $this->db->query("UPDATE " . DB_PREFIX . "dqc_statistic
            SET customer_id = '" . (int)$this->customer->getId(). "',
                data = '" . $this->db->escape(json_encode($this->session->data['statistic'])) . "',
                date_modified = NOW()
            WHERE statistic_id = '" . (int)$statistic_id . "'");
    }



    /*
    *   Return mbooth file.
    */
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
    *   Return mbooth file.
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
    *   Return list of stores.
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
    *   Check if another extension/module is installed.
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
    *   Get the version of this module
    */
    public function getVersion($mbooth_xml){
        if(file_exists(DIR_SYSTEM . 'mbooth/xml/'. $mbooth_xml)){
            $xml = new SimpleXMLElement(file_get_contents(DIR_SYSTEM . 'mbooth/xml/'. $mbooth_xml));
            return $xml->version;
        }else{
            return false;
        }
    }

    /*
    *   Get extension info by mbooth from server (Check for update)
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

    public function download_extension($mbooth_xml, $filename  = false ) {

        if(!$filename){
            $filename = DIR_SYSTEM . 'mbooth/download/archive.zip';
        }

        $userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';
        $ch = curl_init();
        $fp = fopen($filename, "w");
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_URL, 'http://opencart.dreamvention.com/api/1/extension/download/?mbooth=' . $mbooth_xml.'&opencart_version='.VERSION);
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

    public function extract_extension($filename = false, $location = false ) {
        if(!$filename){
            $filename = DIR_SYSTEM . 'mbooth/download/archive.zip';
        }
        if(!$location){
            $location = DIR_SYSTEM . 'mbooth/download/';
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