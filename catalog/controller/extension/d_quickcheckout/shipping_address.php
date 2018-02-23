<?php 

class ControllerExtensionDQuickcheckoutShippingAddress extends Controller {
   
	public function index($config){
        $this->load->model('extension/d_quickcheckout/address');
        $this->load->model('extension/module/d_quickcheckout');
        $this->model_extension_module_d_quickcheckout->logWrite('controller:: shipping_address/index');

        if(!$config['general']['compress']){
            $this->document->addScript('catalog/view/javascript/d_quickcheckout/model/shipping_address.js');
            $this->document->addScript('catalog/view/javascript/d_quickcheckout/view/shipping_address.js');
        }
        
        $data['col'] = $config['account']['guest']['shipping_address']['column'];
        $data['row'] = $config['account']['guest']['shipping_address']['row'];

        $data['text_address_existing'] = $this->language->get('text_address_existing');
        $data['text_address_new'] = $this->language->get('text_address_new');

        $json['account'] = $this->session->data['account'];
        $json['shipping_address'] = $this->session->data['shipping_address'];
        $json['show_shipping_address'] = $this->model_extension_d_quickcheckout_address->showShippingAddress(); 

        if($this->customer->isLogged()){

            $json['addresses'] = $this->model_extension_d_quickcheckout_address->getAddresses();

            if (!empty($this->session->data['shipping_address']['address_id'])) {
                $json['shipping_address']['address_id'] = $this->session->data['shipping_address']['address_id'];
            } else {
                $json['shipping_address']['address_id'] = $this->customer->getAddressId();
            }
        }

        $data['json'] = json_encode($json);

        if(VERSION >= '2.2.0.0'){
            $template = 'd_quickcheckout/shipping_address';
        }elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/d_quickcheckout/shipping_address.tpl')) {
			$template = $this->config->get('config_template') . '/template/d_quickcheckout/shipping_address.tpl';
		} else {
			$template = 'default/template/d_quickcheckout/shipping_address.tpl';
		}

        $this->load->model('extension/d_opencart_patch/load');
        return $this->model_extension_d_opencart_patch_load->view($template, $data);
	}

	public function update(){
        $this->load->model('extension/module/d_quickcheckout');
        $this->load->model('extension/d_quickcheckout/address');
        $this->load->model('extension/d_quickcheckout/method');
        $this->load->model('extension/d_quickcheckout/order');

        $json = array();

        //shipping address
        $json = $this->prepare($json);

        //tax
        $this->model_extension_d_quickcheckout_address->updateTaxAddress();

        //shipping methods
        $json = $this->load->controller('extension/d_quickcheckout/shipping_method/prepare', $json);

        //payment methods
        $json = $this->load->controller('extension/d_quickcheckout/payment_method/prepare', $json);

        $json = $this->load->controller('extension/d_quickcheckout/cart/prepare', $json);
        $totals = array();
        $taxes = $this->cart->getTaxes();
        $total = 0;

        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
        );
        $json['totals'] = $this->session->data['totals'] = $this->model_extension_d_quickcheckout_order->getTotals($total_data);
        $json['total'] = $this->model_extension_d_quickcheckout_order->getCartTotal($total);
        
        $json['order_id'] = $this->session->data['order_id'] = $this->load->controller('extension/d_quickcheckout/confirm/updateOrder');

        //payment
        $json = $this->load->controller('extension/d_quickcheckout/payment/prepare', $json);

        //statistic
        $statistic = array(
            'update' => array(
                'shipping_address' => 1
            )
        );
        $this->model_extension_module_d_quickcheckout->updateStatistic($statistic);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function prepare($json){
        $this->load->model('extension/d_quickcheckout/address');
        $this->load->model('account/address');
        $this->load->model('extension/d_quickcheckout/custom_field');
        $json['show_shipping_address'] = $this->model_extension_d_quickcheckout_address->showShippingAddress();

        if($json['show_shipping_address']){

            if($this->customer->isLogged()){
                if($this->model_account_address->getAddresses()){
                    if(empty($this->session->data['shipping_address']['address_id'])){
                        $this->session->data['shipping_address'] = current($this->model_extension_d_quickcheckout_address->getAddresses());
                    }
                }else{
                    $this->session->data['shipping_address']['country_id'] =  $this->config->get('config_country_id');
                    $this->session->data['payment_address']['zone_id'] = $this->config->get('config_zone_id');
                }


                if(empty($this->session->data['shipping_address']['address_id'])){
                    $this->session->data['shipping_address']['address_id'] = 'new';
                }
            }

        }else{
            $this->session->data['shipping_address'] = $this->session->data['payment_address'];
        }

        //post
        if(isset($this->request->post['shipping_address'])){

            //fix &amp;
            foreach ($this->request->post['shipping_address'] as $key => $value) {
                if(is_array($this->request->post['shipping_address'][$key])){
                    foreach ($this->request->post['shipping_address'][$key] as $custom_field_type => $custom_field_data) {
                        foreach ($custom_field_data as $custom_field_id => $custom_field_value) {
                            $this->request->post['shipping_address'][$key][$custom_field_type][$custom_field_id] = htmlspecialchars_decode($custom_field_value);
                        }
                    }
                } else {
                    $this->request->post['shipping_address'][$key] = htmlspecialchars_decode($value);
                }
            }
            
            if(isset($this->session->data['shipping_address']['country_id']) && isset($this->request->post['shipping_address']['country_id'])){
                if($this->session->data['shipping_address']['country_id'] != $this->request->post['shipping_address']['country_id']){
                    $json['payment_address_refresh'] = true;
                    $json['shipping_address_refresh'] = true;
                }
            }
            if(isset($this->session->data['shipping_address']['city_id']) && isset($this->request->post['shipping_address']['city_id'])){
                if($this->session->data['shipping_address']['city_id'] != $this->request->post['shipping_address']['city_id']){
                    $json['payment_address_refresh'] = true;
                    $json['shipping_address_refresh'] = true;
                }
            }
            $this->request->post['shipping_address'] = $this->model_extension_d_quickcheckout_address->compareAddress($this->request->post['shipping_address'], $this->session->data['shipping_address']);

            if($this->customer->isLogged()){
                if( !empty($this->request->post['shipping_address']['address_id'])
                    && $this->request->post['shipping_address']['address_id'] !== 'new' 
                    && $this->request->post['shipping_address']['address_id'] !== $this->session->data['shipping_address']['address_id'] 
                    ){

                    $this->request->post['shipping_address'] = $this->model_extension_d_quickcheckout_address->getAddress($this->request->post['shipping_address']['address_id']);
                }
            }
            if(isset($this->request->post['shipping_address']['customer_group_id']) && isset($this->request->post['payment_address']['customer_group_id'])){
                $this->request->post['shipping_address']['custom_field'] =  ((!empty($this->request->post['shipping_address']['custom_field']['address'])) ? array('address' => $this->request->post['shipping_address']['custom_field']['address']) : $this->model_extension_d_quickcheckout_custom_field->setCustomFieldsDefaultSessionData('address', $this->request->post['payment_address']['customer_group_id']));
            }
            
            if(isset($this->request->post['shipping_address']['custom_field']) && is_array($this->request->post['shipping_address']['custom_field'])){
                $this->request->post['shipping_address'] = array_merge($this->request->post['shipping_address'], $this->model_extension_d_quickcheckout_custom_field->setCustomFieldValue($this->request->post['shipping_address']['custom_field']));
            }
            //merge post into session
            $this->session->data['shipping_address'] = array_merge($this->session->data['shipping_address'], $this->request->post['shipping_address']);

        }
        $this->model_extension_d_quickcheckout_custom_field->updateCustomFieldsConfigData('shipping_address');


        //session
       
        $json['shipping_address'] = $this->session->data['shipping_address'];

        return $json;
    }


}