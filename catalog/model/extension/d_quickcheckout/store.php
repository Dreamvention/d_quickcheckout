<?php
/*

 ________  _______   ________  ________ _________  ________  ________     
|\   __  \|\  ___ \ |\   __  \|\   ____\\___   ___\\   __  \|\   __  \    
\ \  \|\  \ \   __/|\ \  \|\  \ \  \___\|___ \  \_\ \  \|\  \ \  \|\  \   
 \ \   _  _\ \  \_|/_\ \   __  \ \  \       \ \  \ \ \  \\\  \ \   _  _\  
  \ \  \\  \\ \  \_|\ \ \  \ \  \ \  \____   \ \  \ \ \  \\\  \ \  \\  \| 
   \ \__\\ _\\ \_______\ \__\ \__\ \_______\  \ \__\ \ \_______\ \__\\ _\ 
    \|__|\|__|\|_______|\|__|\|__|\|_______|   \|__|  \|_______|\|__|\|__|
                                                                          
    #The Store Model Class aka `Reactor` 
    It is the heart of Ajax Quick Checkout Event Cycle. 
    Every event triggers a loop that dispatches to all registered receivers an array with 
    Action and Data. 

    Action - is the event name.
    Data - set of new values that will be evaluated by every reciever and then modify the state.

    When all event dispatches have been fullfiled, the new state is returned back to the user.

    Every registrated reciever is responsible for his state only and can not mofidy other state values.
    The State consists of the following sets:

    Session - the current php session.
    Config - the configurations of Ajax Quick Checkout.
    Layout - the layout of the Ajax Quick Checkout page.
    Errors - errors that should be displayed on the page.
    Steps - registrated recievers of modules. 
    Action - list of actions that will run when calling dispatch.

    #Extensibility. 
    To extend the functionality of Ajax Quick Checkout you can simply add a controller to the d_quickcheckout
    folder with the following functions:

    index() - this method is loaded on the first load
    update() - this method is used to update and modify the state
    receiver($data) - this method is part of the event cycle which listens to all dispatched events and mofiys the state.

*/
class ModelExtensionDQuickcheckoutStore extends Model {
    private $receivers = array();

    public function dispatch($action, $data) { 

        $state = $this->getState();

        foreach($state['action'] as $receiver => $actions){
            if(in_array($action, $actions)){
                $this->load->controller('extension/d_quickcheckout/'.$receiver.'/receiver', array( 'action' => $action, 'data' => $data));
            }
                
        }
    }

    public function initState(){
        $this->cache->delete('d_quickcheckout_config');
        $this->cache->delete('d_quickcheckout_layout');
        $this->cache->delete('d_quickcheckout_errors');
        $this->cache->delete('d_quickcheckout_steps');
        $this->cache->delete('d_quickcheckout_action');
        $this->cache->delete('d_quickcheckout_language');

        if(isset($this->request->get['setting_id'])){
            $this->session->data['setting_id'] =  $this->request->get['setting_id'];
        }else{
            $settings = $this->getSettings();
            if(!empty($settings)){
                $this->session->data['setting_id'] = $settings[0]['setting_id']; 
            }
        }

        $state = array();
        $state['layout'] = array();
        $state['steps'] = $this->getReceivers();
        $state['layouts'] = $this->getLayouts();
        $state['skins'] = $this->getSkins();
        $state['errors'] = array();
        $state['config'] = array();
        $state['action'] = array();
        $state['language'] = array();
        $state['notifications'] = array();
        $state['session'] = $this->session->data;

        $settings = $this->getSetting();

        //load layout
        if(!empty($settings['layout'])){
            $state['layout'] = $settings['layout'];
            $this->config->set('d_quickcheckout_layout', $settings['layout']);
        }else{
            $this->load->config('d_quickcheckout_layout/default');
            $state['layout'] = $this->config->get('d_quickcheckout_layout');
        }

        //move to controller. 
        $state['layout'] = $this->validateLayout($state['layout']);

        //load config
        if(!empty($settings['config'])){
            $state['config'] = $settings['config'];
        }

        $state['session']['status'] = true;

        if(isset($state['session']['setting_id'])){
            $setting = $this->getSettingById($state['session']['setting_id']);
        }else{
            $settings = $this->getSettings();
            if(!empty($settings)){
                $setting = $settings[0];
                $state['session']['setting_id'] = $setting['setting_id'];
            }
        } 
        if(isset($setting['name'])){
            $state['session']['setting_name'] = $setting['name'];
        }
        

        $this->setState($state);
        return $state;
    }

    public function loadState(){
        $this->config->set('d_quickcheckout_config', $this->cache->get('d_quickcheckout_config'));
        $this->config->set('d_quickcheckout_layout', $this->cache->get('d_quickcheckout_layout'));
        $this->config->set('d_quickcheckout_errors', $this->cache->get('d_quickcheckout_errors'));
        $this->config->set('d_quickcheckout_steps', $this->cache->get('d_quickcheckout_steps'));
        $this->config->set('d_quickcheckout_action', $this->cache->get('d_quickcheckout_action'));
        $this->config->set('d_quickcheckout_language', $this->cache->get('d_quickcheckout_language'));
        $this->config->set('d_quickcheckout_notifications', array());
    }

    public function setState($data, $save = false){
        $current_state = $this->getState();

        $this->setUpdated($this->array_diff_assoc_recursive( $data, $current_state));

        $state_update = $this->config->get('d_quickcheckout_state_update');
        if(empty($state_update)){
            $state_update = array();
        }
        $state_update = array_replace_recursive($state_update, $data);
        $this->config->set('d_quickcheckout_state_update', $state_update);

        $state = array();

        if(!empty($data['session'])){
            $this->session->data = array_replace_recursive($this->session->data, $data['session']);
            $state['session'] = $this->session->data;
        }

        unset($data['session']);

        if(!empty($data['layout'])){
            $state['layout'] = $data['layout'];
            $this->config->set('d_quickcheckout_layout', $state['layout']);
        }

        unset($data['layout']);

        if(!empty($data)){
            foreach($data as $key => $state_new){

                $state_old = $this->config->get('d_quickcheckout_'.$key);
                if(is_array($state_old) && is_array($state_new)){
                    $state[$key] = array_replace_recursive($state_old, $state_new);
                }else{
                    $state[$key] = $state_new;
                }
                $this->config->set('d_quickcheckout_'.$key, $state[$key]);

                if($save){
                    $this->cache->set('d_quickcheckout_'.$key, $state[$key]);
                }
            }
        }
    }

    public function array_diff_assoc_recursive($array1, $array2)
    {
        foreach($array1 as $key => $value)
        {
            if(is_array($value))
            {
                if(!isset($array2[$key]))
                {
                    $difference[$key] = $value;
                }
                elseif(!is_array($array2[$key]))
                {
                    $difference[$key] = $value;
                }
                else
                {
                    $new_diff = $this->array_diff_assoc_recursive($value, $array2[$key]);
                    if($new_diff != FALSE)
                    {
                        $difference[$key] = $new_diff;
                    }
                }
            }
            elseif(!isset($array2[$key]) || $array2[$key] != $value)
            {
                $difference[$key] = $value;
            }
        }
        return !isset($difference) ? 0 : $difference;
    }

    public function makeArray($keys, $value) {
        $var = array();   
        $index = array_shift($keys);
        if (!isset($keys[0])) {
            $var[$index] = $value;
        } else {
            $var[$index] = $this->makeArray($keys,$value); 
        }
        return $var;
    }

    public function updateState($keys, $value) {
        $state = $this->getState();

        if(is_array($keys) && $keys[0] == 'config' && isset($keys[1]) && $keys[1] != 'guest' && $keys[1] != 'register' && $keys[1] != 'logged'){
            array_splice( $keys, 1, 0, array($state['session']['account']) );
        }

        $current = &$state;
        foreach($keys as $key) {
            if(!isset($current[$key])){
                $current[$key] = array();
            }
            $current = &$current[$key];
        }

        $update_state = $this->makeArray($keys, $value);
        $update = $this->array_diff_assoc_recursive( $update_state, $state);
        $this->setUpdated($update);

        if(is_array($current)&& is_array($value) && count($current) > count($value)){
            foreach($current as $key => $val){
                if(isset($value[$key])){
                    $current[$key] = $value[$key];
                }else{
                    $current[$key] = false;
                }
            }
        }else{
            $current = $value;
        }



        $new_value = $current;

        unset($current);

        if($keys[0] == 'session'){
            $this->session->data = $state['session'];
        }else{
            $this->config->set('d_quickcheckout_'.$keys[0], $state[$keys[0]]);
        }

        //add to updated
        $state_update = $this->config->get('d_quickcheckout_state_update');
        if(empty($state_update)){
            $state_update = array();
        }
        $current = &$state_update;
        foreach($keys as $key) {
            if(!isset($current[$key])){
                $current[$key] = array();
            }
            $current = &$current[$key];
        }
        $current = $new_value;
        unset($current);

        $this->config->set('d_quickcheckout_state_update', $state_update);
    }

    public function saveState(){
        $this->cache->set('d_quickcheckout_config',  $this->config->get('d_quickcheckout_config'));
        $this->cache->set('d_quickcheckout_layout',  $this->config->get('d_quickcheckout_layout'));
        $this->cache->set('d_quickcheckout_errors',  $this->config->get('d_quickcheckout_errors'));
        $this->cache->set('d_quickcheckout_steps',  $this->config->get('d_quickcheckout_steps'));
        $this->cache->set('d_quickcheckout_action',  $this->config->get('d_quickcheckout_action'));
        $this->cache->set('d_quickcheckout_language',  $this->config->get('d_quickcheckout_language'));
    }

    public function getState($keys = ''){

        $state = array();
        if(!empty($keys)){
            if(is_array($keys)){
                foreach($keys as $key){
                    if($key == 'session'){
                        $state['session'] = $this->session->data;
                    }else{
                        $state[$key] = ($this->config->get('d_quickcheckout_'.$key)) ? $this->config->get('d_quickcheckout_'.$key) : array();
                    }
                }
            }else{

                if($keys == 'session'){
                    $state[$keys] = $this->session->data;
                }else{
                    $state[$keys] = ($this->config->get('d_quickcheckout_'.$keys)) ? $this->config->get('d_quickcheckout_'.$keys) : array();
                }
            }
        }else{
            $state['session'] = $this->session->data;
            $state['layouts'] = ($this->config->get('d_quickcheckout_layouts')) ? $this->config->get('d_quickcheckout_layouts') : array();
            $state['skins'] = ($this->config->get('d_quickcheckout_skins')) ? $this->config->get('d_quickcheckout_skins') : array();
            $state['notifications'] = ($this->config->get('d_quickcheckout_notifications')) ? $this->config->get('d_quickcheckout_notifications') : array();
            $state['config'] = ($this->config->get('d_quickcheckout_config')) ? $this->config->get('d_quickcheckout_config') : array();
            $state['layout'] =($this->config->get('d_quickcheckout_layout')) ? $this->config->get('d_quickcheckout_layout') : array();
            $state['errors'] = ($this->config->get('d_quickcheckout_errors')) ? $this->config->get('d_quickcheckout_errors') : array();
            $state['steps'] = ($this->config->get('d_quickcheckout_steps')) ? $this->config->get('d_quickcheckout_steps') : array();
            $state['action'] = ($this->config->get('d_quickcheckout_action')) ? $this->config->get('d_quickcheckout_action') : array();
            $state['language'] = ($this->config->get('d_quickcheckout_language')) ? $this->config->get('d_quickcheckout_language') : array();
        }

        return $state;
    }

    public function clearState(){
        $this->cache->delete('d_quickcheckout_config');
        $this->cache->delete('d_quickcheckout_layout');
        $this->cache->delete('d_quickcheckout_errors');
        $this->cache->delete('d_quickcheckout_steps');
        $this->cache->delete('d_quickcheckout_action');
        $this->cache->delete('d_quickcheckout_language');

        $this->config->set('d_quickcheckout_config', array());
        $this->config->set('d_quickcheckout_layout', array());
        $this->config->set('d_quickcheckout_errors', array());
        $this->config->set('d_quickcheckout_steps', array());
        $this->config->set('d_quickcheckout_action', array());
        $this->config->set('d_quickcheckout_language', array());
        $this->config->set('d_quickcheckout_notifications', array());
        

        $store_id = $this->config->get('config_store_id');
        $code = 'd_quickcheckout'; 
        $this->clearSetting();
    }

    public function getStateUpdated(){
        $this->load->model('extension/d_quickcheckout/order');
        $state['session']['order_id'] = $this->model_extension_d_quickcheckout_order->updateOrder();
        $this->setState($state);

        $state = $this->config->get('d_quickcheckout_state_update');

        if(!isset($state['notifications'])){
            $state['notifications'] = "";
        }
        $this->saveState();
        return $state;
    }

    public function isUpdated($key){
        $updated = $this->config->get('d_quickcheckout_updated');

        return (isset($updated[$key]));
    }


    public function setUpdated($data){
        $state = $this->getState();
        $updated = $this->config->get('d_quickcheckout_updated');

        if(isset($data['session']) && is_array($data['session'])){
            foreach($data['session'] as $step_name => $step){
                if(isset($state['session'][$step_name]) && $state['session'][$step_name] != $step){
                    $updated[$step_name] = $step;

                    if(is_array($step)){
                        foreach ($step as $field => $value) {
                            if(is_array($value)){

                            }else{

                                if(isset($state['session'][$step_name][$field]) && $state['session'][$step_name][$field] != $value){
                                    $updated[$step_name.'_'.$field] = $value;
                                }
                            }
                        }
                    }
                }
            }
        }

        $this->config->set('d_quickcheckout_updated', $updated);

    }

    public function getReceivers(){

        $dir = DIR_APPLICATION.'controller/extension/d_quickcheckout';
        $files = scandir($dir);
        $receivers = array();
        foreach($files as $file){
            if(strlen($file) > 1 && strpos( $file, '.php')){
                $receivers[] = substr($file, 0, -4);
            }
        }

        return $receivers;
    }

    public function getLayouts(){
        $dir = DIR_CONFIG.'d_quickcheckout_layout';
        $files = scandir($dir);
        $layouts = array();
        foreach($files as $file){
            if(strlen($file) > 1 && strpos( $file, '.php')){
                $layouts[] = substr($file, 0, -4);
            }
        }

        return $layouts;
    }

    public function getSkins(){
        $dir = DIR_APPLICATION.'view/theme/default/stylesheet/d_quickcheckout/skin';
        $files = scandir($dir);
        $layouts = array();
        foreach($files as $file){
            if(strlen($file) > 1 && strpos( $file, '.') === false){
                $layouts[] = $file;
            }
        }

        return $layouts;
    }

    public function getSetting() {
        if(isset($this->session->data['setting_id'])){
            $setting_id = $this->session->data['setting_id'];
        }else{
            $settings = $this->getSettings();
            return $settings[0];
        }
        

        return $this->getSettingData($setting_id);
    }

    

    public function editSetting($data) {
        $setting_id = $this->session->data['setting_id'];
        $this->editSettingData($setting_id, $data);
    }

    public function validateLayout($data){
        if(!empty($data['pages'])){
            foreach($data['pages'] as $page_id => $page){
                if(!isset($page['deleted'] ) || $page['deleted'] == 1 ){
                    unset($data['pages'][$page_id]);
                }elseif(!empty($page['children'])){
                    foreach($page['children'] as $row_id => $row){
                        $new_row = $this->validateRow($row);
                        if($new_row){
                            $data['pages'][$page_id]['children'][$row_id] = $new_row;
                        }else{
                            unset($data['pages'][$page_id]['children'][$row_id]);
                        }
                    }
                }else{
                    unset($data['pages'][$page_id]);
                }
            }
        }
        return $data;
    }

    private function validateRow($row){
        if($row['children']){
            foreach($row['children'] as $col_id => $col){
                if(isset($col['children'])){
                    foreach($col['children'] as $item_id => $item){
                        if(isset($item['type'])){
                            if($item['type'] == 'item'){
                                if(!isset($item['name'])){
                                    unset($row['children'][$col_id]['children'][$item_id]);
                                }
                            }elseif($item['type'] == 'row'){
                                $row2 = $this->validateRow($item);
                                if($row2){
                                    $row['children'][$col_id]['children'][$item_id] = $row2;
                                }else{
                                    unset($row['children'][$col_id]['children'][$item_id]);
                                }
                                
                            }else{
                                unset($row['children'][$col_id]['children'][$item_id]);
                            }
                        }else{
                            unset($row['children'][$col_id]['children'][$item_id]);
                        }
                    }
                }else{
                    unset($row['children'][$col_id]);
                }
            }
        }
        if(empty($row['children'])){
            return false;
        }
        return $row;
    }

    public function getSettings(){
        $store_id = $this->config->get('config_store_id');
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "dqc_setting WHERE store_id = '" . (int)$store_id . "'");
        return $query->rows;
        
    }

    public function getSettingById($setting_id){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "dqc_setting WHERE setting_id = '" . (int)$setting_id . "'");
        return $query->row;
        
    }

    public function getSettingData($setting_id){
        $setting_data = array();
        $store_id = $this->config->get('config_store_id');
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "dqc_setting_data WHERE setting_id = '" . (int)$setting_id . "'");
        foreach ($query->rows as $result) {
            $setting_data[$result['key']] = json_decode($result['value'], true);
        }

        $result = array();
        if(isset($setting_data['layout'])){
            $result['layout'] = $setting_data['layout'];
        }

        if(isset($setting_data['language_'.$this->config->get('config_language_id')])){
            $result['language'] = $setting_data['language_'.$this->config->get('config_language_id')];
        }

        foreach($setting_data as $key => $value){
            if(strpos($key, 'config_') === 0){
                $result['config'][str_replace('config_', '', $key)] = $value;
            }
        }
        return $result;
        
    }

     public function editSettingData($setting_id, $data) {
        $code = 'd_quickcheckout'; 
        $store_id = $this->config->get('config_store_id');

        if(isset($data['config'])){
            foreach($data['config'] as $key => $value){
                $data['config_'.$key] = $value;
            }
            unset($data['config']);
        }

        $this->db->query("DELETE FROM `" . DB_PREFIX . "dqc_setting_data` WHERE setting_id = '" . (int)$setting_id . "'");

        foreach ($data as $key => $value) {
            if($key == 'language'){
                $key .= '_'.$this->config->get('config_language_id');
            }
            if (!is_array($value)) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "dqc_setting_data SET setting_id = '" . (int)$setting_id . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
            } else {
                $value = json_encode($value, true);
                
                $this->db->query("INSERT INTO " . DB_PREFIX . "dqc_setting_data SET setting_id = '" . (int)$setting_id . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
            }
        }
        $this->saveState();
    }

    public function changeLayout($codename){
        $setting_id = $this->session->data['setting_id'];
        $this->cache->delete('d_quickcheckout_layout');
        $this->config->set('d_quickcheckout_layout', array());

        $query = $this->db->query("DELETE FROM " . DB_PREFIX . "dqc_setting_data WHERE setting_id = '" . (int)$setting_id . "' AND `key` = 'layout'");


        $this->load->config('d_quickcheckout_layout/'.$codename);
        $value = $this->config->get('d_quickcheckout_layout');

        $value = json_encode($value, true);

        $this->db->query("INSERT INTO " . DB_PREFIX . "dqc_setting_data SET setting_id = '" . (int)$setting_id . "', `key` = '" . $this->db->escape('layout') . "', `value` = '" . $this->db->escape($value) . "'");
    }

    public function getLanguage(){
        $setting_id = $this->session->data['setting_id'];
        $setting_data = array();
        $code = 'language_'.$this->config->get('config_language_id'); 
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "dqc_setting_data WHERE setting_id = '" . (int)$setting_id . "' AND `key` = '" . $this->db->escape($code) . "'");

        $result = $query->row;
        if($result){
            $setting_data = json_decode($result['value'], true);
        }
        return $setting_data;
    }

    public function clearSetting(){
        $setting_id = $this->session->data['setting_id'];
        $query = $this->db->query("DELETE FROM " . DB_PREFIX . "dqc_setting_data WHERE setting_id = '" . (int)$setting_id . "'");
    }

}