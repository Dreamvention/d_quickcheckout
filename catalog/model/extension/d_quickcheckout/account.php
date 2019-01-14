<?php
class ModelExtensionDQuickcheckoutAccount extends Model {
    public function getDefaultAccount($default_account = 'guest'){

      if($this->customer->isLogged()){
        $this->session->data['account'] = 'logged';
        return $this->session->data['account'];
      }

      if (isset($this->session->data['account'])) {
  			$account = $this->session->data['account'];
  		} else {
        $account = $default_account;
      }

      if($account == 'logged'){
        $account = $default_account;
      }

      if($account == 'guest' && !$this->isGuestAllowed()){
        $account = 'register';
      }

      $this->session->data['account'] = $account;

      return $account;
    }

    public function setAccount($account){
      if($account == 'guest' && !$this->isGuestAllowed()){
        $account = 'register';
      }

      $this->session->data['account'] = $account;

      return $account;
    }

    public function isGuestAllowed(){
      return ($this->config->get('config_checkout_guest')
      && !$this->config->get('config_customer_price')
      && !$this->cart->hasDownload());
    }

    public function updateGuest(){
      if(!empty($this->session->data['payment_address'])){
        $keys = array('customer_group_id',
            'firstname',
            'lastname',
            'email',
            'telephone',
            'fax',
            'custom_field',
            'shipping_address');

        foreach($keys as $key){
            if(array_key_exists($key, $this->session->data['payment_address'])){
                 $this->session->data['guest'][$key] = $this->session->data['payment_address'][$key];
            }
        }

        $this->session->data['guest']['customer_group_id'] = $this->getDefaultCustomerGroup();
      }
    }

//SHOULD THIS BE IN ERROR MODEL????
   //  public function login($email, $password){
   //    $this->load->model('account/customer');

			// // Check how many login attempts have been made.
			// $login_info = $this->model_account_customer->getLoginAttempts($email);

			// if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
			// 	$this->session->data['errors']['login']['error'] = $this->language->get('error_attempts');
   //      return false;
			// }

			// // Check if customer has been approved.
			// $customer_info = $this->model_account_customer->getCustomerByEmail($email);

			// if ($customer_info && !$customer_info['approved']) {
			// 	$this->session->data['errors']['login']['error'] =  $this->language->get('error_approved');
   //      return false;
			// }

			// if (!$this->customer->login($email, $password)) {
			// 	$this->session->data['errors']['login']['error'] =  $this->language->get('error_login');

			// 	$this->model_account_customer->addLoginAttempt($email);
   //      return false;
			// } else {
   //      unset($this->session->data['errors']['login']['error']);
			// 	$this->model_account_customer->deleteLoginAttempts($email);
			// }

   //    return true;
   //  }

    public function getCustomerGroups() {
        $result = array();
        if (is_array($this->config->get('config_customer_group_display'))) {

            $this->load->model('account/customer_group');
            $customer_groups = $this->model_account_customer_group->getCustomerGroups();

            foreach ($customer_groups as $customer_group) {

                //customer_group_id
                $customer_group['value'] = $customer_group['customer_group_id'];

                //name
                $customer_group['title'] = $customer_group['name'];

                if (in_array($customer_group['value'], $this->config->get('config_customer_group_display'))) {
                    $result[] = $customer_group;
                }
            }
        }

        return $result;
    }

    public function getDefaultCustomerGroup(){
        if($this->config->get('config_customer_group_id')){
            return $this->config->get('config_customer_group_id');
        }else{
            $this->load->model('setting/setting');
            return $this->model_setting_setting->getSettingValue('config_customer_group_id', $this->config->get('config_store_id'));
        }
    }

    public function getCustomFields(){

        $this->load->model('account/custom_field');
        $custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));
        
        $result = array();

        foreach($custom_fields as $custom_field){
            $options = array();
            foreach($custom_field['custom_field_value'] as $option){
                $options[] = array('name' => $option['name'] , 'value' => $option['custom_field_value_id']);
            }

            $result[] = array(
                'custom_field_id'    => $custom_field['custom_field_id'],
                'custom_field_value' => isset($custom_field['custom_field_value']) ? $custom_field['custom_field_value'] : '',
                'options'            => $options,
                'name'               => isset($custom_field['name']) ? $custom_field['name'] : '',
                'type'               => isset($custom_field['type']) ? $custom_field['type'] : '',
                'value'              => isset($custom_field['value']) ? $custom_field['value'] : '',
                'validation'         => isset($custom_field['validation']) ? $custom_field['validation'] : '',
                'location'           => isset($custom_field['location']) ? $custom_field['location'] : '',
                'required'           => isset($custom_field['required']) ? $custom_field['required'] : '',
                'sort_order'         => isset($custom_field['sort_order']) ? $custom_field['sort_order'] : '',
            );
        }
        return $result;
    }

}
