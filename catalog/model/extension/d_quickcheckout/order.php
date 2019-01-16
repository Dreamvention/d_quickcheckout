<?php
class ModelExtensionDQuickcheckoutOrder extends Model {


    

  public function getOrder() {

    if(!empty($this->session->data['order_id']) && $this->getOrderStatusId($this->session->data['order_id']) == 0){
      return $this->session->data['order_id'];
    }

    $data = array();
    $totals = array();
    $taxes = $this->cart->getTaxes();
    $total = 0;

    $total_data = array(
        'totals' => &$totals,
        'taxes'  => &$taxes,
        'total'  => &$total
    );

    $this->getTotals($total_data);
    $data = $this->prepareCustomerData($data);
    $data = $this->preparePaymentAddressData($data);
    $data = $this->prepareMarketingData($data);

//refactor - create full $data
    $this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET
      invoice_prefix = '" .$this->db->escape($this->config->get('config_invoice_prefix')) . "',
      store_id = '" . (int) $this->config->get('config_store_id') . "',
      store_name = '" . $this->db->escape($this->config->get('config_name')) . "',
      store_url = '" . $this->db->escape(($this->config->get('config_store_id')) ? $this->config->get('config_url') : HTTP_SERVER) . "',
      total = '" . (float) $total . "',
      payment_country_id = '" . (int) $data['payment_country_id'] . "',
      payment_zone_id = '" . (int) $data['payment_zone_id'] . "',
      affiliate_id = '" . (int) $data['affiliate_id'] . "',
      commission = '" . (float) $data['commission'] . "',
      language_id = '" . (int)  $this->config->get('config_language_id') . "',
      currency_id = '" . (int) $this->currency->getId($this->session->data['currency']) . "',
      currency_code = '" . $this->db->escape($this->session->data['currency']) . "',
      currency_value = '" . (float) $this->currency->getValue($this->session->data['currency']) . "',
      ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "',
      forwarded_ip = '" . $this->db->escape($data['forwarded_ip']) . "',
      user_agent = '" . $this->db->escape((isset($this->request->server['HTTP_USER_AGENT'])) ? $this->request->server['HTTP_USER_AGENT']: '') . "',
      accept_language = '" . $this->db->escape((isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) ? $this->request->server['HTTP_ACCEPT_LANGUAGE']: '') . "',
      date_added = NOW(),
      date_modified = NOW()");

    $order_id = $this->db->getLastId();

    return $order_id;
  }

  public function getOrderStatusId($order_id){
    $order_query = $this->db->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

    if(isset($order_query->row['order_status_id'])){
      return $order_query->row['order_status_id'];
    }
    return false;
  }

  public function updateOrder(){
    $data = array();

    $totals = array();
    $taxes = $this->cart->getTaxes();
    $total = 0;

    $total_data = array(
      'totals' => &$totals,
      'taxes'  => &$taxes,
      'total'  => &$total
    );

    $this->getTotals($total_data);

    $this->load->language('checkout/checkout');

    $data = $this->prepareCustomerData($data);
    $data = $this->preparePaymentAddressData($data);
    $data = $this->prepareShippingAddressData($data);
    $data = $this->prepareShippingMethodData($data);
    $data = $this->preparePaymentMethodData($data);
    $data = $this->prepareCartData($data);
    $data = $this->prepareMarketingData($data);

    if(!isset($this->session->data['order_id'])){
        return false;
    }
    $order_id = $this->session->data['order_id'];

    //fix
    if(VERSION < '2.3.0.0'){
        $this->event->trigger('pre.order.add', $data);
    }
//refactor - create full $data
    $query = "UPDATE `" . DB_PREFIX . "order` SET
      customer_id = '" . (int) $data['customer_id'] . "',
      customer_group_id = '" . (int) $data['customer_group_id'] . "',
      firstname = '" . $this->db->escape($data['firstname']) . "',
      lastname = '" . $this->db->escape($data['lastname']) . "',
      email = '" . $this->db->escape($data['email']) . "',
      telephone = '" . $this->db->escape($data['telephone']) . "',
      fax = '" . $this->db->escape($data['fax']) . "',
      custom_field = '" . $this->db->escape($data['custom_field']). "',

      payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "',
      payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "',
      payment_company = '" . $this->db->escape($data['payment_company']) . "',
      payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "',
      payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "',
      payment_city = '" . $this->db->escape($data['payment_city']) . "',
      payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "',
      payment_country = '" . $this->db->escape($data['payment_country']) . "',
      payment_country_id = '" . (int) $data['payment_country_id'] . "',
      payment_zone = '" . $this->db->escape($data['payment_zone']) . "',
      payment_zone_id = '" . (int) $data['payment_zone_id'] . "',
      payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "',
      payment_custom_field = '" . $this->db->escape($data['payment_custom_field']) . "',

      payment_method = '" . $this->db->escape($data['payment_method']) . "',
      payment_code = '" . $this->db->escape($data['payment_code']) . "',

      shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "',
      shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "',
      shipping_company = '" . $this->db->escape($data['shipping_company']) . "',
      shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "',
      shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "',
      shipping_city = '" . $this->db->escape($data['shipping_city']) . "',
      shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "',
      shipping_country = '" . $this->db->escape($data['shipping_country']) . "',
      shipping_country_id = '" . (int) $data['shipping_country_id'] . "',
      shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "',
      shipping_zone_id = '" . (int) $data['shipping_zone_id'] . "',
      shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "',
      shipping_custom_field = '" . $this->db->escape($data['shipping_custom_field']) ."',

      shipping_method = '" . $this->db->escape($data['shipping_method']) . "',
      shipping_code = '" . $this->db->escape($data['shipping_code']) . "',

      comment = '" . $this->db->escape($data['comment']) . "',

      total = '" . (float) $total_data['total'] . "',
      affiliate_id = '" . (int) $data['affiliate_id'] . "',
      commission = '" . (float) $data['commission'] . "',
      marketing_id = '" . (int) $data['marketing_id'] . "',
      tracking = '" . $this->db->escape($data['tracking']) . "',
      language_id = '" . (int)  $this->config->get('config_language_id') . "',
      currency_id = '" . (int) $this->currency->getId($this->session->data['currency']) . "',
      currency_code = '" . $this->db->escape($this->session->data['currency']) . "',
      currency_value = '" . (float) $this->currency->getValue($this->session->data['currency']) . "',
      ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "',
      forwarded_ip = '" . $this->db->escape($data['forwarded_ip']) . "',
      user_agent = '" . $this->db->escape((isset($this->request->server['HTTP_USER_AGENT'])) ? $this->request->server['HTTP_USER_AGENT']: '') . "',
      accept_language = '" . $this->db->escape((isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) ? $this->request->server['HTTP_ACCEPT_LANGUAGE']: '') . "',
      date_added = NOW(),
      date_modified = NOW()
      WHERE order_id = '" . (int) $order_id . "'";

    $this->db->query($query);

    $this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");
    $this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "'");


    // Products
    foreach ($data['products'] as $product) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int) $order_id . "', product_id = '" . (int) $product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int) $product['quantity'] . "', price = '" . (float) $product['price'] . "', total = '" . (float) $product['total'] . "', tax = '" . (float) $product['tax'] . "', reward = '" . (int) $product['reward'] . "'");

        $order_product_id = $this->db->getLastId();

        foreach ($product['option'] as $option) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int) $order_id . "', order_product_id = '" . (int) $order_product_id . "', product_option_id = '" . (int) $option['product_option_id'] . "', product_option_value_id = '" . (int) $option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
        }
    }

    // Gift Voucher
    $this->db->query("DELETE FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int) $order_id . "'");

    // Vouchers
    foreach ($data['vouchers'] as $voucher) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int) $order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int) $voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float) $voucher['amount'] . "'");

        $order_voucher_id = $this->db->getLastId();

        $voucher_id = $this->addVoucher($order_id, $voucher);

        $this->db->query("UPDATE " . DB_PREFIX . "order_voucher SET voucher_id = '" . (int) $voucher_id . "' WHERE order_voucher_id = '" . (int) $order_voucher_id . "'");
    }

    // Totals
    $this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "'");
    foreach ($total_data['totals'] as $total) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float) $total['value'] . "', sort_order = '" . (int) $total['sort_order'] . "'");
    }
    if(VERSION < '2.3.0.0'){
        $this->event->trigger('post.order.add', $order_id);
    }

    return $order_id;
  }

    /*

    $total_data = array(
        'totals' => &$totals,
        'taxes'  => &$taxes,
        'total'  => &$total
    );

     */
//REFACTOR - CALLED TWICE IN THE SYSTEM!!!!
    public function getTotals($total_data = array()) {

        if(VERSION < '3.0.0.0'){
            $this->load->model('extension/extension');
            $results = $this->model_extension_extension->getExtensions('total');
            $prefix = "";
        }else{
            $this->load->model('setting/extension');
            $results = $this->model_setting_extension->getExtensions('total');
            $prefix = "total_";
        }

      $sort_order = array();

      

      foreach ($results as $key => $value) {
          if(VERSION < '3.0.0.0'){
              $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
          }else{
              $sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
          }
      }

      array_multisort($sort_order, SORT_ASC, $results);

      foreach ($results as $result) {
          if ($this->config->get($prefix . $result['code'] . '_status')) {

              if(VERSION < '2.2.0.0'){
                  $this->load->model('total/' . $result['code']);
                  $this->{'model_total_' . $result['code']}->getTotal($total_data['totals'], $total_data['total'], $total_data['taxes']);
              }elseif(VERSION < '2.3.0.0'){
                  $this->load->model('total/' . $result['code']);
                  $this->{'model_total_' . $result['code']}->getTotal($total_data);
              }else{
                  $this->load->model('extension/total/' . $result['code']);
                  $this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
              }

          }
      }

      $sort_order = array();

      foreach ($total_data['totals'] as $key => $value) {
          $sort_order[$key] = $value['sort_order'];
      }
      array_multisort($sort_order, SORT_ASC, $total_data['totals']);


      $totals = array();
      foreach ($total_data['totals'] as $total) {
          if(!empty($total['title'])){
              $totals[] = array(
                  'title' => $total['title'],
                  'text'  => $this->currency->format($total['value'], $this->session->data['currency']),
              );
          }
      }

      //REFECTOR!!!!
      $this->session->data['total'] = $total_data['total'];
      return $totals;
    }

    public function prepareCustomerData($data = array()){

      $data['customer_id'] = 0;
      $data['customer_group_id'] = 0;
      $data['firstname'] = '';
      $data['lastname'] = '';
      $data['email'] = '';
      $data['telephone'] = '';
      $data['fax'] = '';
      $data['custom_field'] = array();

      if ($this->customer->isLogged()) {
        $this->load->model('account/customer');
        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

        $data['customer_id'] = $this->customer->getId();
        $data['customer_group_id'] = $customer_info['customer_group_id'];
        $data['firstname'] = $customer_info['firstname'];
        $data['lastname'] = $customer_info['lastname'];
        $data['email'] = $customer_info['email'];
        $data['telephone'] = $customer_info['telephone'];
        $data['fax'] = $customer_info['fax'];

        //refactor - move this check to database insert
        if(VERSION >= '2.1.0.1'){
          $data['custom_field'] = json_decode($customer_info['custom_field'], true);
        }else{
          $data['custom_field'] = unserialize($customer_info['custom_field']);
        }
      } elseif (isset($this->session->data['payment_address'])) {
        $data['customer_id'] = 0;
        $data['customer_group_id'] = $this->session->data['payment_address']['customer_group_id'];
        $data['firstname'] = $this->session->data['payment_address']['firstname'];
        $data['lastname'] = $this->session->data['payment_address']['lastname'];
        $data['email'] = $this->session->data['payment_address']['email'];
        $data['telephone'] = $this->session->data['payment_address']['telephone'];
        $data['fax'] = $this->session->data['payment_address']['fax'];
        $data['custom_field'] = (isset($this->session->data['payment_address']['custom_field']['account'])) ? $this->session->data['payment_address']['custom_field']['account'] : array();
      }

      //opencart fix: always provide an email.
      if (empty($data['email'])) {
        $data['email'] = $this->config->get('config_email');
      }

  //refactor - move this check to database insert
      if(VERSION >= '2.1.0.1'){
        $data['custom_field'] = json_encode($data['custom_field']);
      }else{
        $data['custom_field'] = serialize($data['custom_field']);
      }

      if(isset($this->session->data['comment'])){
            $data['comment'] = $this->session->data['comment'];
      }

      if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
          $data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
      } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
          $data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
      } else {
          $data['forwarded_ip'] = '';
      }

      return $data;
    }

    public function preparePaymentAddressData($data = array()){


      $data['payment_firstname'] = '';
      $data['payment_lastname'] = '';
      $data['payment_company'] = '';
      $data['payment_address_1'] = '';
      $data['payment_address_2'] = '';
      $data['payment_city'] = '';
      $data['payment_postcode'] = '';
      $data['payment_zone'] = '';
      $data['payment_zone_id'] = $this->config->get('config_zone_id');
      $data['payment_country'] = '';
      $data['payment_country_id'] = $this->config->get('config_country_id');
      $data['payment_address_format'] = '';
      $data['payment_custom_field'] = array();
      $data['payment_iso_code_2'] = '';
      $data['payment_iso_code_3'] = '';
      $data['payment_zone_code'] = '';

      if(isset($this->session->data['payment_address'])){
        $data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
        $data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
        $data['payment_company'] = $this->session->data['payment_address']['company'];
        $data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
        $data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
        $data['payment_city'] = $this->session->data['payment_address']['city'];
        $data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
        $data['payment_zone'] = $this->session->data['payment_address']['zone'];
        $data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
        $data['payment_country'] = $this->session->data['payment_address']['country'];
        $data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
        $data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
        $data['payment_custom_field'] = (isset($this->session->data['payment_address']['custom_field']['address']) ? $this->session->data['payment_address']['custom_field']['address'] : array());
        $data['payment_iso_code_2'] = $this->session->data['payment_address']['iso_code_2'];
        $data['payment_iso_code_3'] =  $this->session->data['payment_address']['iso_code_3'];
        $data['payment_zone_code'] =  $this->session->data['payment_address']['zone_code'];
      }
  //refactor - move this check to database insert. because we need a valid array for the events
      if (VERSION >= '2.1.0.1') {
          $data['payment_custom_field'] = json_encode($data['payment_custom_field']);
      } else {
          $data['payment_custom_field'] = serialize($data['payment_custom_field']);
      }

      return $data;
    }

    public function prepareShippingAddressData($data = array()){

      $data['shipping_firstname'] ='';
      $data['shipping_lastname'] ='';
      $data['shipping_company'] ='';
      $data['shipping_address_1'] ='';
      $data['shipping_address_2'] ='';
      $data['shipping_city'] ='';
      $data['shipping_postcode'] ='';
      $data['shipping_zone'] ='';
      $data['shipping_zone_id'] ='';
      $data['shipping_country'] ='';
      $data['shipping_country_id'] ='';
      $data['shipping_address_format'] ='';
      $data['shipping_custom_field'] = array();
      $data['shipping_iso_code_2'] = '';
      $data['shipping_iso_code_3'] = '';
      $data['shipping_zone_code'] = '';

      if ($this->cart->hasShipping()) {
        if(!$this->session->data['payment_address']['shipping_address']){
          $data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
          $data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
          $data['shipping_company'] = $this->session->data['shipping_address']['company'];
          $data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
          $data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
          $data['shipping_city'] = $this->session->data['shipping_address']['city'];
          $data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
          $data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
          $data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
          $data['shipping_country'] = $this->session->data['shipping_address']['country'];
          $data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
          $data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
          $data['shipping_custom_field'] = (isset($this->session->data['shipping_address']['custom_field']['address']) ? $this->session->data['shipping_address']['custom_field']['address'] : array());
          $data['shipping_iso_code_2'] = $this->session->data['shipping_address']['iso_code_2'];
  				$data['shipping_iso_code_3'] =  $this->session->data['shipping_address']['iso_code_3'];
          $data['shipping_zone_code'] =  $this->session->data['shipping_address']['zone_code'];
        }else{
          $data['shipping_firstname'] = $this->session->data['payment_address']['firstname'];
          $data['shipping_lastname'] = $this->session->data['payment_address']['lastname'];
          $data['shipping_company'] = $this->session->data['payment_address']['company'];
          $data['shipping_address_1'] = $this->session->data['payment_address']['address_1'];
          $data['shipping_address_2'] = $this->session->data['payment_address']['address_2'];
          $data['shipping_city'] = $this->session->data['payment_address']['city'];
          $data['shipping_postcode'] = $this->session->data['payment_address']['postcode'];
          $data['shipping_zone'] = $this->session->data['payment_address']['zone'];
          $data['shipping_zone_id'] = $this->session->data['payment_address']['zone_id'];
          $data['shipping_country'] = $this->session->data['payment_address']['country'];
          $data['shipping_country_id'] = $this->session->data['payment_address']['country_id'];
          $data['shipping_address_format'] = $this->session->data['payment_address']['address_format'];
          $data['shipping_iso_code_2'] = $this->session->data['payment_address']['iso_code_2'];
  				$data['shipping_iso_code_3'] =  $this->session->data['payment_address']['iso_code_3'];
          $data['shipping_zone_code'] =  $this->session->data['payment_address']['zone_code'];
        }
      }

      //refactor - move this check to database insert. because we need a valid array for the events
      if (VERSION >= '2.1.0.1') {
          $data['shipping_custom_field'] = json_encode($data['shipping_custom_field']);
      } else {
          $data['shipping_custom_field'] = serialize($data['shipping_custom_field']);
      }
      return $data;
    }

  public function prepareShippingMethodData($data = array()){
    $data['shipping_method'] = '';
    $data['shipping_code'] = '';

    if ($this->cart->hasShipping()) {
      if (isset($this->session->data['shipping_method']['title'])) {
          $data['shipping_method'] = $this->session->data['shipping_method']['title'];
      }
      if (isset($this->session->data['shipping_method']['code'])) {
          $data['shipping_code'] = $this->session->data['shipping_method']['code'];
      }
    }
    return $data;
  }

    public function preparePaymentMethodData($data = array()){
      $data['payment_method'] = '';
      $data['payment_code'] = '';

      if (isset($this->session->data['payment_method']['title'])) {
        $data['payment_method'] = $this->session->data['payment_method']['title'];
      }

      if (isset($this->session->data['payment_method']['code'])) {
        $data['payment_code'] = $this->session->data['payment_method']['code'];
      }

      return $data;
    }

    public function prepareCartData($data = array()){
      $data['products'] = array();

      foreach ($this->cart->getProducts() as $product) {
          $option_data = array();

          foreach ($product['option'] as $option) {
              $option_data[] = array(
                  'product_option_id'       => $option['product_option_id'],
                  'product_option_value_id' => $option['product_option_value_id'],
                  'option_id'               => $option['option_id'],
                  'option_value_id'         => $option['option_value_id'],
                  'name'                    => $option['name'],
                  'value'                   => $option['value'],
                  'type'                    => $option['type']
              );
          }

          $data['products'][] = array(
              'product_id' => $product['product_id'],
              'name'       => $product['name'],
              'model'      => $product['model'],
              'option'     => $option_data,
              'download'   => $product['download'],
              'quantity'   => $product['quantity'],
              'subtract'   => $product['subtract'],
              'price'      => $product['price'],
              'total'      => $product['total'],
              'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
              'reward'     => $product['reward']
          );
      }

      // Gift Voucher
      $data['vouchers'] = array();

      if (!empty($this->session->data['vouchers'])) {
          foreach ($this->session->data['vouchers'] as $voucher) {
              $data['vouchers'][] = array(
                  'description'      => $voucher['description'],
                  'code'             => substr(md5(mt_rand()), 0, 10),
                  'to_name'          => $voucher['to_name'],
                  'to_email'         => $voucher['to_email'],
                  'from_name'        => $voucher['from_name'],
                  'from_email'       => $voucher['from_email'],
                  'voucher_theme_id' => $voucher['voucher_theme_id'],
                  'message'          => $voucher['message'],
                  'amount'           => $voucher['amount']
              );
          }
      }

      return $data;
    }

    public function prepareMarketingData($data = array()){
      $data['affiliate_id'] = 0;
      $data['commission'] = 0;
      $data['marketing_id'] = 0;
      $data['tracking'] = '';

      if (isset($this->request->cookie['tracking'])) {
        $data['tracking'] = $this->request->cookie['tracking'];

        $subtotal = $this->cart->getSubTotal();

        if(VERSION < '3.0.0.0'){
            // Affiliate
            $this->load->model('affiliate/affiliate');
            $affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
            if ($affiliate_info) {
                $data['affiliate_id'] = $affiliate_info['affiliate_id'];
                $data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
            } else {
                $data['affiliate_id'] = 0;
                $data['commission'] = 0;
            }
        }else{
            // Affiliate
            $this->load->model('account/customer');
            $affiliate_info = $this->model_account_customer->getAffiliateByTracking($this->request->cookie['tracking']);
            if ($affiliate_info) {
                $data['affiliate_id'] = $affiliate_info['customer_id'];
                $data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
            } else {
                $data['affiliate_id'] = 0;
                $data['commission'] = 0;
            }
        }

        // Marketing
        $this->load->model('checkout/marketing');

        $marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

        if ($marketing_info) {
          $data['marketing_id'] = $marketing_info['marketing_id'];
        } else {
          $data['marketing_id'] = 0;
        }
      }

      return $data;
    }

    public function initCart(){
        if(VERSION < '2.1.0.0'){
            return;
        }

        if(VERSION < '2.3.0.0'){
            $this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE customer_id = '0' AND date_added < DATE_SUB(NOW(), INTERVAL 1 HOUR)");

            if ($this->customer->getId()) {
                // We want to change the session ID on all the old items in the customers cart
                $this->db->query("UPDATE " . DB_PREFIX . "cart SET session_id = '" . $this->db->escape($this->session->getId()) . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");

                // Once the customer is logged in we want to update the customer ID on all items he has
                $cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE customer_id = '0' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

                foreach ($cart_query->rows as $cart) {
                    $this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart['cart_id'] . "'");

                    // The advantage of using $this->add is that it will check if the products already exist and increaser the quantity if necessary.
                    $this->cart->add($cart['product_id'], $cart['quantity'], json_decode($cart['option']), $cart['recurring_id']);
                }
            }
        }else{
            $this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE (api_id > '0' OR customer_id = '0') AND date_added < DATE_SUB(NOW(), INTERVAL 1 HOUR)");

            if ($this->customer->getId()) {
                // We want to change the session ID on all the old items in the customers cart
                $this->db->query("UPDATE " . DB_PREFIX . "cart SET session_id = '" . $this->db->escape($this->session->getId()) . "' WHERE api_id = '0' AND customer_id = '" . (int)$this->customer->getId() . "'");

                // Once the customer is logged in we want to update the customers cart
                $cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE api_id = '0' AND customer_id = '0' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

                foreach ($cart_query->rows as $cart) {
                    $this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart['cart_id'] . "'");

                    // The advantage of using $this->add is that it will check if the products already exist and increaser the quantity if necessary.
                    $this->cart->add($cart['product_id'], $cart['quantity'], json_decode($cart['option']), $cart['recurring_id']);
                }
            }
        }
    }

}
