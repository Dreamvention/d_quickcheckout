<?php
/*
 *  location: admin/model
 */

class ModelExtensionModuleDQuickcheckout extends Model {
    
    public function update(){
        $extension = json_decode(file_get_contents(DIR_SYSTEM.'library/d_shopunity/extension/d_quickcheckout.json'), true);
        $currentVer = $extension['version'];
        $prevVer = $this->config->get('d_quickcheckout_version');
        if($currentVer > $prevVer){
            $this->updateVersion($prevVer);

            $this->load->model('setting/setting');
            $this->model_setting_setting->editSettingValue('d_quickcheckout', 'd_quickcheckout_version', $currentVer);
        }
    }

    public function updateVersion($version){

        switch (true){
            // case ($version < '7.0.1'):
            // {
            //     echo '7.0.1';
            // } 

            // case ($version < '7.0.2'):
            // {
            //     echo '7.0.2';
            // }
        }
    }

    public function installDatabase(){
        //install oc_dqc_setting
        $query = $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."dqc_setting` (
          `setting_id` int(11) NOT NULL AUTO_INCREMENT,
          `store_id` int(11) NOT NULL,
          `name` varchar(32) NOT NULL,
          `date_added` datetime NOT NULL,
          `date_modified` datetime NOT NULL,
          PRIMARY KEY (`setting_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

        $query = $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."dqc_setting_data` (
          `setting_id` int(11) NOT NULL AUTO_INCREMENT,
          `key` varchar(32) NOT NULL,
          `value` longtext NOT NULL,
          PRIMARY KEY (`setting_id`, `key`)
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
        $query = $this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."dqc_setting_data`");
        $query = $this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."dqc_statistic`");
    }

    public function setSetting($name, $setting, $store_id = 0){

        $this->db->query("INSERT INTO `" . DB_PREFIX . "dqc_setting`
            SET `store_id` = '" . (int)$store_id . "',
                `name` = '" . $this->db->escape($name) . "',
                `layout` = '" . $this->db->escape(json_encode($setting['layout'])) . "',
                `steps` = '" . $this->db->escape(json_encode($setting['steps'])) . "',
                `action` = '" . $this->db->escape(json_encode($setting['action'])) . "',
                `config` = '" . $this->db->escape(json_encode($setting['config'])) . "',
                `date_added` = NOW(),
                `date_modified` = NOW()");

        $setting_id = $this->db->getLastId();
        if($setting_id){
            $this->db->query("INSERT INTO `" . DB_PREFIX . "dqc_setting_language`
            SET `setting_id` = '" . (int)$setting_id . "',
                `language_id` = '" . (int)$this->config->get('config_language_id')  . "',
                `value` = '" . $this->db->escape(json_encode($setting['language'])) . "'");
        }
        return $setting_id;
    }

    public function getCurrentSettingId($id, $store_id = 0){
        $this->load->model('setting/setting');
        $setting = $this->model_setting_setting->getSetting($id, $store_id);

        if(isset($this->request->get['setting_id'])){
            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "dqc_setting`
                WHERE store_id = '" . (int)$store_id . "'
                AND setting_id = '" . (int)$this->request->get['setting_id'] . "'" );
                if($query->row){
                    return $query->row['setting_id'];
                }
        }

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "dqc_setting`
            WHERE store_id = '" . (int)$store_id . "'" );
        if($query->row){
            return $query->row['setting_id'];
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

    public function getSettings(){
        $store_id = $this->config->get('config_store_id');
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "dqc_setting WHERE store_id = '" . (int)$store_id . "'");
        return $query->rows;
        
    }

    // public function getSettings($store_id, $rating = false){
    //     $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "dqc_setting`
    //         WHERE store_id = '" . (int)$store_id . "'"  );

    //     $results = $query->rows;

    //     foreach ($results as $key => $result) {
    //         $results[$key]['value'] = json_decode($result['value'], true);
    //         if($rating){
    //             $query = $this->db->query("SELECT AVG((date_modified - date_added)) as average_checkout_time, AVG(rating) as average_rating FROM " . DB_PREFIX . "dqc_statistic WHERE setting_id = '" . (int)$result['setting_id'] . "' LIMIT 50");
    //             $results[$key]['average_checkout_time'] = $query->row['average_checkout_time'];
    //             $results[$key]['average_rating'] = $query->row['average_rating'];
    //         }

    //     }

    //     return $results;
    // }

    

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
}
